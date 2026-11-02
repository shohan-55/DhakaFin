<?php

namespace App\Http\Controllers;

use App\Services\CmsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function __construct(protected CmsManager $cms)
    {
    }

    /**
     * Compute tax/VAT/TDS/VDS/Mushak results for all 10 DhakaFin digital tools.
     */
    public function calculate(Request $request): JsonResponse
    {
        $tool = $request->input('tool', 'calculator');
        $rates = $this->cms->get('tax_rates', []);

        switch ($tool) {
            case 'personal-tax':
                $income = max(0, (float) $request->input('income', 0));
                $other = max(0, (float) $request->input('other', 0));
                $investment = max(0, (float) $request->input('investment', 0));
                $status = (string) $request->input('status', 'General');
                $year = (string) $request->input('assessment', '2026–2027');

                $totalIncome = $income + $other;
                $threshold = (float) ($rates['personal_general_threshold'] ?? 350000);
                if (stripos($status, 'Female') !== false || stripos($status, 'Senior') !== false) {
                    $threshold = (float) ($rates['personal_female_senior_threshold'] ?? 400000);
                } elseif (stripos($status, 'Other') !== false) {
                    $threshold = (float) ($rates['personal_other_threshold'] ?? 475000);
                }

                $remaining = max(0, $totalIncome - $threshold);
                $grossTax = 0.0;
                $slabs = [
                    [100000, 0.05],
                    [400000, 0.10],
                    [500000, 0.15],
                    [500000, 0.20],
                    [2000000, 0.25],
                ];
                foreach ($slabs as [$limit, $rate]) {
                    if ($remaining <= 0) {
                        break;
                    }
                    $slice = min($remaining, $limit);
                    $grossTax += $slice * $rate;
                    $remaining -= $slice;
                }
                if ($remaining > 0) {
                    $grossTax += $remaining * 0.30;
                }

                $rebateRate = ((float) ($rates['personal_rebate_rate'] ?? 15)) / 100.0;
                $rebate = min($investment * $rebateRate, $totalIncome * 0.03, 1000000, $grossTax);
                $netTax = max(0, $grossTax - $rebate);
                if ($totalIncome > $threshold && $netTax > 0 && $netTax < 5000) {
                    $netTax = 5000; // Minimum tax when above threshold
                }

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'C O M P U T E D',
                    'row1_label' => 'Total assessable income (' . $year . ')',
                    'row1_value' => '৳ ' . number_format($totalIncome, 2),
                    'row2_label' => 'Tax-free threshold & investment rebate',
                    'row2_value' => 'Threshold ৳ ' . number_format($threshold) . ' · Rebate ৳ ' . number_format($rebate, 2),
                    'row3_label' => 'Estimated net income tax payable',
                    'row3_value' => '৳ ' . number_format($netTax, 2),
                    'notice' => sprintf(
                        'Gross slab tax: ৳ %s | Less investment tax rebate (%s%%): ৳ %s | Net payable tax: ৳ %s.',
                        number_format($grossTax, 2),
                        number_format($rebateRate * 100, 0),
                        number_format($rebate, 2),
                        number_format($netTax, 2)
                    ),
                    'message' => 'Personal income tax computation complete based on Income Tax Act 2023 progressive slabs.',
                ]);

            case 'corporate-tax':
                $profit = max(0, (float) $request->input('profit', 0));
                $adjustment = max(0, (float) $request->input('adjustment', 0));
                $deduction = max(0, (float) $request->input('deduction', 0));
                $business = (string) $request->input('business', 'Private company');
                $year = (string) $request->input('year', '2026–2027');

                $taxableProfit = max(0, $profit + $adjustment - $deduction);
                $rate = (float) ($rates['corporate_private_rate'] ?? 27.5);
                if (stripos($business, 'Listed') !== false) {
                    $rate = (float) ($rates['corporate_listed_rate'] ?? 20.0);
                } elseif (stripos($business, 'Other') !== false) {
                    $rate = (float) ($rates['corporate_other_rate'] ?? 30.0);
                }
                $tax = $taxableProfit * ($rate / 100.0);

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'C O M P U T E D',
                    'row1_label' => 'Taxable corporate income',
                    'row1_value' => '৳ ' . number_format($taxableProfit, 2),
                    'row2_label' => 'Applicable statutory rate (' . $business . ')',
                    'row2_value' => number_format($rate, 2) . '% (' . $year . ')',
                    'row3_label' => 'Estimated corporate tax liability',
                    'row3_value' => '৳ ' . number_format($tax, 2),
                    'notice' => sprintf(
                        'Accounting profit ৳ %s + Adjustments ৳ %s − Deductions ৳ %s = Taxable income ৳ %s at %s%%.',
                        number_format($profit, 2),
                        number_format($adjustment, 2),
                        number_format($deduction, 2),
                        number_format($taxableProfit, 2),
                        number_format($rate, 2)
                    ),
                    'message' => 'Corporate tax computation complete.',
                ]);

            case 'tds-calculator':
                $category = (string) $request->input('category', 'Professional services');
                $base = max(0, (float) $request->input('base', 0));
                $reference = (string) ($request->input('reference') ?: 'Income Tax Act 2023 Part VII');

                $rate = (float) ($rates['tds_professional_rate'] ?? 10.0);
                if (stripos($category, 'Rent') !== false) {
                    $rate = (float) ($rates['tds_rent_rate'] ?? 5.0);
                } elseif (stripos($category, 'Suppl') !== false) {
                    $rate = (float) ($rates['tds_supplies_rate'] ?? 5.0);
                } elseif (stripos($category, 'Contract') !== false) {
                    $rate = (float) ($rates['tds_contract_rate'] ?? 7.0);
                } elseif (stripos($category, 'Other') !== false) {
                    $rate = (float) ($rates['tds_other_rate'] ?? 5.0);
                }

                $tds = $base * ($rate / 100.0);
                $netPayable = max(0, $base - $tds);

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'T D S  R E A D Y',
                    'row1_label' => 'Base payment amount (' . $category . ')',
                    'row1_value' => '৳ ' . number_format($base, 2),
                    'row2_label' => 'Applicable TDS rate (' . $reference . ')',
                    'row2_value' => number_format($rate, 2) . '%',
                    'row3_label' => 'TDS to deduct & deposit',
                    'row3_value' => '৳ ' . number_format($tds, 2) . ' (Net pay: ৳ ' . number_format($netPayable, 2) . ')',
                    'notice' => sprintf(
                        'Deduct ৳ %s (%s%%) at source and pay net ৳ %s to the supplier/payee.',
                        number_format($tds, 2),
                        number_format($rate, 2),
                        number_format($netPayable, 2)
                    ),
                    'message' => 'Withholding tax (TDS) calculation complete.',
                ]);

            case 'vds-calculator':
                $category = (string) $request->input('category', 'Professional services');
                $base = max(0, (float) $request->input('base', 0));
                $reference = (string) ($request->input('reference') ?: 'VAT & SD Act 2012');

                $rate = (float) ($rates['vds_professional_rate'] ?? 15.0);
                if (stripos($category, 'Rent') !== false) {
                    $rate = (float) ($rates['vds_rent_rate'] ?? 15.0);
                } elseif (stripos($category, 'Suppl') !== false) {
                    $rate = (float) ($rates['vds_supplies_rate'] ?? 7.5);
                } elseif (stripos($category, 'Contract') !== false) {
                    $rate = (float) ($rates['vds_contract_rate'] ?? 10.0);
                } elseif (stripos($category, 'Other') !== false) {
                    $rate = (float) ($rates['vds_other_rate'] ?? 15.0);
                }

                $vds = $base * ($rate / 100.0);
                $netPayable = max(0, $base - $vds);

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'V D S  R E A D Y',
                    'row1_label' => 'Supply base value (' . $category . ')',
                    'row1_value' => '৳ ' . number_format($base, 2),
                    'row2_label' => 'Applicable VDS rate (' . $reference . ')',
                    'row2_value' => number_format($rate, 2) . '%',
                    'row3_label' => 'VDS to withhold (Mushak 6.6)',
                    'row3_value' => '৳ ' . number_format($vds, 2),
                    'notice' => sprintf(
                        'Withhold ৳ %s (%s%% VDS) and issue Mushak 6.6 certificate. Net payment before VDS adjustment: ৳ %s.',
                        number_format($vds, 2),
                        number_format($rate, 2),
                        number_format($netPayable, 2)
                    ),
                    'message' => 'VAT deduction at source (VDS) calculation complete.',
                ]);

            case 'vat-calculator':
                $amount = max(0, (float) $request->input('amount', 0));
                $method = (string) $request->input('method', 'VAT exclusive');
                $rateCat = (string) $request->input('rate', 'Standard rate');

                $rate = (float) ($rates['vat_standard_rate'] ?? 15.0);
                if (stripos($rateCat, 'Reduced') !== false) {
                    $rate = (float) ($rates['vat_reduced_rate'] ?? 7.5);
                } elseif (stripos($rateCat, 'Exempt') !== false || stripos($rateCat, 'Zero') !== false) {
                    $rate = (float) ($rates['vat_exempt_rate'] ?? 0.0);
                }

                if (stripos($method, 'inclusive') !== false) {
                    $vat = $rate > 0 ? ($amount * $rate) / (100.0 + $rate) : 0.0;
                    $base = $amount - $vat;
                    $total = $amount;
                } else {
                    $base = $amount;
                    $vat = $base * ($rate / 100.0);
                    $total = $base + $vat;
                }

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'V A T  R E A D Y',
                    'row1_label' => 'Tax-exclusive base value (' . $method . ')',
                    'row1_value' => '৳ ' . number_format($base, 2),
                    'row2_label' => 'Applicable VAT rate',
                    'row2_value' => number_format($rate, 2) . '% (VAT: ৳ ' . number_format($vat, 2) . ')',
                    'row3_label' => 'Total invoice value (incl. VAT)',
                    'row3_value' => '৳ ' . number_format($total, 2),
                    'notice' => sprintf(
                        'Base value: ৳ %s | VAT (%s%%): ৳ %s | Gross total: ৳ %s.',
                        number_format($base, 2),
                        number_format($rate, 2),
                        number_format($vat, 2),
                        number_format($total, 2)
                    ),
                    'message' => 'VAT calculation complete.',
                ]);

            case 'mushak-6-3':
                $invoiceNo = (string) ($request->input('invoice') ?: ('M63-' . date('Y') . '-' . rand(100, 999)));
                $date = (string) ($request->input('date') ?: date('Y-m-d'));
                $supplier = (string) ($request->input('supplier') ?: 'DhakaFin Advisory Ltd.');
                $buyer = (string) ($request->input('buyer') ?: 'Valued Client');
                $item = (string) ($request->input('item') ?: 'Taxable Supply / Service');
                $qty = max(1, (float) $request->input('qty', 1));
                $price = max(0, (float) $request->input('price', 0));

                $vatRate = (float) ($rates['vat_standard_rate'] ?? 15.0);
                $subtotal = $qty * $price;
                $vatAmount = $subtotal * ($vatRate / 100.0);
                $total = $subtotal + $vatAmount;

                // Save invoice to CMS store so it appears in mushak-list & mushak-detail
                $invoices = $this->cms->get('invoices', []);
                $newId = collect($invoices)->max('id') + 1;
                $newInvoice = [
                    'id' => $newId,
                    'invoice_no' => $invoiceNo,
                    'date' => $date,
                    'supplier' => $supplier,
                    'buyer' => $buyer,
                    'item' => $item,
                    'qty' => $qty,
                    'price' => $price,
                    'vat_rate' => $vatRate,
                    'vat_amount' => $vatAmount,
                    'total' => $total,
                    'status' => 'Issued',
                ];
                array_unshift($invoices, $newInvoice);
                $this->cms->put('invoices', $invoices);

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'M U S H A K  6 . 3',
                    'invoice' => $newInvoice,
                    'table_item' => $item . ' (' . $invoiceNo . ')',
                    'table_qty' => (string) $qty,
                    'table_val' => '৳ ' . number_format($total, 2) . ' (incl. ' . $vatRate . '% VAT)',
                    'notice' => sprintf(
                        'Mushak 6.3 #%s saved! Supplier: %s → Buyer: %s | Subtotal: ৳ %s | VAT (%s%%): ৳ %s | Total: ৳ %s.',
                        $invoiceNo,
                        $supplier,
                        $buyer,
                        number_format($subtotal, 2),
                        number_format($vatRate, 1),
                        number_format($vatAmount, 2),
                        number_format($total, 2)
                    ),
                    'message' => 'Mushak 6.3 invoice generated and saved to your Client Workspace!',
                ]);

            case 'vat-return':
                $period = (string) ($request->input('period') ?: date('Y-m'));
                $outputVat = max(0, (float) $request->input('output', 0));
                $inputVat = max(0, (float) $request->input('input', 0));
                $adjustments = (float) $request->input('adjustments', 0);

                $netVat = $outputVat - $inputVat + $adjustments;
                $statusLabel = $netVat >= 0 ? 'Net Payable to Treasury' : 'Closing Balance / Carry-forward Credit';

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'M U S H A K  9 . 1',
                    'row1_label' => 'Output VAT on sales (' . $period . ')',
                    'row1_value' => '৳ ' . number_format($outputVat, 2),
                    'row2_label' => 'Input VAT rebate & adjustments',
                    'row2_value' => 'Rebate: ৳ ' . number_format($inputVat, 2) . ' | Adj: ৳ ' . number_format($adjustments, 2),
                    'row3_label' => $statusLabel,
                    'row3_value' => '৳ ' . number_format(abs($netVat), 2),
                    'notice' => sprintf(
                        'Period %s: Output VAT ৳ %s − Input VAT ৳ %s + Adjustments ৳ %s = %s: ৳ %s.',
                        $period,
                        number_format($outputVat, 2),
                        number_format($inputVat, 2),
                        number_format($adjustments, 2),
                        $statusLabel,
                        number_format(abs($netVat), 2)
                    ),
                    'message' => 'Mushak 9.1 VAT Return computation complete.',
                ]);

            case 'tax-deadlines':
                $year = (string) ($request->input('year') ?: '2026–2027');
                $category = (string) ($request->input('category') ?: 'Personal tax');
                $due = (string) ($request->input('due') ?: date('Y-m-d', strtotime('+15 days')));
                $notes = (string) ($request->input('notes') ?: 'Statutory compliance milestone');

                $deadlines = $this->cms->get('deadlines', []);
                $newId = collect($deadlines)->max('id') + 1;
                $newDeadline = [
                    'id' => $newId,
                    'year' => $year,
                    'category' => $category,
                    'title' => $category . ' Filing (' . $year . ')',
                    'due_date' => $due,
                    'notes' => $notes,
                    'status' => 'Scheduled',
                ];
                array_unshift($deadlines, $newDeadline);
                $this->cms->put('deadlines', $deadlines);

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'S C H E D U L E D',
                    'row1_label' => 'Obligation & assessment year',
                    'row1_value' => $category . ' (' . $year . ')',
                    'row2_label' => 'Scheduled deadline date',
                    'row2_value' => $due,
                    'row3_label' => 'Compliance note',
                    'row3_value' => $notes,
                    'notice' => 'This compliance deadline has been added to your DhakaFin deadline tracker.',
                    'message' => 'Compliance deadline saved and scheduled.',
                ]);

            case 'tax-rates':
                $type = (string) ($request->input('type') ?: 'TDS');
                $head = (string) ($request->input('head') ?: 'Professional');
                $period = (string) ($request->input('period') ?: '2026–2027');

                $rateDisplay = '10.00%';
                $lawDisplay = 'Income Tax Act 2023';
                if ($type === 'TDS') {
                    if (stripos($head, 'Rent') !== false) {
                        $rateDisplay = ($rates['tds_rent_rate'] ?? 5) . '%';
                        $lawDisplay = 'ITA 2023 Sec 109 (House Property Rent)';
                    } elseif (stripos($head, 'Supply') !== false) {
                        $rateDisplay = ($rates['tds_supplies_rate'] ?? 5) . '%';
                        $lawDisplay = 'ITA 2023 Sec 89 (Payment to Suppliers)';
                    } elseif (stripos($head, 'Salary') !== false) {
                        $rateDisplay = 'Progressive Slabs (0%–25%)';
                        $lawDisplay = 'ITA 2023 Sec 86 (Employment TDS)';
                    } else {
                        $rateDisplay = ($rates['tds_professional_rate'] ?? 10) . '%';
                        $lawDisplay = 'ITA 2023 Sec 90 (Professional & Technical Services)';
                    }
                } elseif ($type === 'VDS') {
                    $rateDisplay = ($rates['vds_professional_rate'] ?? 15) . '% (Supplies: ' . ($rates['vds_supplies_rate'] ?? 7.5) . '%)';
                    $lawDisplay = 'VAT & SD Act 2012 (Mushak 6.6 VDS)';
                } elseif ($type === 'Corporate') {
                    $rateDisplay = 'Private: ' . ($rates['corporate_private_rate'] ?? 27.5) . '% | Listed: ' . ($rates['corporate_listed_rate'] ?? 20) . '%';
                    $lawDisplay = 'Finance Act (' . $period . ')';
                } else {
                    $rateDisplay = 'Threshold ৳ ' . number_format((float) ($rates['personal_general_threshold'] ?? 350000)) . ' (Slabs 5%–25%)';
                    $lawDisplay = 'Income Tax Act 2023 Individual Schedule';
                }

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'V E R I F I E D',
                    'row1_label' => 'Category & payment head',
                    'row1_value' => $type . ' · ' . $head . ' (' . $period . ')',
                    'row2_label' => 'Applicable statutory rate',
                    'row2_value' => $rateDisplay,
                    'row3_label' => 'Statutory reference',
                    'row3_value' => $lawDisplay,
                    'notice' => 'Rates are dynamically managed from the DhakaFin Admin Studio Tax Rate Manager.',
                    'message' => 'Statutory tax rate retrieved.',
                ]);

            default:
                $source = (string) ($request->input('source') ?: 'Personal');
                $base = max(0, (float) $request->input('base', 0));
                $year = (string) ($request->input('year') ?: '2026–2027');

                if (stripos($source, 'Corporate') !== false) {
                    $rate = (float) ($rates['corporate_private_rate'] ?? 27.5);
                    $tax = $base * ($rate / 100.0);
                    $rateLabel = $rate . '% Corporate Rate';
                } else {
                    $threshold = (float) ($rates['personal_general_threshold'] ?? 350000);
                    $taxable = max(0, $base - $threshold);
                    $tax = $taxable * 0.10;
                    $rateLabel = 'Threshold ৳ ' . number_format($threshold) . ' + Slab Rate';
                }

                return response()->json([
                    'status' => 'ok',
                    'stamp' => 'C O M P U T E D',
                    'row1_label' => $source . ' base amount (' . $year . ')',
                    'row1_value' => '৳ ' . number_format($base, 2),
                    'row2_label' => 'Applicable statutory rate',
                    'row2_value' => $rateLabel,
                    'row3_label' => 'Estimated tax liability',
                    'row3_value' => '৳ ' . number_format($tax, 2),
                    'notice' => 'Use the dedicated Personal, Corporate, TDS, VDS, or VAT tabs above for detailed line-item adjustments.',
                    'message' => 'Quick tax estimate complete.',
                ]);
        }
    }

    /**
     * Search across the DhakaFin Legal Library, Circulars, and Tax Rates for AI Tax Law Search.
     */
    public function taxSearch(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $library = $this->cms->get('library', []);
        $rates = $this->cms->get('tax_rates', []);

        if ($query === '') {
            return response()->json([
                'status' => 'ok',
                'title' => 'Start with a tax or VAT question',
                'overline' => 'DHAKAFIN TAX INTELLIGENCE',
                'answer' => 'Enter a keyword or question about the Income Tax Act 2023, VAT & SD Act 2012, TDS/VDS rates, or return filing deadlines.',
                'sources' => array_slice($library, 0, 3),
            ]);
        }

        $qLower = mb_strtolower($query);
        $matched = [];
        foreach ($library as $item) {
            $haystack = mb_strtolower(
                ($item['code'] ?? '') . ' ' .
                ($item['title'] ?? '') . ' ' .
                ($item['lead'] ?? '') . ' ' .
                ($item['overview'] ?? '') . ' ' .
                ($item['quote'] ?? '')
            );
            if (str_contains($haystack, $qLower) ||
                (str_contains($qLower, 'vat') && str_contains($haystack, 'vat')) ||
                (str_contains($qLower, 'tds') && str_contains($haystack, 'deduction')) ||
                (str_contains($qLower, 'withholding') && str_contains($haystack, 'withholding')) ||
                (str_contains($qLower, 'return') && str_contains($haystack, 'return')) ||
                (str_contains($qLower, 'document') && str_contains($haystack, 'return'))
            ) {
                $matched[] = $item;
            }
        }

        if (empty($matched)) {
            $matched = array_slice($library, 0, 3);
        }

        $primary = $matched[0];
        $answer = $primary['overview'] . ' ' . $primary['lead'];
        if (str_contains($qLower, 'rate') || str_contains($qLower, 'tds') || str_contains($qLower, 'vat')) {
            $answer .= sprintf(
                ' Current reference rates: Standard VAT %s%%, TDS on Professional Services %s%%, TDS on Rent %s%%, Private Company Tax %s%%.',
                $rates['vat_standard_rate'] ?? 15,
                $rates['tds_professional_rate'] ?? 10,
                $rates['tds_rent_rate'] ?? 5,
                $rates['corporate_private_rate'] ?? 27.5
            );
        }

        return response()->json([
            'status' => 'ok',
            'overline' => 'STATUTORY RESEARCH MATCH · ' . ($primary['book'] ?? 'BANGLADESH TAX LAW'),
            'title' => $primary['code'] . ' — ' . $primary['title'],
            'answer' => $answer,
            'quote' => $primary['quote'] ?? '',
            'sources' => array_slice($matched, 0, 4),
        ]);
    }
}

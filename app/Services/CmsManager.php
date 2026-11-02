<?php

namespace App\Services;

class CmsManager
{
    protected string $storePath;
    protected ?array $cache = null;

    public function __construct()
    {
        $this->storePath = storage_path('app/cms_store.json');
    }

    /**
     * Load the full CMS data store, initializing with 100% original DhakaFin defaults if not present.
     */
    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        if (file_exists($this->storePath)) {
            $decoded = json_decode(file_get_contents($this->storePath), true);
            if (is_array($decoded)) {
                $this->cache = array_replace_recursive($this->defaults(), $decoded);
                return $this->cache;
            }
        }

        $this->cache = $this->defaults();
        $this->save($this->cache);
        return $this->cache;
    }

    /**
     * Save the full CMS data store to disk.
     */
    public function save(array $data): void
    {
        $this->cache = $data;
        $dir = dirname($this->storePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents(
            $this->storePath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Get a specific section or dot-notated key from the CMS store.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->all(), $key, $default);
    }

    /**
     * Update a specific top-level section in the CMS store.
     */
    public function put(string $section, mixed $value): void
    {
        $data = $this->all();
        $data[$section] = $value;
        $this->save($data);
    }

    /**
     * Default DhakaFin data matching 100% of the original HTML design.
     */
    public function defaults(): array
    {
        return [
            'settings' => [
                'site_name' => 'DhakaFin',
                'tagline' => 'FINANCIAL INTELLIGENCE',
                'email' => 'hello.sohan@outlook.com',
                'phone' => '+8801865555767',
                'address' => 'Gulshan-2, Dhaka 1212, Bangladesh',
                'theme_color' => '#063e32',
                'header_client_label' => 'Client Services',
                'header_talk_label' => 'Let’s Talk',
                'meta_description' => 'Professional accounting, income tax, VAT compliance, financial reporting, audit support and virtual CFO advisory for Bangladesh businesses and individuals.',
                'live_edit_toolbar' => true,
            ],
            'homepage' => [
                'eyebrow' => 'ACCOUNTING, TAX, VAT & ADVISORY IN BANGLADESH',
                'hero_title_line1' => 'Financial clarity,',
                'hero_title_serif' => 'designed around you.',
                'hero_lead' => 'Thoughtful advisory services and modern financial tools—bringing accounting, tax, VAT and decision support into one clear experience.',
                'cta_primary_text' => 'Book a consultation',
                'cta_secondary_text' => 'Explore services',
                'advisory_kicker' => '01 / CORE ADVISORY',
                'advisory_title' => 'Expertise that moves with your business.',
                'tools_kicker' => '02 / DIGITAL SUITE',
                'tools_title' => 'Ten connected tools for tax, VAT and compliance.',
                'cta_banner_title' => 'Ready for a clearer financial conversation?',
                'cta_banner_sub' => 'Connect with our advisory team for accounting, income tax, VAT compliance and strategic financial support.',
            ],
            'footer' => [
                'heading_line1' => 'Make clarity',
                'heading_serif' => 'your advantage.',
                'description' => 'Financial advisory and digital tools, designed to work beautifully together.',
                'home_description' => 'Financial advisory, tax intelligence and thoughtful digital experiences—designed for a clearer tomorrow.',
                'copyright' => '© 2026 DhakaFin · All rights reserved',
                'support_note' => 'For tax assistance and professional advice, contact our team.',
            ],
            'tax_rates' => [
                'personal_general_threshold' => 350000,
                'personal_female_senior_threshold' => 400000,
                'personal_other_threshold' => 475000,
                'personal_rebate_rate' => 15,
                'corporate_private_rate' => 27.5,
                'corporate_listed_rate' => 20.0,
                'corporate_other_rate' => 30.0,
                'tds_professional_rate' => 10.0,
                'tds_rent_rate' => 5.0,
                'tds_supplies_rate' => 5.0,
                'tds_contract_rate' => 7.0,
                'tds_other_rate' => 5.0,
                'vds_professional_rate' => 15.0,
                'vds_rent_rate' => 15.0,
                'vds_supplies_rate' => 7.5,
                'vds_contract_rate' => 10.0,
                'vds_other_rate' => 15.0,
                'vat_standard_rate' => 15.0,
                'vat_reduced_rate' => 7.5,
                'vat_exempt_rate' => 0.0,
            ],
            'articles' => [
                [
                    'id' => 1,
                    'slug' => 'tax-planning-with-a-clearer-lens',
                    'code' => '01',
                    'art_class' => 'art-0',
                    'category' => 'TAX & COMPLIANCE',
                    'title' => 'Tax planning with a clearer lens',
                    'summary' => 'A guide-style content layout designed for practical financial decisions.',
                    'subtitle' => 'DhakaFin editorial layout · Tax & Compliance perspective',
                    'body_p1' => 'Financial information becomes more valuable when it is organised around real business questions. A clear reporting framework can help people see connections and communicate decisions more confidently.',
                    'body_h1' => 'Clarity before complexity',
                    'body_p2' => 'Start with the underlying facts. Separate assumptions from confirmed figures. Build a record of what has been reviewed and what still requires judgement.',
                    'body_h2' => 'Thoughtful next steps',
                    'body_p3' => 'Effective tax planning aligns statutory compliance under the Income Tax Act 2023 with accurate documentation, timely return filing, and verified investment rebates.',
                    'status' => 'Published',
                    'date' => '2026-10-15',
                ],
                [
                    'id' => 2,
                    'slug' => 'a-more-organised-financial-close',
                    'code' => '02',
                    'art_class' => 'art-1',
                    'category' => 'ACCOUNTING',
                    'title' => 'A more organised financial close',
                    'summary' => 'How a careful month-end workflow supports stronger reporting.',
                    'subtitle' => 'DhakaFin editorial layout · Accounting & IFRS perspective',
                    'body_p1' => 'A structured month-end close turns raw bookkeeping into reliable management accounts. Reconciling bank statements, receivables, and withholding ledgers early prevents year-end audit bottlenecks.',
                    'body_h1' => 'Consistent monthly reconciliations',
                    'body_p2' => 'Establish a repeatable checklist for revenue recognition, expense accruals, and payroll schedules aligned with IFRS standards.',
                    'body_h2' => 'Audit-ready documentation',
                    'body_p3' => 'Maintaining indexed vouchers and challan copies throughout the year makes statutory audit and tax assessment markedly smoother.',
                    'status' => 'Published',
                    'date' => '2026-10-18',
                ],
                [
                    'id' => 3,
                    'slug' => 'understanding-vat-records',
                    'code' => '03',
                    'art_class' => 'art-2',
                    'category' => 'VAT',
                    'title' => 'Understanding VAT records',
                    'summary' => 'An introduction to the documents that help keep VAT records consistent.',
                    'subtitle' => 'DhakaFin editorial layout · VAT & Mushak compliance',
                    'body_p1' => 'Under the Value Added Tax and Supplementary Duty Act 2012, input tax credit depends directly on accurate Mushak 6.3 tax invoices and timely Mushak 9.1 monthly returns.',
                    'body_h1' => 'Keeping Mushak 6.1, 6.2 & 6.3 aligned',
                    'body_p2' => 'Every supply and purchase book entry should match the corresponding supplier BIN, invoice date, and VDS certificate.',
                    'body_h2' => 'Monthly return discipline',
                    'body_p3' => 'Filing Mushak 9.1 by the 15th of the following month with reconciled Treasury Challans keeps compliance clean and predictable.',
                    'status' => 'Published',
                    'date' => '2026-10-22',
                ],
                [
                    'id' => 4,
                    'slug' => 'a-founders-financial-dashboard',
                    'code' => '04',
                    'art_class' => 'art-3',
                    'category' => 'BUSINESS',
                    'title' => 'A founder’s financial dashboard',
                    'summary' => 'The information that can make everyday decisions more informed.',
                    'subtitle' => 'DhakaFin editorial layout · Virtual CFO & Advisory',
                    'body_p1' => 'Founders and leadership teams need more than historical financial statements—they need a forward-looking view of cash runway, unit economics, and tax obligations.',
                    'body_h1' => 'Cash flow and working capital visibility',
                    'body_p2' => 'Track net operating cash flow alongside upcoming VAT, TDS, and payroll commitments so working capital is never caught off guard.',
                    'body_h2' => 'Decision-ready metrics',
                    'body_p3' => 'Combine gross margin trends, cost-efficiency reviews, and scenario forecasts into a single leadership pack.',
                    'status' => 'Published',
                    'date' => '2026-10-28',
                ],
            ],
            'deadlines' => [
                [
                    'id' => 1,
                    'year' => '2026–2027',
                    'category' => 'VAT return',
                    'title' => 'Monthly VAT Return (Mushak 9.1)',
                    'due_date' => '2026-11-15',
                    'notes' => '15th of every following month under VAT & SD Act 2012.',
                    'status' => 'Upcoming',
                ],
                [
                    'id' => 2,
                    'year' => '2026–2027',
                    'category' => 'Personal tax',
                    'title' => 'Individual Income Tax Return (Tax Day)',
                    'due_date' => '2026-11-30',
                    'notes' => 'Annual personal income tax return filing deadline for individual taxpayers.',
                    'status' => 'Active',
                ],
                [
                    'id' => 3,
                    'year' => '2026–2027',
                    'category' => 'Withholding',
                    'title' => 'Monthly Withholding Tax (TDS) Return & Challan',
                    'due_date' => '2026-11-25',
                    'notes' => 'Deposit of tax deducted at source and monthly withholding return submission.',
                    'status' => 'Upcoming',
                ],
                [
                    'id' => 4,
                    'year' => '2026–2027',
                    'category' => 'Company tax',
                    'title' => 'Corporate Income Tax Return Filing',
                    'due_date' => '2027-01-15',
                    'notes' => 'Corporate tax return filing with audited financial statements.',
                    'status' => 'Scheduled',
                ],
            ],
            'library' => [
                [
                    'id' => 1,
                    'code' => 'CH 01',
                    'type' => 'Act Chapter',
                    'title' => 'Preliminary',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Definitions, scope, commencement, and foundational concepts of the Income Tax Act 2023 in Bangladesh.',
                    'overview' => 'Chapter 1 establishes the statutory definitions including assessment year, income year, resident status, assessee, company, and principal officer used throughout the Act.',
                    'quote' => 'Every person whose total income during the income year exceeds the maximum amount not chargeable to tax shall file a return of income under the provisions of this Act.',
                    'navigation_note' => 'Cross-reference statutory definitions in Chapter 1 before determining residency, source of income, or withholding obligations.',
                    'status' => 'Published',
                ],
                [
                    'id' => 2,
                    'code' => 'CH 02',
                    'type' => 'Act Chapter',
                    'title' => 'Income and taxable persons',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Classification of heads of income, scope of total income, and residential status rules for individuals and companies.',
                    'overview' => 'Income is classified under Employment, Rent, Agriculture, Business, Capital Gains, Financial Assets, and Other Sources. Resident taxpayers are assessed on worldwide income, while non-residents are assessed on Bangladesh-sourced income.',
                    'quote' => 'Total income of an assessee for any income year includes all income derived from whatever source received, accrued, or arising in Bangladesh.',
                    'navigation_note' => 'Review each head of income separately to apply head-specific allowable deductions and exemptions.',
                    'status' => 'Published',
                ],
                [
                    'id' => 3,
                    'code' => 'CH 03',
                    'type' => 'Act Chapter',
                    'title' => 'Computation of total income',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Allowable business deductions, inadmissible expenses, depreciation allowances, and investment tax rebate rules.',
                    'overview' => 'Covers computation of taxable income across heads, Sixth Schedule exemptions and investment rebates (DPS, savings certificates, life insurance, listed securities), and corporate expense limits.',
                    'quote' => 'An eligible individual taxpayer may claim an investment tax credit calculated at 15% of eligible investments, subject to statutory caps on total taxable income.',
                    'navigation_note' => 'Use the Personal and Corporate Tax Calculators to model taxable income and investment rebate scenarios.',
                    'status' => 'Published',
                ],
                [
                    'id' => 4,
                    'code' => 'CH 04',
                    'type' => 'Act Chapter',
                    'title' => 'Tax deduction and collection',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Withholding tax (TDS) obligations on salaries, contractor payments, professional fees, rent, imports, and service payments.',
                    'overview' => 'Withholding entities must deduct tax at source at prescribed rates when making specified payments and deposit the deducted amount to the Government Treasury within the statutory deadline.',
                    'quote' => 'Failure to deduct or deposit tax at source as required under Part VII may result in disallowance of the underlying expenditure and statutory penalty interest.',
                    'navigation_note' => 'Use the TDS Calculator and Tax Rate Reference to check applicable withholding rates.',
                    'status' => 'Published',
                ],
                [
                    'id' => 5,
                    'code' => 'CH 05',
                    'type' => 'Act Chapter',
                    'title' => 'Return filing and assessment',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Universal self-assessment, return filing deadlines (Tax Day), Proof of Submission of Return (PSR), and audit selection.',
                    'overview' => 'Explains mandatory return filing categories (PSR requirements for 40+ services), wealth statement (IT-10B) thresholds, and self-assessment procedures.',
                    'quote' => 'A return filed under universal self-assessment accompanied by required statements and tax payment shall be processed and acknowledged upon submission.',
                    'navigation_note' => 'Check the Tax Deadline Tracker for upcoming return filing milestones.',
                    'status' => 'Published',
                ],
                [
                    'id' => 6,
                    'code' => 'CH 06',
                    'type' => 'Act Chapter',
                    'title' => 'Appeals, offences and procedure',
                    'subtitle' => 'Legislation · Reader',
                    'book' => 'BOOK 01 / INCOME TAX ACT 2023',
                    'lead' => 'Appellate Commissioner, Taxes Appellate Tribunal, High Court Division reference, Alternative Dispute Resolution (ADR), and penalties.',
                    'overview' => 'Outlines timelines and documentation required to file an appeal against an assessment order or apply for Alternative Dispute Resolution (ADR).',
                    'quote' => 'An aggrieved taxpayer may prefer an appeal before the Commissioner (Appeals) within the statutory period from service of the assessment notice.',
                    'navigation_note' => 'Contact DhakaFin Corporate Tax Advisory for representation in assessments, appeals, and ADR.',
                    'status' => 'Published',
                ],
                [
                    'id' => 7,
                    'code' => 'SRO 01',
                    'type' => 'Circular / SRO',
                    'title' => 'Income Tax circulars & Finance Act updates',
                    'subtitle' => 'Curated index · Regulatory reference',
                    'book' => 'NBR CIRCULARS & FINANCE ACT',
                    'lead' => 'Summary of recent Finance Act changes, tax slab adjustments, and NBR clarifying circulars (Paripatra).',
                    'overview' => 'Summarizes key rate adjustments for individual taxpayers, corporate tax compliance conditions (cashless transaction thresholds), and withholding tax updates.',
                    'quote' => 'Corporate tax rate concessions are conditional upon compliance with prescribed banking-channel thresholds for receipts and expenses.',
                    'navigation_note' => 'Compare with Tax Rates Reference for current assessment year slabs.',
                    'status' => 'Published',
                ],
                [
                    'id' => 8,
                    'code' => 'VAT 01',
                    'type' => 'Circular / SRO',
                    'title' => 'VAT & Supplementary Duty notices (Mushak 6.3 & 9.1)',
                    'subtitle' => 'Structured reference view',
                    'book' => 'VAT & SD ACT 2012 / RULES 2016',
                    'lead' => 'Guidelines on VAT deduction at source (VDS), input tax credit conditions, Mushak 6.3 invoicing, and Mushak 9.1 monthly returns.',
                    'overview' => 'Input tax credit requires a valid Mushak 6.3 invoice, disclosure in the purchase register (Mushak 6.1), and payment through banking channels for supplies above the statutory threshold.',
                    'quote' => 'Every registered person shall issue a tax invoice in Form Mushak 6.3 for each taxable supply and submit a monthly return in Form Mushak 9.1 by the 15th of the following month.',
                    'navigation_note' => 'Use the Mushak 6.3 Creator, VDS Calculator, and VAT Return tools in Digital Tools.',
                    'status' => 'Published',
                ],
            ],
            'team' => [
                [
                    'id' => 1,
                    'code' => '01',
                    'kicker' => 'TAX ADVISORY',
                    'name' => 'Tax advisory specialist',
                    'summary' => 'Corporate & personal income tax planning, return preparation, and NBR representation.',
                    'bio' => 'Specialises in corporate tax assessments, withholding tax (TDS) compliance, international tax treaties, and strategic tax planning for Bangladesh businesses and individuals.',
                    'tags' => ['Income Tax Act 2023', 'Corporate Tax', 'Tax Planning'],
                ],
                [
                    'id' => 2,
                    'code' => '02',
                    'kicker' => 'ASSURANCE',
                    'name' => 'Audit & assurance professional',
                    'summary' => 'IFRS financial reporting, statutory audit readiness, and internal control reviews.',
                    'bio' => 'Leads IFRS financial statement preparation, internal audit engagements, risk-informed control frameworks, and audit readiness support for corporate and NGO clients.',
                    'tags' => ['IFRS Reporting', 'Internal Audit', 'Assurance'],
                ],
                [
                    'id' => 3,
                    'code' => '03',
                    'kicker' => 'ADVISORY',
                    'name' => 'Financial advisory professional',
                    'summary' => 'Virtual CFO leadership, VAT compliance, financial modelling, and business advisory.',
                    'bio' => 'Advises founders, boards, and finance teams on cash-flow forecasting, cost-efficiency reviews, VAT & Mushak workflows, and strategic growth decisions.',
                    'tags' => ['Virtual CFO', 'VAT & Mushak', 'Business Advisory'],
                ],
            ],
            'enquiries' => [
                [
                    'id' => 1,
                    'type' => 'consultation',
                    'name' => 'Tanvir Ahmed',
                    'email' => 'tanvir@example.com',
                    'organisation' => 'Apex Tech Ltd.',
                    'service' => 'Corporate tax',
                    'preferred_date' => '2026-11-10',
                    'message' => 'We would like to schedule a consultation regarding our FY 2026-2027 corporate tax return and monthly TDS/VDS reconciliation.',
                    'status' => 'Unread',
                    'created_at' => '2026-11-01 14:20',
                ],
                [
                    'id' => 2,
                    'type' => 'contact',
                    'name' => 'Nusrat Jahan',
                    'email' => 'nusrat@example.com',
                    'organisation' => 'GreenLeaf SME',
                    'service' => 'VAT & Mushak',
                    'preferred_date' => '',
                    'message' => 'Looking for monthly Mushak 9.1 return preparation and Mushak 6.3 invoicing support for our retail operations in Dhaka.',
                    'status' => 'Read',
                    'created_at' => '2026-10-29 11:05',
                ],
            ],
            'invoices' => [
                [
                    'id' => 1,
                    'invoice_no' => 'M63-2026-001',
                    'date' => '2026-10-25',
                    'supplier' => 'DhakaFin Advisory Ltd. (BIN: 001234567-0101)',
                    'buyer' => ' Horizon Manufacturing Ltd.',
                    'item' => 'Monthly Financial Reporting & VAT Compliance Retainer',
                    'qty' => 1,
                    'price' => 85000,
                    'vat_rate' => 15,
                    'vat_amount' => 12750,
                    'total' => 97750,
                    'status' => 'Issued',
                ],
                [
                    'id' => 2,
                    'invoice_no' => 'M63-2026-002',
                    'date' => '2026-11-01',
                    'supplier' => 'DhakaFin Advisory Ltd. (BIN: 001234567-0101)',
                    'buyer' => 'Delta Logistics Bangladesh',
                    'item' => 'Corporate Tax Assessment & Withholding Review',
                    'qty' => 1,
                    'price' => 120000,
                    'vat_rate' => 15,
                    'vat_amount' => 18000,
                    'total' => 138000,
                    'status' => 'Draft',
                ],
            ],
            'documents' => [
                [
                    'id' => 1,
                    'title' => 'FY 2026-27 Trade License & TIN Certificate.pdf',
                    'category' => 'Tax & Corporate',
                    'size' => '1.4 MB',
                    'uploaded_at' => '2026-10-20',
                    'status' => 'Verified',
                ],
                [
                    'id' => 2,
                    'title' => 'October 2026 Bank Reconciliation & Ledger.xlsx',
                    'category' => 'Accounting',
                    'size' => '840 KB',
                    'uploaded_at' => '2026-10-30',
                    'status' => 'In Review',
                ],
                [
                    'id' => 3,
                    'title' => 'Mushak 9.1 Monthly VAT Return Copy - Oct 2026.pdf',
                    'category' => 'VAT Compliance',
                    'size' => '2.1 MB',
                    'uploaded_at' => '2026-11-01',
                    'status' => 'Verified',
                ],
            ],
            'submissions' => [
                [
                    'id' => 1,
                    'reference' => 'SUB-2026-101',
                    'title' => 'Monthly VAT Return (Mushak 9.1) — October 2026',
                    'type' => 'VAT Return',
                    'period' => '2026-10',
                    'amount' => '৳ 1,45,000',
                    'status' => 'Submitted',
                    'updated_at' => '2026-11-01',
                ],
                [
                    'id' => 2,
                    'reference' => 'SUB-2026-102',
                    'title' => 'Withholding Tax (TDS) Monthly Statement',
                    'type' => 'TDS Return',
                    'period' => '2026-10',
                    'amount' => '৳ 62,500',
                    'status' => 'In Review',
                    'updated_at' => '2026-11-02',
                ],
                [
                    'id' => 3,
                    'reference' => 'SUB-2026-103',
                    'title' => 'Annual Corporate Income Tax Return (AY 2026–2027)',
                    'type' => 'Income Tax',
                    'period' => '2026–2027',
                    'amount' => '৳ 4,80,000',
                    'status' => 'Prepared',
                    'updated_at' => '2026-11-02',
                ],
            ],
            'notifications' => [
                [
                    'id' => 1,
                    'tag' => 'DEADLINE',
                    'title' => 'Mushak 9.1 VAT Return due on 15 November 2026',
                    'body' => 'Ensure all October Mushak 6.3 purchase and sales invoices and Treasury Challans are reconciled before filing.',
                    'read' => false,
                ],
                [
                    'id' => 2,
                    'tag' => 'TAX DAY',
                    'title' => 'Personal Income Tax Return (Tax Day — 30 November)',
                    'body' => 'Upload bank statements, salary certificates, and investment proofs (DPS, Sanchayapatra, insurance) to the Document Vault.',
                    'read' => false,
                ],
                [
                    'id' => 3,
                    'tag' => 'ADVISORY',
                    'title' => 'Q3 Management Accounts & IFRS Review Ready',
                    'body' => 'Your quarterly financial reporting pack has been reviewed by the DhakaFin advisory team.',
                    'read' => true,
                ],
            ],
            'admin_credentials' => [
                'email' => 'admin@dhakafin.com',
                'password' => 'dhakafin2026',
                'name' => 'DhakaFin Administrator',
                'client_name' => 'DhakaFin Client',
                'client_email' => 'client@dhakafin.com',
                'client_org' => 'Apex Enterprise Ltd.',
            ],
            'page_overrides' => [],
        ];
    }

    /**
     * Apply dynamic CMS settings & page overrides onto rendered HTML without altering design classes.
     */
    public function applyOverrides(string $html, string $slug = 'home'): string
    {
        $data = $this->all();
        $settings = $data['settings'] ?? [];
        $footer = $data['footer'] ?? [];

        // Replace global contact email & phone if changed by admin
        if (!empty($settings['email']) && $settings['email'] !== 'hello.sohan@outlook.com') {
            $html = str_replace('hello.sohan@outlook.com', e($settings['email']), $html);
        }
        if (!empty($settings['phone']) && $settings['phone'] !== '+8801865555767') {
            $html = str_replace('+8801865555767', e($settings['phone']), $html);
        }

        // Replace footer texts if customized by admin
        if (!empty($footer['copyright']) && $footer['copyright'] !== '© 2026 DhakaFin · All rights reserved') {
            $html = str_replace('© 2026 DhakaFin · All rights reserved', e($footer['copyright']), $html);
        }
        if (!empty($footer['support_note']) && $footer['support_note'] !== 'For tax assistance and professional advice, contact our team.') {
            $html = str_replace('For tax assistance and professional advice, contact our team.', e($footer['support_note']), $html);
        }
        if (!empty($footer['description']) && $footer['description'] !== 'Financial advisory and digital tools, designed to work beautifully together.') {
            $html = str_replace('Financial advisory and digital tools, designed to work beautifully together.', e($footer['description']), $html);
        }

        // Apply page-specific overrides (hero heading, hero lead, custom text pairs)
        $pageOvr = $data['page_overrides'][$slug] ?? null;
        if (is_array($pageOvr)) {
            if (!empty($pageOvr['replacements']) && is_array($pageOvr['replacements'])) {
                foreach ($pageOvr['replacements'] as $pair) {
                    if (!empty($pair['find']) && isset($pair['replace']) && $pair['find'] !== $pair['replace']) {
                        $html = str_replace($pair['find'], $pair['replace'], $html);
                    }
                }
            }
            if (!empty($pageOvr['hero_title'])) {
                $html = preg_replace('/(<h1[^>]*>).*?(<\/h1>)/is', '$1' . $pageOvr['hero_title'] . '$2', $html, 1);
            }
            if (!empty($pageOvr['hero_lead'])) {
                $html = preg_replace('/(<div class="v2-hero-copy">.*?<h1[^>]*>.*?<\/h1>\s*<p[^>]*>).*?(<\/p>)/is', '$1' . e($pageOvr['hero_lead']) . '$2', $html, 1);
            }
        }

        return $html;
    }
}

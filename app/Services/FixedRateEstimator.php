<?php

namespace App\Services;

use InvalidArgumentException;

final class FixedRateEstimator
{
    /**
     * Fixed percentage arithmetic only, not statutory applicability advice.
     * Supports VAT-exclusive and VAT-inclusive amounts with integer paisa.
     *
     * A rate of 100 basis points represents 1%. No floating-point money math.
     */
    public function calculate(
        string $amountBdt,
        int $rateBasisPoints,
        string $amountMode = 'exclusive'
    ): array {
        if (!preg_match('/\A(0|[1-9][0-9]{0,11})(?:\.([0-9]{1,2}))?\z/D', $amountBdt, $match)) {
            throw new InvalidArgumentException('Amount must be non-negative BDT with up to two decimals.');
        }

        if ($rateBasisPoints < 0 || $rateBasisPoints > 10000) {
            throw new InvalidArgumentException('Rate must be between 0 and 100 percent.');
        }

        if (!in_array($amountMode, ['exclusive', 'inclusive'], true)) {
            throw new InvalidArgumentException('Amount mode must be exclusive or inclusive.');
        }

        $fraction = str_pad($match[2] ?? '', 2, '0');
        $paisa = ((int) $match[1] * 100) + (int) $fraction;

        if ($amountMode === 'inclusive') {
            // Tax is included in the entered total. 10,000 bps = 100%.
            // Max intermediate value: 10^14 paisa × 10^4 < signed int64.
            $denominator = 10000 + $rateBasisPoints;
            $tax = intdiv(
                ($paisa * $rateBasisPoints) + intdiv($denominator, 2),
                $denominator
            );
            $net = $paisa - $tax;
        } else {
            // Avoid large multiplication even on a 64-bit PHP host.
            $major = intdiv($paisa, 10000) * $rateBasisPoints;
            $minor = intdiv(($paisa % 10000) * $rateBasisPoints + 5000, 10000);
            $tax = $major + $minor;
            $net = $paisa;
        }

        return [
            'base_amount_bdt' => $this->format($paisa),
            'rate_percent' => sprintf('%d.%02d', intdiv($rateBasisPoints, 100), $rateBasisPoints % 100),
            'estimated_amount_bdt' => $this->format($tax),
            'net_amount_bdt' => $this->format($net),
            'amount_mode' => $amountMode,
        ];
    }

    private function format(int $paisa): string
    {
        return sprintf('%d.%02d', intdiv($paisa, 100), $paisa % 100);
    }
}

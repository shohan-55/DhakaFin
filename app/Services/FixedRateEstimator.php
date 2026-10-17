<?php

namespace App\Services;

use InvalidArgumentException;

final class FixedRateEstimator
{
    /**
     * Simple exclusive-rate arithmetic only. This does not implement
     * statutory exemptions, thresholds, gross-up rules or other tax law.
     *
     * Amount must be BDT with up to two decimals. 100 basis points = 1%.
     */
    public function calculate(string $amountBdt, int $rateBasisPoints): array
    {
        if (!preg_match('/\A(0|[1-9][0-9]{0,11})(?:\.([0-9]{1,2}))?\z/D', $amountBdt, $match)) {
            throw new InvalidArgumentException('Amount must be a non-negative BDT sum with up to two decimals.');
        }

        if ($rateBasisPoints < 0 || $rateBasisPoints > 10000) {
            throw new InvalidArgumentException('Rate must be between 0 and 100 percent.');
        }

        $fraction = str_pad($match[2] ?? '', 2, '0');
        $paisa = ((int) $match[1] * 100) + (int) $fraction;

        // Split the calculation to avoid multiplying very large sums.
        $major = intdiv($paisa, 10000) * $rateBasisPoints;
        $minor = intdiv(($paisa % 10000) * $rateBasisPoints + 5000, 10000);
        $withholdingPaisa = $major + $minor;

        return [
            'base_amount_bdt' => $this->format($paisa),
            'rate_percent' => sprintf('%d.%02d', intdiv($rateBasisPoints, 100), $rateBasisPoints % 100),
            'estimated_amount_bdt' => $this->format($withholdingPaisa),
        ];
    }

    private function format(int $paisa): string
    {
        return sprintf('%d.%02d', intdiv($paisa, 100), $paisa % 100);
    }
}

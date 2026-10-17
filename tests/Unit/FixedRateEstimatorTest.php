<?php

namespace Tests\Unit;

use App\Services\FixedRateEstimator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FixedRateEstimatorTest extends TestCase
{
    public function test_bdt_calculations_use_integer_paisa_without_floating_point_drift(): void
    {
        $calculator = new FixedRateEstimator();

        $this->assertSame([
            'base_amount_bdt' => '1000.00',
            'rate_percent' => '5.00',
            'estimated_amount_bdt' => '50.00',
            'net_amount_bdt' => '1000.00',
            'amount_mode' => 'exclusive',
        ], $calculator->calculate('1000.00', 500));

        $this->assertSame('92.59', $calculator->calculate('1234.56', 750)['estimated_amount_bdt']);
        $this->assertSame('0.01', $calculator->calculate('0.01', 5000)['estimated_amount_bdt']);
        $this->assertSame('155000000.00', $calculator->calculate('1000000000.00', 1550)['estimated_amount_bdt']);
    }

    public function test_vat_inclusive_arithmetic_uses_integer_paisa_and_rounding(): void
    {
        $calculator = new FixedRateEstimator();

        $this->assertSame([
            'base_amount_bdt' => '1150.00',
            'rate_percent' => '15.00',
            'estimated_amount_bdt' => '150.00',
            'net_amount_bdt' => '1000.00',
            'amount_mode' => 'inclusive',
        ], $calculator->calculate('1150.00', 1500, 'inclusive'));

        $this->assertSame('0.01', $calculator->calculate('0.03', 5000, 'inclusive')['estimated_amount_bdt']);
        $this->assertSame('0.00', $calculator->calculate('0.01', 1500, 'inclusive')['estimated_amount_bdt']);
        $this->assertSame('0.00', $calculator->calculate('0.00', 1500, 'inclusive')['estimated_amount_bdt']);
    }

    public function test_invalid_bdt_input_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new FixedRateEstimator())->calculate('123.456', 500);
    }

    public function test_rate_over_100_percent_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new FixedRateEstimator())->calculate('100.00', 10001);
    }

    public function test_unknown_amount_mode_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new FixedRateEstimator())->calculate('100.00', 1500, 'wrong');
    }
}

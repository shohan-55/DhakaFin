<?php

namespace Tests\Feature;

use App\Models\TaxRateRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxRateRuleApiTest extends TestCase
{
    use RefreshDatabase;

    private function sample(): array
    {
        return [
            'code' => 'test-rate-500',
            'tax_type' => 'tds',
            'category' => 'Unit test only',
            'description' => 'Illustrative rate, not real statutory guidance.',
            'rate_basis_points' => 500,
            'effective_from' => today('Asia/Dhaka')->subDay()->format('Y-m-d'),
            'effective_until' => today('Asia/Dhaka')->addMonth()->format('Y-m-d'),
            'reference_code' => 'UNIT-TEST-ONLY',
            'provision_reference' => 'Synthetic test clause',
            'source_url' => 'https://nbr.gov.bd',
            'status' => 'draft',
        ];
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();
        return $user;
    }

    public function test_rate_editor_requires_admin_access(): void
    {
        $this->get('/admin/tax-rates')->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create())
            ->get('/admin/tax-rates')->assertForbidden();
    }

    public function test_admin_can_store_and_review_rate_rule(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.tax-rates.store'), $this->sample())
            ->assertRedirect(route('admin.tax-rates.index'));

        $rule = TaxRateRule::firstOrFail();
        $this->assertNull($rule->verified_at);

        $this->put(route('admin.tax-rates.update', $rule), [
            ...$this->sample(), 'status' => 'verified',
        ])->assertRedirect(route('admin.tax-rates.index'));

        $this->assertNotNull($rule->fresh()->verified_at);

        $this->post(route('admin.tax-rates.store'), [
            ...$this->sample(), 'code' => 'invalid-rate', 'rate_basis_points' => 10001,
        ])->assertSessionHasErrors('rate_basis_points');
    }

    public function test_public_rates_and_estimates_are_closed_before_release(): void
    {
        config()->set('dhakafin.features.tax_rates', false);
        config()->set('dhakafin.features.tds_calculator', false);
        $this->getJson('/api/v1/tax/rates')->assertStatus(503);
        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds', 'rate_rule_id' => 1, 'amount_bdt' => '1000.00',
        ])->assertStatus(503);
    }

    public function test_only_verified_effective_rate_can_be_used_for_fixed_arithmetic(): void
    {
        config()->set('dhakafin.features.tax_rates', true);
        config()->set('dhakafin.features.tds_calculator', true);

        TaxRateRule::create($this->sample());
        $published = TaxRateRule::create([
            ...$this->sample(),
            'code' => 'verified-test-rate',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->getJson('/api/v1/tax/rates')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'verified-test-rate');

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds',
            'rate_rule_id' => $published->id,
            'amount_bdt' => '1000.00',
        ])->assertOk()
            ->assertJsonPath('calculation.estimated_amount_bdt', '50.00')
            ->assertJsonPath('citation.reference_code', 'UNIT-TEST-ONLY');

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds',
            'rate_rule_id' => 1,
            'amount_bdt' => '1000.00',
        ])->assertNotFound();
    }
}

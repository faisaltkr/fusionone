<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trType = $this->faker->randomElement(['Purchase', 'Purchase Return']);
        $prefix = $trType === 'Purchase' ? 'PUR-' : 'PRN-';

        $gross = $this->faker->randomFloat(2, 100, 10000);
        $discount = $this->faker->randomFloat(2, 0, $gross * 0.2);
        $net = $gross - $discount;
        $vat = $net * 0.15;
        $grand = $net + $vat;

        return [
            'company_id' => User::where('id','!=',1)->inRandomOrder()->value('id'),
            'entry_no' => $this->faker->unique()->numberBetween(1, 100000),
            'purchase_purchase_return_no' => $prefix . $this->faker->unique()->numerify('#####'),
            'transaction_type' => $trType,
            'supplier_name' => $this->faker->company,
            'supplier_id' => $this->faker->numberBetween(1, 500),
            'mode_of_transaction' => $this->faker->randomElement(['CA','CR','BA']),
            'gross_amount' => $gross,
            'discount' => $discount,
            'net_amount' => $net,
            'vat_amount' => $vat,
            'grand_amount' => $grand,
            'tr_date' => $this->faker->date()
        ];
    }
}

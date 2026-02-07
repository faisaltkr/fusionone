<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            //'company_name'=> 'FusionOne',
            'email' => 'admin@fusionone.in',
            'password' => bcrypt('password'),
            //'company_reg_id' => Str::uuid(),
            //'address' => 'Super User address',
            //'place' => 'Super User Place',
            //'phone' => '9664515421',
            'user_type' => 'super_admin',
            'status' => true,
        ]);

       /* User::factory()->create([
            'name' => 'Faisal',
            'company_name'=> 'ABC Company',
            'email' => 'faizel@fusionone.in',
            'password' => bcrypt('password'),
            'company_reg_id' => Str::uuid(),
            'address' => 'Admin User address',
            'place' => 'Admin User Place',
            'phone' => '9645151518',
            'user_type' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Abdullah',
            'company_name' => 'ExOne Technologies',
            'email' => 'abdulla@exonetech.com',
            'password' => bcrypt('password'),
            'company_reg_id' => Str::uuid(),
            'address' => 'Admin User address',
            'place' => 'Admin User Place',
            'phone' => '9567232524',
            'user_type' => 'admin',
        ]);*/

        // $this->call([
        //     SaleSeeder::class,
        //     PurchaseSeeder::class,
        // ]);
    }
}

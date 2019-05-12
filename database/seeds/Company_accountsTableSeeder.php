<?php

use Illuminate\Database\Seeder;
use App\Company_account;
use Illuminate\Support\Facades\Hash;
class Company_accountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Company_account::create([
            'username'=>'PauloCompany',
            'password'=>Hash::make('12345678'),
            'number'=>'0912838783'
        ]);
        $companies = factory(Company_account::class, 40)->create();
    }
}

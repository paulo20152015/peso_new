<?php

use Illuminate\Database\Seeder;
use App\Company_address;
class Company_addressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $company_address = factory(Company_address::class,40)->create();
    }
}

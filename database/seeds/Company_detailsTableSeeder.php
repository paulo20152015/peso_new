<?php

use Illuminate\Database\Seeder;
use App\Company_account;
use App\Company_detail;
class Company_detailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $company_details = factory(Company_detail::class, 40)->create();
    }
}

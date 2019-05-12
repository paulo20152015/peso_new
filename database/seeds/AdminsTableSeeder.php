<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Admin;
class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Admin::create([
            'username'=>'admin',
            'password'=>Hash::make('12345678'),
            'is_archive'=>false,
            'level'=>2
        ]);
        $user = factory(Admin::class,20)->create();
    }
}

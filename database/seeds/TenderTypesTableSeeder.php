<?php

use Illuminate\Database\Seeder;

class TenderTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!\App\Models\TenderType::where('name', 'government')->count()) {
            DB::table('tender_types')->insert([
                [
                    'name' => 'government',
                ],
            ]);
        }

        if(!\App\Models\TenderType::where('name', 'smarttender')->count()) {
            DB::table('tender_types')->insert([
                [
                    'name' => 'smarttender',
                ],
            ]);
        }

        if(!\App\Models\TenderType::where('name', 'alladin')->count()) {
            DB::table('tender_types')->insert([
                [
                    'name' => 'alladin',
                ],
            ]);
        }

        $admin = \App\User::where('name', 'admin')->first();

        $admin->tenderTypes()->sync(\App\Models\TenderType::pluck('id'));

        $lev = \App\User::where('name', 'Lev')->first();
        $lev->tenderTypes()->attach(\App\Models\TenderType::where('name', 'alladin')->first()->id);

    }
}

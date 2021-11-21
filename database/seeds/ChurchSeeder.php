<?php

use App\Models\Church;
use Illuminate\Database\Seeder;

class ChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Church::create([
            'name' => 'StGeorge Sporting',
            'arabic_name' => 'كنيسة مارجرجس سبورتنج',
        ]);
    }
}

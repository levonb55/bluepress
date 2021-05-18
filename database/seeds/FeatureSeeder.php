<?php

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feature = new Feature([
            'product_id' => 1,
            'title' => 'Screen Size',
            'description' => '15.6 Inches',
        ]);

        $feature->save();

        $feature = new Feature([
            'product_id' => 1,
            'title' => 'Computer Memory Size',
            'description' => '4 GB',
        ]);

        $feature->save();

        $feature = new Feature([
            'product_id' => 3,
            'title' => 'Color',
            'description' => 'Blue',
        ]);

        $feature->save();
    }
}

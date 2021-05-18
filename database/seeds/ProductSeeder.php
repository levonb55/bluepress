<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product([
            'image_path' => 'https://www.notebookcheck.net/uploads/tx_nbc2/4zu3_Asus_Vivobook_14_X412FJ.jpg',
            'title' => 'Laptop Asus Vivobook',
            'description' => 'Super cool',
            'price' => 10,
            'is_physical' => 1,
            'stock' => 17
        ]);
        $product->save();

        $product = new Product([
            'image_path' => 'https://image.ebooks.com/previews/210/210173/210173281/210173281.jpg',
            'title' => 'Head First Design Patterns',
            'description' => 'Book in pdf format',
            'price' => 12,
            'is_physical' => 0,
        ]);
        $product->save();

        $product = new Product([
            'image_path' => 'https://assets.swappie.com/swappie-product-iphone-12-blue.png',
            'title' => 'Iphone',
            'description' => '12th generation',
            'price' => 20,
            'is_physical' => 1,
            'stock' => 22
        ]);
        $product->save();
    }
}

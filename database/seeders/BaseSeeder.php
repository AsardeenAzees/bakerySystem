<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaseSeeder extends Seeder
{
    public function run(): void
    {
        // roles: admin, chef, delivery, customer
        User::factory()->create(['name'=>'Admin','email'=>'admin@bakery.test','role'=>'admin']);
        User::factory()->create(['name'=>'Chef','email'=>'chef@bakery.test','role'=>'chef']);
        User::factory()->create(['name'=>'Deliver Guy','email'=>'delivery@bakery.test','role'=>'delivery']);
        User::factory()->create(['name'=>'Customer','email'=>'customer@bakery.test','role'=>'customer']);

        $cats = ['Cakes','Breads','Pastries','Cookies'];
        foreach ($cats as $c) {
            $cat = Category::create(['name'=>$c,'slug'=>Str::slug($c)]);
            for ($i=1; $i<=5; $i++) {
                $p = Product::create([
                    'category_id'=>$cat->id,
                    'name'=>"$c Item $i",
                    'slug'=>Str::slug("$c Item $i").'-'.Str::random(5),
                    'price'=>rand(300,1200),
                    'description'=>'Freshly baked item.',
                    'is_active'=>true,
                ]);
                Inventory::create(['product_id'=>$p->id,'quantity'=>rand(5,25),'reorder_level'=>5]);
            }
        }
    }
}

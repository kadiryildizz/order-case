<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
        Campaign::create([
            "title"=> "Sepette %10 indirim",
            "status"=> "ACTIVE",
            "discount"=> 10,
            "discount_type"=> "PERCENT",
            "product_count"=> 1,
            "count_type"=> "DIFF",
            "count_apply"=> "CHEAPEST",
            "minimum_cart_total"=> 1000,
            "main_id"=> null,
            "main_type"=> "PRODUCT",
            "criterions"=> "[\"minimum_cart_total\"]",
            "active_since"=> "2025-04-01 12:00:00",
            "active_till"=> "2026-12-31 12:00:00",
        ]);
        // 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
        $categoryId = Category::inRandomOrder()->first()->id;
        Campaign::create([
                "title"=> $categoryId." ID'li kategoriden Aynı üründen 6 adet satın alana birtanesi ücretsiz.",
                "status"=> "ACTIVE",
                "discount"=> 1,
                "discount_type"=> "GIFT",
                "product_count"=> 6,
                "count_type"=> "SAME",
                "count_apply"=> "CHEAPEST",
                "minimum_cart_total"=> 0,
                "main_id"=> $categoryId,
                "main_type"=> "CATEGORY",
                "criterions"=> "[\"combine\"]",
                "active_since"=> "2025-04-01 12:00:00",
                "active_till"=> "2026-12-31 12:00:00",
        ]);
        //1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
        $categoryId = Category::inRandomOrder()->first()->id;
        Campaign::create([
            "title"=> $categoryId." ID'li kategoriden iki veya daha fazla ürün var ise, en ucuz üründe %20 indirim.",
            "status"=> "ACTIVE",
            "discount"=> 20,
            "discount_type"=> "PERCENT",
            "product_count"=> 2,
            "count_type"=> "DIFF",
            "count_apply"=> "CHEAPEST",
            "minimum_cart_total"=> 0,
            "main_id"=> $categoryId,
            "main_type"=> "CATEGORY",
            "criterions"=> "[\"combine\"]",
            "active_since"=> "2025-04-01 12:00:00",
            "active_till"=> "2026-12-31 12:00:00",
    ]);
    }
}

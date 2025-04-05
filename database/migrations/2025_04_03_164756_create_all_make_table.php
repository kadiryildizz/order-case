<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CategoryStatus;
use App\Enums\ProductStatus;
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use App\Enums\CampaignDiscountTypes;
use App\Enums\CampaignCountTypes;
use App\Enums\CampaignCountApplies;
use App\Enums\CampaignCriterions;
use App\Enums\CampaignStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 180);
            $table->string('slug', 180)->unique();
            $table->tinyInteger('status')->default(CategoryStatus::ACTIVE)->index()->comment(implode(',', CategoryStatus::values()));
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 180);
            $table->string('slug', 180)->unique();
            $table->bigInteger('category_id')->unsigned();
            $table->float('price');
            $table->integer('stock');
            $table->tinyInteger('status')->default(ProductStatus::ACTIVE)->index()->comment(implode(',', ProductStatus::values()));
            $table->timestamps();
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 180);
            $table->tinyInteger('status')->default(CustomerStatus::ACTIVE)->index()->comment(implode(',', CustomerStatus::values()));
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('campaign_id')->nullable()->unsigned();
            $table->tinyInteger('status')->default(OrderStatus::DRAFT)->index()->comment(implode(',', OrderStatus::values()));
            $table->float('total');
            $table->timestamps();
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->integer('quantity');
            $table->float('unit_price');
            $table->float('discount')->default(0);
            $table->float('total_price');
            $table->timestamps();
        });
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->float('discount')->default(0);
            $table->enum('discount_type', CampaignDiscountTypes::values());
            $table->integer('product_count')->default(0);
            $table->enum('count_type', CampaignCountTypes::values())->default(CampaignCountTypes::DIFF);
            $table->enum('count_apply', CampaignCountApplies::values())->default(CampaignCountApplies::CHEAPEST);
            $table->integer('minimum_cart_total')->default(0);
            $table->enum('main_type', CampaignCriterions::values())->comment(implode(',', CampaignCriterions::values()));
            $table->integer('main_id')->nullable();
            $table->enum('status', CampaignStatus::values())->default(CampaignStatus::PASSIVE);
            $table->string('criterions', 255);
            $table->dateTime('active_since')->nullable();
            $table->dateTime('active_till')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onDelete('cascade');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('cascade');
            $table->foreign(['campaign_id'])->references(['id'])->on('campaigns')->onDelete('cascade');
        });
        Schema::table('order_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('cascade');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_status_index');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_status_index');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_category_id_foreign');
            $table->dropIndex('products_status_index');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_customer_id_foreign');
            $table->dropIndex('orders_status_index');
            $table->dropForeign('orders_campaign_id_foreign');
        });
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropForeign('order_products_product_id_foreign');
            $table->dropForeign('order_products_order_id_foreign');
        });
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('campaigns');
    }
};

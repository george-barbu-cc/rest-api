<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->string('contact_customer_fullname');
            $table->unsignedBigInteger('contact_product_type_offered_id');
            $table->string('contact_product_type_offered');
            $table->date('date');
            $table->string('type');
            $table->string('region');

            $table->string('entry_id')->nullable();
            $table->bigInteger('sale_net_amount')->nullable();
            $table->bigInteger('sale_gross_amount')->nullable();
            $table->bigInteger('sale_tax_rate')->nullable();
            $table->bigInteger('sale_product_total_cost')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};

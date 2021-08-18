<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartitems', function (Blueprint $table) {
            $table->primary(['cart_id', 'item_id']);
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('quantity');

            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartitems');
    }
}

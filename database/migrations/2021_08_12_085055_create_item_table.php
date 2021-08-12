<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('item_name' , 100);
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedSmallInteger('rating');
            $table->decimal('price');      
            
            $table -> integer('offer');
            $table -> date('offer_end_date');

            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}

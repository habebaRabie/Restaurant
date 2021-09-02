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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            
            $table->unsignedBigInteger('category_id');
            $table->unsignedSmallInteger('rating')->default(0);
            $table->decimal('price');      
            $table -> decimal('offer')->nullable();
            $table -> datetime('offer_end_date')->nullable();
            $table -> char('file_path')->nullable();
            

            $table->timestamps();


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

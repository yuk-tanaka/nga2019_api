<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreweriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breweries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('prefecture')->index();
            $table->string('sakenote_maker_id')->index()->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('web', 1024)->nullable();
            $table->string('twitter', 1024)->nullable();
            $table->string('facebook', 1024)->nullable();
            $table->timestamps();
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breweries');
    }
}

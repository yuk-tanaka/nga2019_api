<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('area_id')->index();
            $table->string('name')->index();
            $table->string('google_search_keyword')->nullable();
            $table->string('google_place_id')->nullable();
            $table->string('google_url', 1024)->nullable();
            $table->string('address')->nullable();
            $table->double('latitude')->nullable()->index();
            $table->double('longitude')->nullable()->index();
            $table->string('tel')->nullable();
            $table->string('web', 1024)->nullable();
            $table->string('twitter', 1024)->nullable();
            $table->string('facebook', 1024)->nullable();
            $table->string('tabelog', 1024)->nullable();
            $table->text('business_hours')->nullable();
            $table->boolean('can_auto_update')->default(true);
            $table->boolean('is_closed')->default(false);
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
        Schema::dropIfExists('restaurants');
    }
}

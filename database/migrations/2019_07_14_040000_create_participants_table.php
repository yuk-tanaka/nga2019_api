<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('brewery_id')->index();
            $table->unsignedInteger('year')->index();
            $table->dateTime('opened_at')->index();
            $table->dateTime('closed_at')->index();
            //マルチカラムアトリビュートだがMAX2なので許容
            $table->dateTime('opened_at_after_break')->nullable()->index();
            $table->dateTime('closed_at_after_break')->nullable()->index();
            $table->text('restaurant_description')->nullable();
            $table->text('sake_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}

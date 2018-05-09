<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('registers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 1024);
            $table->string('surname');
            $table->integer('age')->unsigned()->index();
            $table->timestamps();

            $table->integer('policy_id')->unsigned();

            $table->foreign('policy_id')->references('id')->on('policies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('registers');
    }

}

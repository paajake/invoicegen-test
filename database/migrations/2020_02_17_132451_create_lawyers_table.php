<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("title_id")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("image")->default("default.png");
            $table->unsignedBigInteger("rank_id");
            $table->string("email")->unique();
            $table->string("phone")->nullable();
            $table->decimal("addon_rate")->default(0);
            $table->timestamps();

            $table->foreign('title_id')
                ->references('id')
                ->on('titles')
                ->onDelete("SET null");

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lawyers');
    }
}

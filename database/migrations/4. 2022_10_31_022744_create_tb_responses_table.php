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
        Schema::create('tb_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("form_id")
            ->references("id")
            ->on(tb_forms::class)
            ->cascadeOnDelete();
            $table->foreignId("user_id")
            ->references("id")
            ->on(tb_users::class)
            ->cascadeOnDelete();
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
        Schema::dropIfExists('tb_responses');
    }
};

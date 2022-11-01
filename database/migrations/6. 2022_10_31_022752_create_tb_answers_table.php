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
        Schema::create('tb_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("response_id")
            ->references("id")
            ->on(tb_responses::class)
            ->cascadeOnDelete();
            $table->foreignId("question_id")
            ->references("id")
            ->on(tb_questions::class)
            ->cascadeOnDelete();
            $table->text("value");
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
        Schema::dropIfExists('tb_answers');
    }
};

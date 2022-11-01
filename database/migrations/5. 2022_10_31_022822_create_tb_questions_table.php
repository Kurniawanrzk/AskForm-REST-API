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
        Schema::create('tb_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("form_id")
            ->references("id")
            ->on(tb_forms::class)
            ->cascadeOnDelete();
            $table->string("name");
            $table->enum("choice_type", ['short_answer', 'paragraph', 'data', 'time', 'multiple_choice', 'drop_down', 'checkboxes']);
            $table->string("choices")->nullable();
            $table->boolean("is_required");
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
        Schema::dropIfExists('tb_questions');
    }
};

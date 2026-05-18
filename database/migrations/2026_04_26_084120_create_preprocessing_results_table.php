<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preprocessing_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dataset_id');
            $table->longText('original_text');
            $table->longText('processed_text')->nullable();

            $table->timestamps();

            $table->foreign('dataset_id')
                  ->references('id')
                  ->on('datasets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preprocessing_results');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();

            $table->string('conversation_id_str')->nullable();
            $table->integer('favorite_count')->nullable();
            $table->text('full_text')->nullable();
            $table->string('id_str')->nullable();
            $table->text('image_url')->nullable();
            $table->string('in_reply_to_screen_name')->nullable();
            $table->string('lang')->nullable();
            $table->string('location')->nullable();
            $table->integer('quote_count')->nullable();
            $table->integer('reply_count')->nullable();
            $table->integer('retweet_count')->nullable();
            $table->text('tweet_url')->nullable();
            $table->string('user_id_str')->nullable();
            $table->string('username')->nullable();
            $table->string('label')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
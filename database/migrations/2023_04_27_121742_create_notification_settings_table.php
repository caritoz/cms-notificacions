<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('notification_types_id');
            $table->foreign('notification_types_id')
                ->references('id')->on('notification_types')
                ->onDelete('cascade');

            // unique indexes because eloquent doesn't support COMPOSITE PRIMARY KEYS // Ref: https://laravel.com/docs/8.x/eloquent#composite-primary-keys
            $table->unique(['user_id','notification_types_id']);

            $table->json('channel');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};

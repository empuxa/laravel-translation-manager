<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('translation-manager.table'), static function (Blueprint $table): void {
            $table->id();
            $table->string('file');
            $table->string('name');
            $table->string('group_key');
            $table->string('locale', 16);
            $table->text('translation');
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('translation-manager.table'));
    }
};

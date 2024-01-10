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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('newsId');
            $table->string('type');
            $table->string('webTitle');
            $table->string('sectionId');
            $table->string('sectionName');
            $table->string('webPublicationDate');
            $table->string('webUrl');
            $table->string('apiUrl');
            $table->string("pillarId")->nullable();
            $table->string("pillarName")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

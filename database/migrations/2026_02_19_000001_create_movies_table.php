<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->string('title_en');
            $table->string('title_pl')->nullable();
            $table->string('title_de')->nullable();
            $table->text('overview_en')->nullable();
            $table->text('overview_pl')->nullable();
            $table->text('overview_de')->nullable();
            $table->date('release_date')->nullable();
            $table->string('poster_path')->nullable();
            $table->decimal('popularity', 8, 3)->nullable();
            $table->decimal('vote_average', 4, 2)->nullable();
            $table->unsignedInteger('vote_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

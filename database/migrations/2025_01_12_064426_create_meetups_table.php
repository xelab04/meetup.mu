<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("meetups", function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->string("community");
            $table->string("title");
            $table->text("abstract")->nullable();
            $table->string("location")->nullable();
            // default 100 cause I don't want logic of using value 0/null
            $table->integer("capacity")->default(50);
            $table->text("registration")->nullable();
            $table->dateTime("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("meetups");
    }
};

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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tempat_id');
            $table->text('name');
            $table->text('plat')->unique();
            $table->boolean('status')->default(false);
            $table->text('condition');
            $table->date('warranty');
            $table->integer('capacity');
            $table->enum('category', ['mobil', 'motor', 'truk']);
            $table->text('color');
            $table->text('photo')->nullable();
            $table->date('tax');
            $table->timestamps();
            $table->foreign('tempat_id')->references('id')->on('tempats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};

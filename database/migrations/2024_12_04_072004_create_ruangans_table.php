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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tempat_id');
            $table->text('code')->unique();
            $table->boolean('status')->default(false);
            $table->integer('capacity')->nullable();
            $table->enum('category', ['kelas', 'lab', 'gudang']);
            $table->text('photo')->nullable();
            $table->timestamps();
            $table->foreign('tempat_id')->references('id')->on('tempats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};

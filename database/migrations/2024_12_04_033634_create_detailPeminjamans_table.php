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
        Schema::create('detailPeminjamans', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'returned', 'canceled']);
            $table->timestamp('borrowedDate')->nullable();
            $table->timestamp('estimatedTime')->nullable();
            $table->timestamp('returnDate')->nullable();
            $table->text('objective');
            $table->text('destination')->nullable();
            $table->integer('passenger')->nullable();
            $table->text('canceledReason')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamen');
    }
};

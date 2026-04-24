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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('iban')->nullable();
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->decimal('total_income', 15, 2)->default(0);
            $table->integer('total_transactions')->default(0);
            $table->string('currency')->default('RON');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

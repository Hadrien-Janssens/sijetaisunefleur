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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('reference');
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_paid')->default(true);
            $table->boolean('with_tva')->default(false);
            $table->string('comment')->nullable();
            $table->string('echeance')->nullable();
            $table->integer('remise')->default(0);
            $table->float('remiseAmount')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

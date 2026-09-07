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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Quién realizó la venta
            $table->decimal('total', 10, 2);
            $table->string('metodo_pago')->default('Efectivo'); // Efectivo, QR, Tarjeta, etc.
            $table->decimal('monto_pagado', 10, 2)->nullable();
            $table->decimal('cambio', 10, 2)->nullable();
            $table->string('estado')->default('Completado'); // Completado, Anulado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

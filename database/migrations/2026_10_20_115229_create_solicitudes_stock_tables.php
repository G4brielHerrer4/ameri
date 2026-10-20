<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cabecera de la solicitud
        Schema::create('solicitudes_stock', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id') // El admin que pide (A)
                ->constrained('users')->cascadeOnDelete();
                
            $table->foreignId('proveedor_user_id') // El admin dueño del stock (B)
                ->constrained('users')->cascadeOnDelete();
                
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'completada'])
                ->default('pendiente');
                
            $table->text('nota')->nullable();
            $table->timestamps();
        });

        // 2. Detalles de la solicitud
        Schema::create('solicitud_stock_detalles', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('solicitud_id')
                ->constrained('solicitudes_stock')->cascadeOnDelete();
                
            $table->foreignId('producto_id')
                ->constrained('productos')->restrictOnDelete();
                
            $table->decimal('cantidad', 12, 2)->comment('Cantidad física solicitada');
            $table->decimal('precio_acordado', 12, 2)->nullable()->comment('Precio que pone el admin B');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_stock_detalles');
        Schema::dropIfExists('solicitudes_stock');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE notificaciones
            MODIFY COLUMN tipo ENUM(
                'stock_prestado',
                'stock_devuelto',
                'stock_faltante',
                'info'
            ) NOT NULL DEFAULT 'info'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE notificaciones
            MODIFY COLUMN tipo ENUM(
                'stock_prestado',
                'stock_devuelto',
                'info'
            ) NOT NULL DEFAULT 'info'
        ");
    }
};
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProbarCorreo extends Command
{
    protected $signature = 'correo:probar {destino}';

    protected $description = 'Prueba el envío de correo de AMERI';

    public function handle(): int
    {
        $destino = $this->argument('destino');

        try {
            Mail::raw(
                '¡Hola! Este es un correo de prueba del sistema AMERI. '
                . 'La configuración de correo está funcionando correctamente.',
                function ($message) use ($destino) {
                    $message
                        ->to($destino)
                        ->subject('Prueba de correo - AMERI');
                }
            );

            $this->info('Solicitud de envío completada. Revisa tu bandeja de entrada.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('No se pudo enviar el correo.');
            $this->line($e->getMessage());

            return self::FAILURE;
        }
    }
}

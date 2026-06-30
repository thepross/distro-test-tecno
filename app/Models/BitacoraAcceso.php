<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BitacoraAcceso extends Model
{
    protected $table = 'bitacora_accesos';

    protected $fillable = [
        'id_usuario',
        'email',
        'evento',
        'estado',
        'ip',
        'metodo',
        'ruta',
        'user_agent',
        'descripcion',
        'state',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Registra eventos de acceso sin depender de permisos ni roles.
     *
     * Eventos usados:
     * - login_aceptado
     * - login_fallado
     * - usuario_inactivo
     * - logout
     * - bloqueo_login
     *
     * Este método no debe bloquear el login si la bitácora presenta algún problema,
     * pero sí deja el error en storage/logs/laravel.log para poder diagnosticarlo.
     */
    public static function registrar(
        Request $request,
        string $evento,
        string $estado,
        ?string $descripcion = null,
        ?int $idUsuario = null,
        ?string $email = null
    ): void {
        try {
            if (! Schema::hasTable('bitacora_accesos')) {
                Log::warning('Bitácora de accesos no registrada: la tabla bitacora_accesos no existe. Ejecute php artisan migrate.');
                return;
            }

            DB::table('bitacora_accesos')->insert([
                'id_usuario' => $idUsuario,
                'email' => $email ?: $request->input('email') ?: optional($request->user())->email,
                'evento' => $evento,
                'estado' => $estado,
                'ip' => $request->ip(),
                'metodo' => $request->method(),
                'ruta' => $request->path(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'descripcion' => $descripcion,
                'state' => 'a',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('No se pudo registrar la bitácora de accesos.', [
                'evento' => $evento,
                'estado' => $estado,
                'email' => $email ?: $request->input('email') ?: optional($request->user())->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PagoFacilService
{
    private const MONTO_PRUEBA_QR = 0.10;

    public function generarQrPedido($pedido): array
    {
        $token = $this->getAccessToken();
        $pedido->loadMissing('cliente');
        $cliente = $pedido->cliente;
        $montoReal = (float) $pedido->total;

        $data = [
            'paymentMethod' => 4,
            'clientName' => $this->nombreCliente($cliente),
            'documentType' => 1,
            'documentId' => (string) ($cliente->ci ?? $cliente->id ?? $pedido->id),
            'phoneNumber' => (string) ($cliente->telefono ?? '70000000'),
            'email' => $cliente->email ?? 'sin_correo@admin.com',
            'paymentNumber' => 'P' . $pedido->id,
            'amount' => self::MONTO_PRUEBA_QR,
            'currency' => 2,
            'clientCode' => 'CARLA-CLI-' . ($cliente->id ?? $pedido->id),
            'callbackUrl' => $this->callbackUrl(),
            'orderDetail' => [
                [
                    'serial' => $pedido->id,
                    'product' => 'Pedido #' . $pedido->id . ' | Monto real: Bs ' . number_format($montoReal, 2, '.', '') . ' | Pago de prueba QR',
                    'quantity' => 1,
                    'price' => self::MONTO_PRUEBA_QR,
                    'discount' => 0,
                    'total' => self::MONTO_PRUEBA_QR,
                ],
            ],
        ];

        return $this->enviarSolicitudQr($token, $data);
    }

    public function generarQrParaCuota($cuota): array
    {
        $token = $this->getAccessToken();
        $cuota->loadMissing('planPago.pedido.cliente');
        $pedido = $cuota->planPago->pedido;
        $cliente = $pedido->cliente;
        $montoReal = (float) $cuota->monto;

        $data = [
            'paymentMethod' => 4,
            'clientName' => $this->nombreCliente($cliente),
            'documentType' => 1,
            'documentId' => (string) ($cliente->ci ?? $cliente->id ?? $pedido->id),
            'phoneNumber' => (string) ($cliente->telefono ?? '70000000'),
            'email' => $cliente->email ?? 'sin_correo@admin.com',
            'paymentNumber' => 'P' . $pedido->id . '-C' . $cuota->id,
            'amount' => self::MONTO_PRUEBA_QR,
            'currency' => 2,
            'clientCode' => 'CARLA-CLI-' . ($cliente->id ?? $pedido->id),
            'callbackUrl' => $this->callbackUrl(),
            'orderDetail' => [
                [
                    'serial' => $cuota->id,
                    'product' => 'Pedido #' . $pedido->id . ' - Cuota #' . $cuota->numero_cuota . ' | Monto real: Bs ' . number_format($montoReal, 2, '.', '') . ' | Pago de prueba QR',
                    'quantity' => 1,
                    'price' => self::MONTO_PRUEBA_QR,
                    'discount' => 0,
                    'total' => self::MONTO_PRUEBA_QR,
                ],
            ],
        ];

        return $this->enviarSolicitudQr($token, $data);
    }

    public function montoPrueba(): float
    {
        return self::MONTO_PRUEBA_QR;
    }

    private function enviarSolicitudQr(string $token, array $data): array
    {
        $response = Http::withToken($token)->post(config('services.pagofacil.base_url') . '/generate-qr', $data);

        if ($response->successful() && (int) $response->json('error') === 0) {
            return $response->json('values');
        }

        throw new \Exception('Error al generar el QR de PagoFácil: ' . $response->body());
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('pagofacil_token', 600, function () {
            $response = Http::withHeaders([
                'tcTokenService' => config('services.pagofacil.service_token'),
                'tcTokenSecret' => config('services.pagofacil.secret_token'),
            ])->post(config('services.pagofacil.base_url') . '/login');

            return (string) $response->json('values.accessToken');
        });
    }

    private function callbackUrl(): string
    {
        return config('services.pagofacil.callback_url') ?: route('pagofacil.callback');
    }

    private function nombreCliente($cliente): string
    {
        if (!$cliente) {
            return 'Cliente Distribuidora Carla';
        }

        return trim(($cliente->nombre ?? '') . ' ' . ($cliente->apellido ?? '')) ?: ($cliente->email ?? 'Cliente Distribuidora Carla');
    }
}

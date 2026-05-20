<?php

namespace OnestoIt\Sdk;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Onesto
{
    /**
     * Costruisce la richiesta HTTP base con token + base URL.
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl((string) config('onesto.url'))
            ->withToken((string) config('onesto.token'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('onesto.timeout', 30));
    }

    /**
     * POST helper — ritorna l'array decodificato.
     */
    protected function post(string $endpoint, array $data): ?array
    {
        return $this->client()->post($endpoint, $data)->json();
    }

    /**
     * POST helper — ritorna l'oggetto Response completo (status, headers, …).
     */
    protected function postRaw(string $endpoint, array $data): Response
    {
        return $this->client()->post($endpoint, $data);
    }

    /**
     * Crea una fattura attiva fornendo manualmente cliente, articoli e scadenze.
     *
     * @see https://api.onesto.it
     */
    public function createInvoiceManually(array $data): ?array
    {
        return $this->post('/fatture/nuova/manuale', $data);
    }

    /**
     * Crea una fattura attiva a partire dalla Partita IVA del cliente
     * (i dati anagrafici vengono recuperati automaticamente da Onesto).
     *
     * @see https://api.onesto.it
     */
    public function createInvoiceFromPIVA(array $data): ?array
    {
        return $this->post('/fatture/nuova/piva', $data);
    }
}

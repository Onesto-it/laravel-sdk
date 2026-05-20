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
     * Struttura di `$data` (campi opzionali sono indicati come `?`):
     *
     *   - cliente: array{ name, piva, address, cap, city, province?, country, sdi?, pec?, email?, phone? }
     *   - numerazione: string (nome della numerazione configurata)
     *   - issue_date: string Y-m-d
     *   - tipo_documento?: 'TD01'|'TD01_ACC'|'TD24'|'TD25'
     *   - metodo_pagamento: string
     *   - sconto?: float
     *   - intestazione?: string
     *   - note?: string
     *   - natura?: string (codice natura IVA: N2.1, N2.2, N3.1, ...)
     *   - paid?: float (importo già incassato, crea pagamento)
     *   - articoli: array<int, array{ nome, quantita, prezzo, iva, natura?, descrizione? }>
     *   - scadenze?: array<int, array{ date, value, type: 'percent'|'amount' }>
     *   - invia_sdi?: bool (default true)
     *   - emails?: string[]
     *
     * **Riferimenti Pubblica Amministrazione** (opzionali — progetti finanziati,
     * appalti pubblici, PNRR). Finiscono nei `<DatiOrdineAcquisto>` della
     * FatturaPA al momento dell'invio SDI.
     *
     *   - pa?: array{
     *         cig?: string,             // Codice Identificativo Gara (max 15)
     *         cup?: string,             // CUP singolo (max 15) — usa cups[] per multipli
     *         cups?: string[],          // Lista CUP (max 15 ciascuno). Prevale su cup.
     *         numero_ordine?: string,   // max 20
     *         data_ordine?: string,     // Y-m-d
     *         impegno?: string,         // max 100 — impegno di spesa
     *         determina?: string,       // max 100 — determina/commessa
     *         codice_commessa?: string  // max 100 — codice commessa/convenzione
     *     }
     *
     * @see https://docs.onesto.it
     */
    public function createInvoiceManually(array $data): ?array
    {
        return $this->post('/fatture/nuova/manuale', $data);
    }

    /**
     * Crea una fattura attiva a partire dalla Partita IVA del cliente
     * (i dati anagrafici vengono recuperati automaticamente da Onesto).
     *
     * Struttura di `$data` (campi opzionali con `?`):
     *
     *   - piva: string (con o senza prefisso "IT")
     *   - numerazione?: string (default: "Standard")
     *   - issue_date?: string Y-m-d (default: oggi)
     *   - tipo_documento?: 'TD01'|'TD01_ACC'|'TD24'|'TD25'
     *   - metodo_pagamento?: string
     *   - sconto?: float
     *   - intestazione?: string
     *   - note?: string
     *   - articoli: array<int, array{ nome, quantita, prezzo, iva?, descrizione? }>
     *   - scadenze?: array<int, array{ date, value, type: 'percent'|'amount' }>
     *   - invia_sdi?: bool (default true)
     *   - emails?: string[]
     *
     * **Riferimenti Pubblica Amministrazione** (opzionali — progetti finanziati,
     * appalti pubblici, PNRR). Finiscono nei `<DatiOrdineAcquisto>` della
     * FatturaPA al momento dell'invio SDI.
     *
     *   - pa?: array{
     *         cig?: string,             // Codice Identificativo Gara (max 15)
     *         cup?: string,             // CUP singolo (max 15) — usa cups[] per multipli
     *         cups?: string[],          // Lista CUP (max 15 ciascuno). Prevale su cup.
     *         numero_ordine?: string,   // max 20
     *         data_ordine?: string,     // Y-m-d
     *         impegno?: string,         // max 100 — impegno di spesa
     *         determina?: string,       // max 100 — determina/commessa
     *         codice_commessa?: string  // max 100 — codice commessa/convenzione
     *     }
     *
     * @see https://docs.onesto.it
     */
    public function createInvoiceFromPIVA(array $data): ?array
    {
        return $this->post('/fatture/nuova/piva', $data);
    }
}

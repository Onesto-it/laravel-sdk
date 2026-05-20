# Onesto Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/onesto-it/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/onesto-it/laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/onesto-it/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/onesto-it/laravel-sdk)
[![License](https://img.shields.io/packagist/l/onesto-it/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/onesto-it/laravel-sdk)

SDK Laravel ufficiale per integrare le API di [Onesto.it](https://onesto.it) — fatturazione elettronica, invio SDI, gestione clienti e movimenti.

---

## Requisiti

- PHP **8.1+**
- Laravel **9, 10, 11, 12**

---

## Installazione

```bash
composer require onesto-it/laravel-sdk
```

Il service provider e l'alias `Onesto` vengono registrati automaticamente da Laravel package discovery.

(Opzionale) pubblica il file di configurazione:

```bash
php artisan vendor:publish --tag=onesto-config
```

---

## Configurazione

Nel file `.env`:

```env
ONESTO_TOKEN=la_tua_api_key
# opzionale, default https://api.onesto.it
ONESTO_URL=https://api.onesto.it
# opzionale, default 30s
ONESTO_TIMEOUT=30
```

Il token si genera dal pannello Onesto → **Impostazioni → API**.

---

## Utilizzo

### Creare una fattura manuale

```php
use Onesto;

$result = Onesto::createInvoiceManually([
    'cliente'  => [
        'ragione_sociale' => 'Acme SRL',
        'piva'            => '01234567890',
        'indirizzo'       => 'Via Roma 1',
        'cap'             => '20121',
        'citta'           => 'Milano',
        'provincia'       => 'MI',
        'nazione'         => 'IT',
        'pec'             => 'acme@pec.it',
    ],
    'articoli' => [
        ['descrizione' => 'Consulenza', 'quantita' => 1, 'prezzo' => 1000, 'iva' => 22],
    ],
    'scadenze' => [
        ['data' => '2026-06-30', 'importo' => 1220],
    ],
    'numerazione'      => 'Standard',
    'issue_date'       => '2026-05-20',
    'tipo_documento'   => 'TD01',
    'metodo_pagamento' => 'Bonifico',
    'invia_sdi'        => true,
]);
```

### Creare una fattura da Partita IVA

I dati anagrafici vengono recuperati automaticamente da Onesto:

```php
Onesto::createInvoiceFromPIVA([
    'piva'             => '01234567890',
    'numerazione'      => 'Standard',
    'issue_date'       => '2026-05-20',
    'tipo_documento'   => 'TD01',
    'metodo_pagamento' => 'Bonifico',
    'articoli'         => [...],
    'scadenze'         => [...],
    'invia_sdi'        => true,
]);
```

### Riferimenti Pubblica Amministrazione (CIG, CUP, ordine, determina…)

Per progetti finanziati, appalti pubblici o PNRR puoi passare **due oggetti opzionali**:

- **`pa`** → solo i codici PA (`cig`, `cup`, `cups`).
- **`ordine`** → riferimenti all'ordine d'acquisto (`numero`, `data`, `impegno`, `determina`, `codice_commessa`). Validi anche fuori dalla PA (es. B2B con ordine d'acquisto interno).

Tutti i campi sono **opzionali**; quelli passati vengono inseriti nei `<DatiOrdineAcquisto>` della FatturaPA al momento dell'invio SDI.

```php
Onesto::createInvoiceManually([
    // ... cliente, articoli, scadenze come sopra ...

    'pa' => [
        'cig'  => 'ZF3392A8B7',          // Codice Identificativo Gara
        'cups' => [                       // Codici Unico Progetto (più di uno OK)
            'J53D23000170006',
            'K12C24000050001',
        ],
    ],

    'ordine' => [
        'numero'          => 'ORD-2025-001',
        'data'            => '2025-04-15',
        'impegno'         => 'IMP-123',          // Impegno di spesa
        'determina'       => 'DET-456',          // Determina / commessa
        'codice_commessa' => 'COMM-789',         // Codice commessa / convenzione
    ],
]);
```

Funziona allo stesso modo anche con `Onesto::createInvoiceFromPIVA([...])`.

Vincoli sui campi:

| Campo | Max chars | Note |
|---|---|---|
| `pa.cig` | 15 | Alfanumerico |
| `pa.cup` | 15 | Singolo. Per più CUP usa `pa.cups`. |
| `pa.cups[*]` | 15 | Lista. Prevale su `pa.cup` se entrambi presenti. |
| `ordine.numero` | 20 | Numero ordine |
| `ordine.data` | — | Formato `YYYY-MM-DD` |
| `ordine.impegno` | 100 | Impegno di spesa |
| `ordine.determina` | 100 | Determina / commessa |
| `ordine.codice_commessa` | 100 | Codice commessa / convenzione |

### Senza Facade (dependency injection)

```php
use OnestoIt\Sdk\Onesto;

class FattureController
{
    public function __construct(private Onesto $onesto) {}

    public function store()
    {
        return $this->onesto->createInvoiceManually([...]);
    }
}
```

Per la struttura completa dei payload e tutte le risposte, vedi la [documentazione API Onesto](https://api.onesto.it).

---

## Licenza

MIT © True Solutions S.r.l.

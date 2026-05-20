# Changelog

Tutti i cambiamenti rilevanti di `onesto-it/laravel-sdk`.
Il formato segue [Keep a Changelog](https://keepachangelog.com/it/1.1.0/) e il versionamento è [SemVer](https://semver.org/lang/it/).

## [1.1.1] — 2026-05-20

### Corretto
- **Documentazione dei riferimenti ordine d'acquisto.** In 1.1.0 i campi `numero_ordine`, `data_ordine`, `impegno`, `determina`, `codice_commessa` erano documentati (PHPDoc + README) come parte dell'oggetto `pa`, ma l'API di Onesto li accetta sotto un oggetto separato `ordine` con chiavi `numero` e `data` (senza il prefisso `_ordine`). Chi ha seguito la documentazione 1.1.0 vedeva quei campi silenziosamente ignorati dal validator lato server e la fattura emessa senza i riferimenti ordine. Solo correzione di documentazione: nessun cambio runtime nell'SDK (i payload sono già passati as-is all'API).
- README: l'esempio dei riferimenti PA ora mostra correttamente i due oggetti separati `pa` (CIG/CUP) + `ordine` (numero/data/impegno/determina/codice_commessa).

### Migrazione da 1.1.0
Se stavi passando i campi ordine dentro `pa`, sposta in un nuovo oggetto `ordine` e rinomina:

```diff
  'pa' => [
      'cig'  => 'ZF...',
      'cups' => ['J53...'],
-     'numero_ordine'   => 'ORD-2025-001',
-     'data_ordine'     => '2025-04-15',
-     'impegno'         => 'IMP-123',
-     'determina'       => 'DET-456',
-     'codice_commessa' => 'COMM-789',
  ],
+ 'ordine' => [
+     'numero'          => 'ORD-2025-001',
+     'data'            => '2025-04-15',
+     'impegno'         => 'IMP-123',
+     'determina'       => 'DET-456',
+     'codice_commessa' => 'COMM-789',
+ ],
```

## [1.1.0] — 2026-05-20

### Aggiunto
- Supporto al gruppo opzionale `pa` (Riferimenti Pubblica Amministrazione) nei payload di `createInvoiceManually()` e `createInvoiceFromPIVA()`. Campi disponibili: `cig`, `cup`, `cups[]`, `numero_ordine`, `data_ordine`, `impegno`, `determina`, `codice_commessa`. Quando passati, finiscono nei `<DatiOrdineAcquisto>` della FatturaPA inviata al SDI.
- README aggiornato con esempio dedicato per i riferimenti PA + tabella dei vincoli sui campi.
- PHPDoc dei metodi del client esteso con la struttura completa dei payload accettati.

### Compatibilità
- 100% retro-compatibile con 1.0.x: i nuovi campi sono **tutti opzionali**, payload esistenti continuano a funzionare invariati.

## [1.0.0] — 2026-05-20

### Aggiunto
- Prima release pubblica.
- Namespace `OnestoIt\Sdk\`, facade `Onesto`, service provider `OnestoServiceProvider`.
- Metodi `createInvoiceManually()` e `createInvoiceFromPIVA()`.
- Config `config/onesto.php` con env `ONESTO_TOKEN`, `ONESTO_URL`, `ONESTO_TIMEOUT`.

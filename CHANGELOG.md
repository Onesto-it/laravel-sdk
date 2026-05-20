# Changelog

Tutti i cambiamenti rilevanti di `onesto-it/laravel-sdk`.
Il formato segue [Keep a Changelog](https://keepachangelog.com/it/1.1.0/) e il versionamento è [SemVer](https://semver.org/lang/it/).

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

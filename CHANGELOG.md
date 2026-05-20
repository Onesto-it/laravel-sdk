# Changelog

Tutti i cambiamenti rilevanti di `onesto-it/laravel-sdk`.
Il formato segue [Keep a Changelog](https://keepachangelog.com/it/1.1.0/) e il versionamento è [SemVer](https://semver.org/lang/it/).

## [1.0.0] — 2026-05-20

### Aggiunto
- Prima release pubblica.
- Namespace `OnestoIt\Sdk\`, facade `Onesto`, service provider `OnestoServiceProvider`.
- Metodi `createInvoiceManually()` e `createInvoiceFromPIVA()`.
- Config `config/onesto.php` con env `ONESTO_TOKEN`, `ONESTO_URL`, `ONESTO_TIMEOUT`.

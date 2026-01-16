# Handlings logg

Jeg valgte å opprette filene som tilhører brukergrensesnittet under `/public`-mappen som da er filer som webserveren kan trygt levere, og filene som tilhører serveren og API-et under `/src` for å holde dem separert fra brukergrensesnittet.

Jeg valgte å legge til `*.sqlite3` i .gitignore for å ikke laste opp SQLite databasen til GitHub.
Jeg bekreftet at database tabellene ble opprettet riktig av init-database.php med å sjekke hvilke tabeller som var i databasen med SQLite sitt kommandolinjeverktøy

Jeg satt av for mye tid til å sette opp databasestrukturen.
Jeg valgte å bruke den ekstra tiden til å dobbelsjekke oppgaven og hva som er nødvendig og sette opp tider og frister i kalender min som jeg kanskje kunne gjort litt tidligere.
Jeg valgte å sette opp denne handlings loggen for å begrunne eventuelle valg og problemer som måtte oppstå underveis.
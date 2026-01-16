# Handlings logg

Jeg valgte å opprette filene som tilhører brukergrensesnittet under `/public`-mappen som da er filer som webserveren kan trygt levere, og filene som tilhører serveren og API-et under `/src` for å holde dem separert fra brukergrensesnittet.

Jeg valgte å legge til `*.sqlite3` i .gitignore for å ikke laste opp SQLite databasen til GitHub.
Jeg bekreftet at database tabellene ble opprettet riktig av init-database.php med å sjekke hvilke tabeller som var i databasen med SQLite sitt kommandolinjeverktøy

Jeg satt av for mye tid til å sette opp databasestrukturen.
Jeg valgte å bruke den ekstra tiden til å dobbelsjekke oppgaven og hva som er nødvendig og sette opp tider og frister i kalender min som jeg kanskje kunne gjort litt tidligere.
Jeg valgte å sette opp denne handlings loggen for å begrunne eventuelle valg og problemer som måtte oppstå underveis.

Jeg begynte så på å sette opp registrering og pålogging av bruker tidligere en planlagt fordi jeg trodde det var best å starte med det tidlig siden jeg innså i etterkant av planleggingen at jeg kanskje hadde beregnt for liten tid til det.
Etter at jeg satt opp den funksjonelle delen av endepunkter og sider for registrering av bruker og innlogging hadde jeg fortsatt rikelig av tid igjen som jeg hadde planlagt å bruke til dette.
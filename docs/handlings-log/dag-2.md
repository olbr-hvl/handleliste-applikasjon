# Handlings logg dag 2 (16.01.2026)

Jeg valgte å opprette filene som tilhører brukergrensesnittet under `/public`-mappen som da er filer som webserveren kan trygt levere, og filene som tilhører serveren og API-et under `/src` for å holde dem separert fra brukergrensesnittet.

Jeg valgte å legge til `*.sqlite3` i .gitignore for å ikke laste opp SQLite databasen til GitHub.
Jeg bekreftet at database tabellene ble opprettet riktig av init-database.php med å sjekke hvilke tabeller som var i databasen med SQLite sitt kommandolinjeverktøy

Jeg satt av for mye tid til å sette opp databasestrukturen.
Jeg valgte å bruke den ekstra tiden til å dobbelsjekke oppgaven og hva som er nødvendig og sette opp tider og frister i kalender min som jeg kanskje kunne gjort litt tidligere.
Jeg valgte å sette opp denne handlings loggen for å begrunne eventuelle valg og problemer som måtte oppstå underveis.

Jeg begynte så på å sette opp registrering og pålogging av bruker tidligere en planlagt fordi jeg trodde det var best å starte med det tidlig siden jeg innså i etterkant av planleggingen at jeg kanskje hadde beregnt for liten tid til det.
Jeg har valgt å bruke `password_hash` og `password_verify` funksjonene til PHP for å lagre og sjekke passord på en trygg måte, jeg bruker `PASSWORD_DEFAULT` som algoritme siden dette da bruker PHP sin anbefalte algoritme. Per nå er dette bcrypt med kostfaktor 12 (10 dersom man bruker PHP 8.3 eller eldre).
Etter at jeg satt opp den funksjonelle delen av endepunkter og sider for registrering av bruker og innlogging hadde jeg fortsatt rikelig av tid igjen som jeg hadde planlagt å bruke til dette.
Jeg hoppet over noen steg i sikkerheten når jeg først satt det opp så valgte å bruke litt tid på å forbedre dette, sette inn en sjekk for hvor sterkt passord personen lager, oppdatere hash som bruker gamle algoritmer, gi samme melding om personen prøver å registrere en ny bruker med en email som allerede er knyttet til en bruker eller ikke slik at ekistensen av brukeren ikke blir lekket.

Siden jeg har mer tid igjen tenkte jeg kanskje å legge til litt design ved hjelp av CSS for å gjøre skjemaet for registrering av bruker og innlogging penere, men jeg er litt usikker på om jeg vil ha seperate sider for disse funksjonene eller om jeg bare vil ha knapper på startsiden (oversikt over handlelister) som åpner en dialog med skjemaet.
Jeg tenker å utsette dette valget til jeg har fått tenkt litt mer på det og til jeg har laget oversikten over handlelister siden slik at det er enklere å visualisere valget.
Jeg tenker derfor det beste er å starte å lage oversikt over handlelister siden tidlig slik at jeg da kan bruke mer tid til designet rundt registrering av bruker og innlogging senere.

Når jeg satt opp endepunktene for handlelister så på sletting og endring av handlelister valgte jeg å legge til "account = ?" i SQL-spørringen for å hindre at noen andre enn brukeren som opprettet handlelisten kan slette eller endre handlelisten.
Jeg har ikke lagt inn noen eksplisitt sjekk for om denne situasjonen oppstår og tenker at det ikke er så farlig siden de ville bare ha fått en "success" respons selv om ingen ting skjedde.
Eventuelt kunne en sjekk bli lagt til i koden for å istedenfor gi en feilrespons med HTTP status kode 403.
Status kode 404 kunne også bli brukt for å gjemme eksistensen av en handleliste på den spesifikke id-en, men dette er ikke så nøye i denne situasjonen fordi id-ene til handlelistene er sekvensielle og det er trivielt å gjette seg til en ekte id.
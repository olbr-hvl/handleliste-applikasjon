# Systemdokumentasjon

## Config

Filen [`/config.php`](/config.php) setter konfigurerbare verdier.

Konstanten `PDO_DSN` settes til en [PDO_SQLITE DSN](https://www.php.net/manual/en/ref.pdo-sqlite.connection.php) og bestemmer lokasjonen på SQLite database-filen

## Database

Før applikasjonen gjøres tilgjengelig må [`/init-database.php`](/init-database.php)-filen kjøres for å opprette SQLite databasen og tabellene i denne.
Filen kan kjøres ved å bruke denne kommandoen i terminalen dersom du er i riktig mappe.

```cmd
php -f "init-database.php"
```

## Webserver

Prosjektet har ikke tatt høyde for hvilken type webserver applikasjonen skal kjøre på.
Webserveren bør bare levere ut filene som ligger i mappene [`/public`](/public/) og [`/src/api`](/src/api/).
PHP trenger fortsatt tilgang til filene som ikke skal være tilgjengelig for webserveren ([`/config.php`](/config.php), [`/src/api-functions.php`](/src/api-functions.php) og SQLite databasen). Merk at avhengig av plasseringen av filene på webserveren må nok URL-er i koden endres.

## Testing

For å teste applikasjonen kan man bruke [PHP sin innebygde webserver](https://www.php.net/manual/en/features.commandline.webserver.php).
Webservern kan bli startet med å bruke denne kommandoen i terminalen dersom du er i riktig mappe.

```cmd
php -S localhost:8000 -d auto_prepend_file="testing/prepend_file.php"
```

## API dokumentasjon

Dokumentasjonen på API-et kan man finne på [`/docs/openapi/index.html`](/docs/openapi/index.html).
Dersom du vil bruke "Try it out"-mode for å teste API-et kan det gjøres med [PHP sin innebygde webserver](#testing).
Merk at siden API-et bruker session cookie vil nettleser automatisk håndtere cookien for API-et og den kan ikke bli satt manuelt.

## Sikkerhetsrisikoer

### SQL injection

Koden til applikasjon bruker hovedsaklig prepared statements i SQL for å hindre SQL injection angrep.
Det er enkelte steder der deler av SQL-spørringene er generert dynamisk, men bruker fortsatt genererte placeholders for brukerstyrte variabler eller kjente verdier via tillatelselister.

### CSRF

Applikasjonen er beskyttet mot cross-site request forgery angrep fordi de fleste endepunktene vil utløse [CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Guides/CORS) feil. Alle endepunkt som gjør endringer krever en JSON request body (`Content-Type: application/json`) som gjør at forespørslene ikke blir vurdert som en [simple requests](https://developer.mozilla.org/en-US/docs/Web/Security/Attacks/CSRF#avoiding_simple_requests).

### Grenser

Applikasjonen har ingen grenser som standard på hvor mye data som kan lastes opp i endepunktene.
Det er heller ingen grense på hvor lange navn handlelistene eller varene kan ha.
Dette gjør det enkelt å eventuelt kjøre Denial of Service angrep eller å misbruke API-et for filhosting og fildeling.

### XSS

Applikasjonen skal ikke være sårbar til cross-site scripting angrep fordi all data som er dynamisk og hentet ut fra API-ene er satt inn i sidene med [`Node.textContent`](https://developer.mozilla.org/en-US/docs/Web/API/Node/textContent) og ikke parset som HTML. Det er ingen sanering av brukerdata før eller etter det blir lagt inn i databasen. dataene blir altså lagret akkuratt slik som de ser ut så man må fortsatt være klar over hva man gjør og være forsiktig ved videre utvikling for å ikke ta i bruk utrygge metoder.
# Planlegging

## Mål med oppgaven

Målet med oppdraget er å lage en handleliste-applikasjon.
Kravene til løsningen er at data som tilhører løsningen er strukturert i en database og at det finnes et brukergrensesnitt som er tilgjengelig for brukeren både hjemme og i butikken.

Minimumsforventninger til applikasjonen er:
- Registrering av bruker og pålogging til applikasjonen.
- En eller flere lister med varer.
- En lett måte for brukeren å ha kontroll på hva som er handlet, og hva som gjenstår.

Det er også viktig at sikkerheten i og rundt pålogging og behandling av data er ivaretatt.

## Skisse / Løsningsforslag

Jeg har planlagt å lage en webapplikasjon der jeg bruker PHP som programmeringsspråk for server-siden av applikasjonen og lage ett nettsted ved hjelp av HTML, CSS og JavaScript til brukergrensesnittet. Jeg har valgt dette fordi det er teknologier jeg allerede er kjent med og ett nettsted oppfyller kravet til at brukergrensesnittet er tilgjengelig både hjemme og i butikken siden personen kan gå til nettstedet fra hvilken som helst enhet de bruker så lenge den kan koble seg til internettet.

### Brukergrensesnitt

Jeg har planlagt at brukergrensesnittet vil ha en side for [registrering av bruker](#registrering-av-bruker), [innlogging](#innlogging), [oversikt over handlelister](#oversikt-over-handlelister), og [oversikt over varer i en handleliste](#oversikt-over-varer-i-en-handleliste).
Det er viktig at disse sidene er responsive og ser bra ut på både mobil, nettbrett og pc slik at brukeren kan enkelt bruke applikasjonen uavhengig av hvilken enhet de bruker eller hvor de er.

#### Registrering av bruker

Siden for registrering av bruker vil inneholde et skjema personen fyller ut for å registrere brukeren sin. Skjemaet vil inneholde felt der personen oppgir emailadresse og passord for å kunne logge inn i applikasjonen igjen senere.

#### Innlogging

Siden for innlogging vil inneholde et skjema personen fyller ut for å logge inn og få tilgang til handlelistene sine. Dette skjeamet vil se ganske likt ut som det for å registrere bruker og vil inneholde felt der personen oppgir emailadressen og passordet de oppgav når de registrerte brukeren sin.

#### Oversikt over handlelister

Siden for oversikt over handlelister vil inneholde en liste over alle handlelistene brukeren har tilgang til og dersom man trykker på en bestemt liste vil man gå til en side der de får en [oversikt over varer i handlelisten](#oversikt-over-varer-i-en-handleliste). Brukeren skal også ha tilgang fra denne siden til å opprette nye handlelister, endre på informasjon knyttet til handlelisten, og slette eksisterende handlelister.

#### Oversikt over varer i en handleliste

Siden for oversikt over varer i en handleliste vil inneholde en liste over alle varene som tilhører handlelisten. Brukeren skal ha tilgang til å legge til nye varer, endre på informasjonen knyttet til varen (for eksempel navn og om varen er handlet), og kunne fjerne varer fra handlelisten.

### Database

For databasen har jeg planlagt å bruke en SQLite database fordi det er det jeg har mest erfaring med.
I databasen har jeg planlagt å ha tabeller for [brukere](#bruker-tabell), [handleliste](#handleliste-tabell) og [varer](#vare-tabell).

#### Bruker-tabell

Tabellen for brukere vil inneholde brukerinformasjon og påloggingsinformasjon. Blant annet vil tabellen inneholde:

* En automatisk generert id for å identifisere brukeren i applikasjonen.
* Emailadresse til brukeren som brukeren oppgir når de skal logge på og som også kan bli brukt til å kontakte brukeren dersom nødvendig.
* Passordet til brukeren, passordet vil bli lagret i hashet format slik at personer som har tilgang til databasen ikke kan gjenskape eller stjele passordet til brukeren og få tilgang til brukeren.

#### Handleliste-tabell

Tabellen for handlelisten vil inneholde informasjon om hvilken handlelister brukeren har opprettet. Blant annet vil tabellen inneholde:

* En automatisk generert id for å identifisere den spesifikke handlelisten i applikasjonen.
* Et navn på handlelisten som brukeren har valgt selv slik at de kan identifisere handlelisten, dette kan for eksempel være navnet på butikken eller formålet med varene.
* En referanse til brukeren som har opprettet og skal ha tilgang til handlelisten.

#### Vare-tabell

Tabellen for varer vil inneholde informasjon om hvilke varer som tilhører handlelistene. Blant annet vil tabellen inneholde:

* En automatisk generert id for å identifisere den spesifikke varen i applikasjonen.
* Et navn på varen som brukeren har valgt selv slik at de kan identifisere varen.
* En boolsk verdi for om varen er handlet eller ikke slik at brukeren kan ha kontroll på om varen er handlet.
* En referanse til handlelisten som varen tilhører.

### Server og API

Jeg har planlagt at server-siden av applikasjonen er programmert med PHP, dette vil bestå av API-endepunktene som brukergrensesnittet skal bruke for å kommunisere med serveren og håndtering av pålogging til applikasjon. Serveren og API-et har ansvar for å gjøre databasespørringene som trengs for å gi brukergrensesnittet dataene den trenger fra databasen og tilgangsstyring slik at bare den som opprettet en handleliste har lov til å få ut data knyttet til handlelisten. Felles for alle API-endepunktene er at jeg kommer til å bruke JSON som format på HTTP forespørsler og responser. Jeg har valgt dette fordi det er ett format jeg er kjent med fra før og er ett veldig vanlig format som API-er tilbyr og fordi både PHP og JavaScript har innebygd støtte for jobbe med dette formatet som gjør det enkelt å utvikle med.

#### Bruker-endepunkter

Disse endepunktene vil bli brukt av sidene for [registrering av bruker](#registrering-av-bruker) og [innlogging](#innlogging).
Det trengs endepunkter for:

* Registrere en bruker, trenger email og passord som parametere.
* Pålogging, trenger email og passord som parametere.
* Logge ut.

#### Handleliste-endepunkter

Disse endepunktene vil bli brukt av siden for [oversikt over handlelister](#oversikt-over-handlelister).
Alle endepunkter for handlelister trenger at brukeren er pålogget. Det trengs endepunkter for:

* Hente ut en oversikt over alle handlelistene brukeren har opprettet.
* Opprette en ny handleliste, trenger navn som parameter for å bestemme navnet på handlelisten.
* Endring av informasjon knyttet til handlelisten (for eksempel navnet på handlelisten), trenger id som parameter for hvilken handleliste som skal bli endret.
* Sletting av handleliste, trenger id som parameter for hvilken handleliste som skal bli slettet.

#### Vare-endepunkter

Disse endepunktene vil bli brukt av siden for [oversikt over varer i en handleliste](#oversikt-over-varer-i-en-handleliste).
Alle endepunkter for varer trenger at brukeren er pålogget. Det trengs endepunkter for:

* Hente ut en oversikt over alle varer brukeren har lagt til i handlelisten, trenger id på handlelisten som parameter.
* Legge til en ny vare, trenger navn som parameter for å bestemme navnet på varen.
* Endring av informasjon knyttet til varen (for eksempel navnet på varen eller om den har blitt handlet).
* Fjerning av vare fra handleliste, trenger id som parameter for hvilken vare som skal bli fjernet.

### Tilleggsmuligheter

Jeg har også tenkt på noen tilleggsmuligheter utenom minimumskravene og det jeg har planlagt i resten av skissen som kan være greit å utvikle dersom jeg ser jeg har tid til det.
Tilleggsmulighetene jeg har kommet med er sortert etter hvor mye tid og krefter jeg tror de vil ta og hvilke jeg mest sannsynligvis vil utvikle dersom jeg har ekstra tid til det.

#### Glemt passord

Det kan være greit for brukeropplevelsen at brukeren har muligheten til å nullstille passordet sitt dersom de har glemt det eller vil bytte passord.
I siden for [registrering av bruker](#registrering-av-bruker) så hadde det planlagte skjemaet bare ett felt for å skrive inn passordet og ikke ett for å skrive det inn på nytt igjen for å bekrefte at de skrev passordet riktig, derfor ville det vært veldig greit om det var så enkelt som mulig for brukeren å nullstille og endre passordet sitt.

#### Sortering av lister

For [oversikten over handlelister](#oversikt-over-handlelister) og [oversikt over varer i en handleliste](#oversikt-over-varer-i-en-handleliste) kan det være greit for brukeropplevelsen å gi brukeren mulighet til å endre på rekkefølgen av handlelister og rekkefølgen av varer i handlelisten. Dersom brukeren har mange handlelister kan det være tungvint å finne igjen handlelisten de har tenkt å bruke og dette vil da gi de muligheten til å plassere handlelister de ofte bruker øverst og prioritere forskjellige handlelister. Dersom brukeren har mange varer i en handleliste kan det være tungvint å få en oversikt over varene og å kunne endre rekkefølgen vil gi de muligheten til å for eksempel sortere listen deres etter kategorier, prioritet eller rekkefølgen de kommer til å handle varene i butikken.

#### Progressive Web App

Det kan være greit å gjøre nettstedet om til en progressive web app slik at brukeren kan få ett app-ikon på mobilen de kan trykke på for å åpne applikasjonen istedenfor å måtte gå til en nettleser og skrive inn nettadressen. Dette kan gjøre det enklere for de å finne igjen og åpne applikasjonen når de er på farten.

Jeg har ingen erfaring med progressive web apps fra før så å gjøre dette vil også bruke ekstratid på å undersøke muligheter og lære meg om progressive web apps.

##### Offline-modus

Progressive web apps gir også mulighet til å kunne gjøre at applikasjonen kan kjøres offline.
Det kan være greit å bruke disse mulighetene for at brukeren kan se og endre på handlelister og varer mens de ikke er tilkoblet internettet for og så synkronisere eventuelle endringer med databasen etter de er tilkoblet igjen. Dette ville da gi brukeren mulighet til å bruke applikasjonen på mobilen i butikken uten å måtte være tilkoblet ett nettverk eller bruke mobildata.

## Oversikt over arbeidsoppgaver

* Sette opp database.
* Utvikle brukergrensesnitt/nettstedet.
* Utvikle endepunkt.
* Planlegge og utføre test.
* Lage og utføre presentasjon.

## Tidsskjema

### Torsdag

9:00 - 10:00:
Sende inn erklæring fra kandidaten skjema og gjøre meg klar til å begynne å skrive planlegging, sette opp ett nytt repositorium i GitHub.

10:00 - 11:30:
Skrive planlegging.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Skrive planlegging.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Skrive planlegging og gjøre den klar til å sendes inn.

16:30 - 17:00:
Sende planleggings-dokumentet til prøvenemnda.

### Fredag

8:00 - 9:00:
Begynne å utvikle applikasjonen, sette opp fil og mappestruktur som jeg har tenkt å bruke.

9:00 - 11:30:
Utvikle applikasjonen, begynne med å sette opp databasestrukturen.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Utvikle applikasjonen, sette opp registrering og pålogging av bruker.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Utvikle applikasjonen, bli ferdig med å sette opp registrering og pålogging av bruker.

### Mandag

8:00 - 11:30:
Utvikle applikasjonen, begynne å sette opp side for [oversikt over handlelister](#oversikt-over-handlelister) og endepunkter knyttet til handlelister.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Utvikle applikasjonen, bli ferdig med å sette opp side for [oversikt over handlelister](#oversikt-over-handlelister) og endepunkter knyttet til handlelister.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Utvikle applikasjonen, begynne å sette opp side for [oversikt over varer i en handleliste](#oversikt-over-varer-i-en-handleliste) og endepunkter knyttet til varer.

### Tirsdag

8:00 - 11:30:
Utvikle applikasjonen, bli ferdig med å sette opp side for [oversikt over varer i en handleliste](#oversikt-over-varer-i-en-handleliste) og endepunkter knyttet til varer.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Utvikle applikasjonen, begynne å innføre liten endring som prøvenemnda har kommet med tidligere.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Utvikle applikasjonen, bli ferdig med liten endring som prøvenemnda har kommet med dersom jeg ikke er ferdig eller arbeid med dokumentasjonen som er laget så langt.

### Onsdag

8:00 - 11:30:
Lage og bekrefte at all dokumentasjon som er nødvendig er på plass.
Begynne å planlegge og dokumentere test.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Gjennomføre og dokumentere resultat av testen.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Bli ferdig meg testrapporten.

### Torsdag

8:00 - 11:30:
Tid satt av til å ferdigstille hva som ikke er blitt ferdig ennå, enten om det måtte være utvikling, testing eller dokumentasjon av arbeid.
Eventuelt se på og utvikle tilleggsmuligheter dersom jeg har tid til det.

11:30 - 12:00:
Lunsjpause.

12:00 - 13:00:
Starte å lage presentasjon.

13:00 - 14:00:
Gå hjem til hjemmekontor, finne meg mat, kort pause og gjøre meg klar til å fortsette arbeidet.

14:00 - 16:30:
Bli ferdig med å lage presentasjon.

16:30 - 17:00:
Sende inn dokumentasjon på arbeidet til prøvenemnda.

### Fredag

08:00 - 08:30:
Forbredelse til presentasjon.

08:30:
Presentasjon av applikasjon og arbeid for prøvenemnda.

## Programvare, utstyr og ressurser

* [Visual Studio Code](https://code.visualstudio.com/), kode-editoren jeg bruker.
* [Git](https://git-scm.com/), versjonskontrollsystem.
* [GitHub](https://github.com/), for å dele Git repositoriumet med andre på nettet.
* [PHP](https://www.php.net/), programmeringsspråk jeg har tenkt å programmere server-siden av applikasjonen med, PHP kommer også med en [innebygd webserver](https://www.php.net/manual/en/features.commandline.webserver.php) som jeg kommer til å bruke under testing og utvikling av applikasjonen.
* [Chrome](https://www.google.com/intl/no/chrome/), nettleseren jeg kommer til å bruke under utvikling og testing av applikasjonen.
* [SQLite](https://sqlite.org/), databasen jeg har planlagt å bruke, SQLite har også ett [kommandolinjeverktøy](https://sqlite.org/cli.html) for å jobbe med databaser som jeg kommer til å bruke under utvikling og testing av applikasjonen.

## Informasjonskilder og samarbeidspartnere

* [MDN Web Docs](https://developer.mozilla.org/en-US/), dokumentasjon på web teknologier som HTML, CSS, og JavaScript.
* [SQLite](https://sqlite.org/), dokumentasjon på SQLite.
* [SQLite Tutorial](https://www.sqlitetutorial.net/), referanse på SQL-spørringer og syntax tilpasset SQLite.
* [PHP](https://www.php.net/), dokumentasjon på PHP.
* Terje Rudi, har hatt det faglige ansvaret for opplæringen min under læretiden og kan nok hjelpe meg dersom jeg sitter fast og har behov for det.
* Vebjørn Hjelmeseter, har vært lærling i samme fag tidligere og bestod fagprøven i fjor sommer og kan nok hjelpe meg dersom jeg sitter fast og har behov for det.

## Kostnad

Jeg har ingen planlagte kostnader utover kostnader knyttet til ansettelsesforhold mitt som kontorplass, utstyr og arbeidstid brukt på fagprøven.
Alle programvarer og ressurser jeg har tenkt å bruke under fagprøven for å utvikle og teste applikasjonen er åpne og gratis.
# Handlings logg dag 5 (21.01.2026)

Jeg valgte å legge til hidden på starten på en del elementer som bare vises når du er logget inn/logget ut slik at de ikke popper opp mens siden laster inn. Dersom tilkoblinger din er rask ville dette bare vært noen milliesekunder, men der er også greit å gjøre for de med tregere tilkobling slik at de blant annet ikke ser "Ny handleliste"-knappen før de har logget inn.

Jeg la til aria-label på knappene og endre navn feltet i listene slik at folk med skjermlesere også skal kunne vite hva knappene og feltet gjør, jeg la også til samme test i title attributtet slik at teksten vises dersom man holder musepekeren over elementene.
Jeg la symbolene jeg brukte som ikoner inn i ett span med aria-hidden slik at det ikke er noen skjanse for at skjermlesere skal prøve å lese opp symbolene og leser heller opp teksten i aria-label.
Jeg oppdaget at måten jeg markerte at en vare i handlelisten var kjøpt ble ikke tilgjengelig for skjermlesere siden jeg bare la på stiler for å visuelt markere at varen var kjøpt, jeg har endret knappen som før ble brukt til å endre statusen til en avkrysningsboks som bare er tilgjengelig for skjermlesere.

For brukertesting valgte jeg å sette opp applikasjonen på v.hvl.no som er en webserver jeg har tilgang til slik at jeg kunne dele applikasjonen med de som ville bidra med å teste.
Etter jeg hadde satt opp applikasjonen testet jeg selv applikasjonen på mobil for å se om alt virket ok.
Jeg oppdaget da at ikonene jeg brukte på opp og ned knappen tydeligvis ikke var inkludert i fonten på mobilen min siden de viste bare en generisk boks som ikonet, jeg endret ikonene til mer vanlige symboler som jeg håper på er tilgjengelige på flere enheter.

Jeg oppdaget at dersom man trykker på flytt opp/ned knappene to ganger før den første responsen fra endepunktet kommer tilbake så beveger knappen seg i listen to ganger mens i databasen vil den bare ha beveget seg en gang (altså de blir desynkronisert), dette fikser seg av seg selv dersom man trykker på en av flytte knappene en gang til før man går ut av applikasjonen men kan fortsatt være forvirrende dersom det oppstår.
Jeg har ikke valgt å fikse dette siden det er ett sjeldent tilfelle, jeg kunne for eksempel ha gjort det mindre sannsynlig å desynkronisere med at knappene utførte den visuelle handlingen med en gang uten å vente på respons fra endepunktet, men det ville fortsatt være mulig å desynkronisere listen dersom endepunktet enten ikke mottar forespørselen eller mottar forespørslene i feil rekkefølge.
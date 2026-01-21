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

Jeg endret registrering av bruker endepunktet og skjemaet til å logge inn brukeren automatisk etter at den var opprettet igjen, jeg tokk vekk denne muligheten tidligere fordi det ville være mulig å bruke endepunktet til å se om en bruker hadde registrert konto (for eksempel har test@example.com opprettet en konto?).
Jeg gjorde denne endringen for å gjøre brukeropplevelsen bedre under testing, men har latt endringen bli på grunn av at i den nåværende versjonen er det uansett mulig å sjekke om en email har registrert konto med å registrere kontoen og så prøve å logge seg inn etterpå, hvis du greier å logge deg inn så har ingen registrert kontoen før.
På grunn av at det ikke er noen verifikasjon på om man faktisk har eierskap til emailen så vil du egentlig ikke vite om det er personen som eier emailen som har opprettet brukeren eller ikke så det er ikke ett så stort problem.
For å fikse dette problemet måtte jeg eventuelt ha verifisert eierskapet av emailen som man registrerer seg med og sendt de en email for å verifisere eierskapet.
Da ville flyten ha vært:
Registrer konto -> Klikk på lenke i email for å verifisere eierskap -> Logg inn
Angriperen vil ikke lenger kunne sjekke om en bruker ekisterer fordi de vil ikke ha tilgang til å se om emailen er sendt og de vil ikke kunne logge inn uansett om brukeren eksisterte fra før eller ikke.
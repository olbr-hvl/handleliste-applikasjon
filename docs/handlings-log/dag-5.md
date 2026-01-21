# Handlings logg dag 5 (21.01.2026)

Jeg valgte å legge til hidden på starten på en del elementer som bare vises når du er logget inn/logget ut slik at de ikke popper opp mens siden laster inn. Dersom tilkoblinger din er rask ville dette bare vært noen milliesekunder, men der er også greit å gjøre for de med tregere tilkobling slik at de blant annet ikke ser "Ny handleliste"-knappen før de har logget inn.

Jeg la til aria-label på knappene og endre navn feltet i listene slik at folk med skjermlesere også skal kunne vite hva knappene og feltet gjør, jeg la også til samme test i title attributtet slik at teksten vises dersom man holder musepekeren over elementene.
Jeg la symbolene jeg brukte som ikoner inn i ett span med aria-hidden slik at det ikke er noen skjanse for at skjermlesere skal prøve å lese opp symbolene og leser heller opp teksten i aria-label.
Jeg oppdaget at måten jeg markerte at en vare i handlelisten var kjøpt ble ikke tilgjengelig for skjermlesere siden jeg bare la på stiler for å visuelt markere at varen var kjøpt, jeg har endret knappen som før ble brukt til å endre statusen til en avkrysningsboks som bare er tilgjengelig for skjermlesere.
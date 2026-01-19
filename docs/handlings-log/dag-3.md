# Handlings logg dag 3 (19.01.2026)

Jeg valgte å gå vekk fra de seperate sidene for registrering av bruker og innlogging til å ha de som knapper som åpner dialoger med skjemaene på samme siden som oversikt over handlelister.
Jeg har også nå håndtert hva som skal vises på oversikt over handlelister siden om du er logget inn eller ikke logget inn.
Jeg valgte å legge inn en logg ut knapp som vises når personen er logget inn slik at brukeren har muligheten til å logge ut dersom de ønsker det, i tilegg gjør dette det lettere for meg under testing.

Når jeg begynte å sette opp vare-endepunktene så innså jeg at det kan være greit å ha en funksjon for å teste om brukeren har tilgang til å gjøre handlingen, så jeg har satt opp to nye funksjoner i `/src/api-functions.php` (`accountHasAccessToShoppingList` og `accountHasAccessToShoppingListItem`).
Når jeg satt opp handleliste-endepunktene på fredag så satt jeg inn `account = ?` for at handlingene bare skulle utføres når man hadde tilgang, men har endret det til å bruke `accountHasAccessToShoppingList` for å feilhåndtere situasjonen bedre.
Jeg kunne ikke like lett legge til `account = ?` på de nye vare-endepunktene fordi da måtte jeg legge til en `LEFT JOIN shoppinglist` fordi vare-tabellen har ingen direkte referanse til hvem som skal ha tilgang til varen så jeg tenkte det var bedre å lage disse to funksjonene som også gir meg muligheten til å håndtere feilene mer eksplisitt.
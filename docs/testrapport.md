# Testrapport

## Endringer gjort i applikasjonen ved brukertesting

Jeg måtte endre litt på URL-ene i koden for at de skulle stemme overens med hvor jeg la applikasjonen på webserveren som jeg testet med.

Jeg la til en startside for brukertestere med hva jeg ønsker de å teste:

> Velkommen til brukertesting av handleliste applikasjon  
> Her er en liste med oppgaver du kan gå gjennom, du velger selv hvor mye du vil teste.
> 
> 1. Registrer en bruker.
> 2. Opprett en handleliste.
> 3. Legg til varer i handlelisten.
> 4. Gå til butikken og bruk applikasjonen og marker varer som handlet underveis.
> 6. Rediger varer i handlelisten: endre navn på varen, endre posisjon på varen, og slett varen.
> 7. Rediger handlelister: endre navn på handlelister, endre posisjon på handlelister, og slett handlelister.
> 
> Etter du er ferdig kan du gjerne svare på:
> 
> * Hvordan var det å bruke applikasjonen?
> * Hadde du noen problemer med applikasjonen?
> * Har du noen forslag til forbedringsmuligheter?
> * Har du noen andre tilbakemeldinger?
> 
> (lenke) Åpne handleliste applikasjonen  
> Du kan sende tilbakemeldinger på teams

Jeg la også til en ekstra melding i skjemaet for å registrere ny bruker:

> Under testing trenger du ikke å bruke en ekte e-post adresse.  
> Vennligst ikke gjenbruk passord.

## Problemer og løsninger

> Det går ikke an å opprette handlelister.

Vi hadde et problem tidlig i testfasen der personer ikke fikk opprettet handleliste etter at de registrerte kontoen sin, dette var forårsaket av endringen jeg gjorde i forbindelse med testfasen.
Jeg hadde kopiert kode fra login og limt inn i signup for å logge de inn automatisk etter at kontoen var opprettet, men $accountId variabelen var ikke satt så de fikk en tom id i sin sesjon som gjorde at det å opprette handlelister ikke fungerte.
Personene som opplevde dette problemet måtte logge ut og logge inn igjen for å fikse sesjonen sin.

> Det var vanskelig å oppdage "Ny handleliste" / "Legg til vare" knappene.

Dette kan fikses med å legge inn tekst dersom listene er tomme, noe sånt som "Du har ingen handlelister/varer ennå, trykk på knappen nederst for å opprette en ny handleliste/legge til en vare"

> Jeg ville redigere navnet på handlelisten. Forsøkte Enter, men mått klikke på X-en, og da forsvant lista.

Det er kanskje ikke intuitivt nok at endringene i redigeringsmodus lagres automatisk.
Forslag fra brukeren er at "Rediger"-knappen endrer tekst til "Ferdig" slik at den er mer intuitiv å trykke på.
Jeg tror heller ikke det er så farlig siden brukeren vil etter vært innse hva som skjer etter litt prøving og feiling i starten.

> Jeg finner ikke ut hvordan jeg sletter varer.

På mobilen til denne personen tok navne redigerings feltet og knappene så mye plass at slette knappen forårsaket overflow, personen fant ikke ut at det var mulig å horisontalt rulle.
Jeg la tidligere til `overflow-x: auto;` på `<li>`-elementene for at de skulle håndtere lange navn og være mulige å rulle horisontalt, dette gjorde det mulig å rulle horisontalt for å finne slette knappen men det er ikke intuitivt nok.
Enten må `min-width` på navne redigerings feltet settes lavere slik at det er mindre sjanse for overflow eller så kan `flex-wrap: wrap` settes for at knappene heller skal komme på neste linje, men dette forårsaker at det er ulike høyder på `<li>`-elementene i redigeringsmodus og ikke så det måtte eventuelt fikses også.

> I firefox hvis man har mange varer i handlelisten slik at det er overflow så overflower hele siden også

Det er ikke ideelt at hele siden blir scrollbar og ikke bare handlelisten, må undersøkes nærmere.

## Tilbakemeldinger

> Man bør komme inn i handlelisten automatisk etter den har blitt opprettet.

Jeg er enig med at man bør navigeres inn i handlelisten automatisk etter at den har blitt opprettet, det er sjeldent noen trenger å bli værende på oversikt over handlelister siden for å lage flere handlelister på en gang og det er sikkert bedre å komme raskere i gang med å legge til varer i handlelisten.

> Man bør kunne se om en vare er handlet i redigeringsmodus dersom man vil slette en handlet vare.

Det er mulig å legge til `text-decoration: line-through;` på navne redigeringsfeltet for å markere om varen er handlet eller ikke. Det må først undersøkes nærmere om denne stilen kommer til hinder for den originale funksjonen til feltet, altså om linjen gjennom navne redigeringsfeltet kan komme til hinder når man skal endre navnet på en handlet vare.
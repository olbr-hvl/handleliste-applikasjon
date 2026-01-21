# Testrapport

## Endringer gjort i applikasjonen ved brukertesting

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

## Tilbakemeldinger

> Man bør komme inn i handlelisten automatisk etter den har blitt opprettet.

Jeg er enig med at man bør navigeres inn i handlelisten automatisk etter at den har blitt opprettet, det er sjeldent noen trenger å bli værende på oversikt over handlelister siden for å lage flere handlelister på en gang og det er sikkert bedre å komme raskere i gang med å legge til varer i handlelisten.
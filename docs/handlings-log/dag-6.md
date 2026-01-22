# Handlings logg dag 6 (22.01.2026)

Jeg valgte å bruke tid på å sette opp API dokumentasjon med [Swagger UI](https://github.com/swagger-api/swagger-ui) fordi dette er ett mye brukt verktøy for utviklere for å få en oversikt over API-ene til tjenester og prøve de ut.

Jeg valgte å legge til feilmeldinger når noe går gale, for eksempel når brukeren skriver inn feil email eller passord.
Jeg la også til hjelpful informasjon når man ikke er logget inn, eller når listen over handlelister eller varer er tomme.
Jeg håper dette vil gjøre det enklere for førstegangs brukere av applikasjonen.

Jeg dokumenterte litt mer om mulige sikkerhetsrisikoer.

Jeg valgte å endre litt på koden for flytting opp og ned av handlelister/varer i listene slik at koden er enklere å gjenbruke. For eksempel vil det nå være enklere å lage en knapp som flytter handlelister/varer helt til topp eller bunn av listene.
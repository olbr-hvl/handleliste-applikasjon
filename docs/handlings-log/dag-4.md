# Handlings logg dag 4 (20.01.2026)

Jeg valgte å sette opp to nye tabeller i databasen for å lagre sorteringen brukeren skaper `shopping_list_order` og `shopping_list_item_order`.
Et alternativ ville vært å ha en tabell `order` og de to andre tabellene kunne ha vært mange til mange tabeller som refererte mellom `order` og `shopping_list` / `shopping_list_item`, men jeg følte det ville være mer komplekst for en liten fordel dersom man trengte flere sorterbare lister.
Jeg tokk også anledningen til å rydde opp i de ekisterende tabell definisjonene.
Jeg la til `DEFAULT FALSE` på `bought`-kolonnen slik at jeg ikke trenger å oppgi verdien i `INSERT`-spørringer.
Jeg ryddet opp i bruken min av `shoppinglistitem` vs `shopping_list_item` der jeg har tidligere har brukt en blanding av `shoppingListItem` og `shoppinglistItem` i JS og PHP kode (og tilsvarende for `shoppinglist`).

Mens jeg testet applikasjonen litt oppdaget jeg at `ON DELETE CASCADE` i kolonne definisjonene mine ikke virket sånn som jeg forventet.
Det viste seg at SQLite ikke har foreign keys constraints aktive som standard og må aktiveres per forbindelse med `PRAGMA foreign_keys = ON;`.
Jeg har lagt til dette forran alle `INSERT`, `UPDATE`, og `DELETE` spørringene som berører kolonner med foreign key constraints, jeg har utelat spørringer som ikke berører slike kolonner (f.eks `INSERT` i `account`, `UPDATE set = name` på `shopping_list`).

Mens jeg lagde muligheten for sortering bestemte jeg meg for å lage funksjonene `accountHasAccessToShoppingLists` / `accountHasAccessToShoppingListItems` for å sjekke om brukeren hadde tilgang til en liste med id-er istedenfor å måtte gjøre en for-loop med `accountHasAccessToShoppingList` / `accountHasAccessToShoppingListItem` slik at jeg bare trengte å kjøre en SQL-spørring istedenfor flere.

Mitt første utkast til dette var

```sql
SELECT COUNT(*) = ?
FROM shopping_list
WHERE id IN ($shoppingListIdsPlaceholders) AND account = ?;
```

Men denne virket ikke, etter en stund oppdaget jeg at det er fordi `COUNT(*)` blir et nummer og parameteret jeg sette inn med `?` blir en string og `=`-operatoren sammenligner også typene.
Jeg fant ut at jeg kunne fikse dette med å endre `?` til `CAST(? AS INTEGER)` (altså gjøre parameteret mitt om til ett nummer) slik at dem begge har samme type.
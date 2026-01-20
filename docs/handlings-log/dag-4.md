# Handlings logg dag 4 (20.01.2026)

Jeg valgte å sette opp to nye tabeller i databasen for å lagre sorteringen brukeren skaper `shopping_list_order` og `shopping_list_item_order`.
Et alternativ ville vært å ha en tabell `order` og de to andre tabellene kunne ha vært mange til mange tabeller som refererte mellom `order` og `shopping_list` / `shopping_list_item`, men jeg følte det ville være mer komplekst for en liten fordel dersom man trengte flere sorterbare lister.
Jeg tokk også anledningen til å rydde opp i de ekisterende tabell definisjonene.
Jeg la til `DEFAULT FALSE` på `bought`-kolonnen slik at jeg ikke trenger å oppgi verdien i `INSERT`-spørringer.
Jeg ryddet opp i bruken min av `shoppinglistitem` vs `shopping_list_item` der jeg har tidligere har brukt en blanding av `shoppingListItem` og `shoppinglistItem` i JS og PHP kode (og tilsvarende for `shoppinglist`).
# language: pl

@transaction
Potrzeba biznesowa: Wycofanie aktywów
    W celu możliwości wycofania aktywów z portfela inwestycyjnego
    Jako użytkownik
    Chcę usunąć aktywa z portfela inwestycyjnego

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam 1000 PLN w portfelu inwestycyjnym

    @api
    Scenariusz: Wycofanie aktywów
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "withdraw"
        I wybieram do wypłaty 900 PLN
        I za transakcję płacę 0.99 PLN prowizji
        I podaję datę oraz czas zawarcia transakcji
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I widzę, że wycofałem aktywa o kwocie 900 PLN brutto oraz 899.01 PLN netto
        I posiadam teraz 99.01 PLN w portfelu inwestycyjnym

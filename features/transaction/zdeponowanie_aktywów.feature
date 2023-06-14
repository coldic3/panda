# language: pl

@transaction
Potrzeba biznesowa: Zdeponowanie aktywów
    W celu możliwości dodania aktywów do portfela inwestycyjnego
    Jako użytkownik
    Chcę dodać środki do wybranego aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "ACM" o nazwie "Acme Corporation"
        * posiadam 1000 PLN w portfelu inwestycyjnym

    @api
    Scenariusz: Zdeponowanie aktywów
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "deposit"
        I wybieram do zdeponowania 3500 PLN
        I za transakcję płacę 27.11 PLN prowizji
        I podaję datę oraz czas zawarcia transakcji
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I widzę, że zdeponowałem 3472.89 PLN brutto oraz 3500 PLN netto
        I posiadam teraz 4472.89 PLN w portfelu inwestycyjnym

# language: pl

@transaction
Potrzeba biznesowa: Sprzedaż aktywów
    W celu możliwości sprzedaży aktywów
    Jako użytkownik
    Chcę wymienić jedne aktywa na inne

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny w walucie "PLN"
        * posiadam 1000 PLN w portfelu inwestycyjnym
        * posiadam 10 akcji spółki "ACM" o nazwie "Acme Corporation"
        * kurs dla pary "PLN/ACM" w dniu "2023-04-03" wyniósł 13.5748

    @api
    Scenariusz: Sprzedaż aktywów
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "bid"
        I wybieram do sprzedaży 4 akcje spółki "ACM"
        I cena sprzedaży akcji wynosi 100 PLN
        I za transakcję płacę 5 PLN prowizji
        I podaję datę oraz czas zawarcia transakcji "2023-04-03 21:16:43"
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I widzę, że sprzedałem jedną akcję za 26.25 PLN brutto oraz 25 PLN netto
        I posiadam teraz 1095 PLN w portfelu inwestycyjnym
        I posiadam teraz 6 akcji spółki "ACM"

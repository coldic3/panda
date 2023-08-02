# language: pl

@transaction
Potrzeba biznesowa: Zakup aktywów
    W celu możliwości zakupu aktywów
    Jako użytkownik
    Chcę wymienić jedne aktywa na inne

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny w walucie "PLN"
        * posiadam 1000 PLN w portfelu inwestycyjnym
        * posiadam 10 akcji spółki "ACM" o nazwie "Acme Corporation"
        * kurs dla pary "PLN/ACM" w dniu "2023-04-03" wyniósł 13.5748

    @api
    Scenariusz: Zakup aktywów
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "ask"
        I wybieram do zakupu 20 akcji spółki "ACM"
        I wybieram do zapłaty 500 PLN
        I za transakcję płacę 5 PLN prowizji
        I podaję datę oraz czas zawarcia transakcji "2023-04-03 21:16:43"
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I widzę, że zapłaciłem za jedną akcję 25.25 PLN brutto oraz 25 PLN netto
        I posiadam teraz 495 PLN w portfelu inwestycyjnym
        I posiadam teraz 30 akcji spółki "ACM"

# language: pl

@transaction
Potrzeba biznesowa: Pobranie opłaty
    W celu zapłacenia podatków
    Jako użytkownik
    Chcę mieć możliwość pobrania opłaty

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny w walucie "PLN"
        * posiadam 1000 PLN w portfelu inwestycyjnym
        * posiadam 10 akcji spółki "ACM" o nazwie "Acme Corporation"

    @api
    Scenariusz: Pobranie opłaty
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "fee"
        I dodaję opłatę w wysokości 456.74 PLN
        I podaję datę oraz czas zawarcia transakcji "2023-04-03 21:16:43"
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I posiadam teraz 543.26 PLN w portfelu inwestycyjnym
        I posiadam teraz 10 akcji spółki "ACM"

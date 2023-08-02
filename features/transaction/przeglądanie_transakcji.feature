# language: pl

@transaction
Potrzeba biznesowa: Przeglądanie transakcji
    W celu sprawdzenia jakie transakcje miały miejsce w przeszłości
    Jako użytkownik
    Chcę mieć możliwość wyświetlenia historycznej listy transakcji

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny w walucie "PLN"
        * posiadam aktywo "PLN" o nazwie "Polski Złoty"
        * posiadam aktywo "ACM" o nazwie "ACME Inc."
        * posiadam aktywo "EXT" o nazwie "Example Tech"
        * zdeponowałem 1000 PLN w dniu "2023-03-01 12:00:00"
        * kupiłem 10 akcji spółki "ACM" w dniu "2023-03-01 12:30:00" za 456.74 PLN
        * platforma inwestycyjna pobrała opłatę w wysokości 5 PLN w dniu "2023-04-03 00:00:00"
        * sprzedałem 5 akcji spółki "ACM" w dniu "2023-04-07 14:00:00" za 317 PLN płacąc przy tym 6.78 PLN prowizji
        * wypłaciłem z platformy inwestycyjnej 300 PLN w dniu "2023-04-07 14:10:00" płacąc przy tym 2 PLN prowizji
        * kupiłem 7 akcji spółki "EXT" w dniu "2023-04-27 10:50:00" za 411.31 PLN
        * kupiłem 1 akcję spółki "EXT" w dniu "2023-04-27 11:00:00" za 1 akcję spółki "ACM"

    @api
    Scenariusz: Przeglądanie transakcji
        Gdy wyświetlam historię transakcji
        Wtedy widzę 7 transakcji
        I na pierwszej pozycji jest transakcja zakupu 1 akcji spółki "EXT" za 1 akcję spółki "ACM"
        I transakcja ta została wykonana w dniu "2023-04-27 11:00:00"
        I na drugiej pozycji jest transakcja zakupu 7 akcji spółki "EXT" za 411.31 PLN
        I transakcja ta została wykonana w dniu "2023-04-27 10:50:00"
        I na trzeciej pozycji jest transakcja wypłaty 300 PLN
        I transakcja ta została wykonana w dniu "2023-04-07 14:10:00"
        I za tę transakcję zapłacono 2 PLN prowizji
        I na czwartej pozycji jest transakcja sprzedaży 5 akcji spółki "ACM" za 317 PLN
        I transakcja ta została wykonana w dniu "2023-04-07 14:00:00"
        I za tę transakcję zapłacono 6.78 PLN prowizji
        I na piątej pozycji jest transakcja pobrania opłaty 5 PLN
        I transakcja ta została wykonana w dniu "2023-04-03 00:00:00"
        I na szóstej pozycji jest transakcja zakupu 10 akcji spółki "ACM" za 456.74 PLN
        I transakcja ta została wykonana w dniu "2023-03-01 12:30:00"
        I na siódmej pozycji jest transakcja depozytu 1000 PLN
        I transakcja ta została wykonana w dniu "2023-03-01 12:00:00"

    @api
    Scenariusz: Przeglądanie transakcji z wybranym aktywem z którego nastąpiła wymiana
        Gdy wyświetlam historię transakcji
        I wybieram filtrowanie po aktywie "ACM" z którego nastąpiła wymiana
        Wtedy widzę 2 transakcje
        I na pierwszej pozycji jest transakcja zakupu 1 akcji spółki "EXT" za 1 akcję spółki "ACM"
        I transakcja ta została wykonana w dniu "2023-04-27 11:00:00"
        I na drugiej pozycji jest transakcja sprzedaży 5 akcji spółki "ACM" za 317 PLN
        I transakcja ta została wykonana w dniu "2023-04-07 14:00:00"
        I za tę transakcję zapłacono 6.78 PLN prowizji

    @api
    Scenariusz: Przeglądanie transakcji z wybranym aktywem na który nastąpiła wymiana
        Gdy wyświetlam historię transakcji
        I wybieram filtrowanie po aktywie "ACM" na który nastąpiła wymiana
        Wtedy widzę 1 transakcję
        I na pierwszej pozycji jest transakcja zakupu 10 akcji spółki "ACM" za 456.74 PLN
        I transakcja ta została wykonana w dniu "2023-03-01 12:30:00"

    @api
    Scenariusz: Przeglądanie transakcji wykonanych w konkretnym przedziale czasowym
        Gdy wyświetlam historię transakcji
        I wybieram filtrowanie po dacie dokonania transakcji od "2023-04-01 00:00:00"
        I wybieram filtrowanie po dacie dokonania transakcji do "2023-04-15 23:59:59"
        Wtedy widzę 3 transakcje
        I na pierwszej pozycji jest transakcja wypłaty 300 PLN
        I transakcja ta została wykonana w dniu "2023-04-07 14:10:00"
        I za tę transakcję zapłacono 2 PLN prowizji
        I na drugiej pozycji jest transakcja sprzedaży 5 akcji spółki "ACM" za 317 PLN
        I transakcja ta została wykonana w dniu "2023-04-07 14:00:00"
        I za tę transakcję zapłacono 6.78 PLN prowizji
        I na trzeciej pozycji jest transakcja pobrania opłaty 5 PLN
        I transakcja ta została wykonana w dniu "2023-04-03 00:00:00"

# language: pl

@report
Potrzeba biznesowa: Generowanie raportów udziału aktywów w portfelu
    W celu zapoznania się z udziałem aktywów w portfelu inwestycyjnym
    Jako użytkownik
    Chcę mieć możliwość wygenerowania raportu na temat udziału aktywów

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny o nazwie "Mój portfel" w walucie "PLN"
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
        * kurs dla pary "PLN/ACM" w dniu "2023-04-07" wyniósł 7978.1401
        * kurs dla pary "PLN/EXT" w dniu "2023-04-07" wyniósł 14911.0651
        * kurs dla pary "PLN/ACM" w dniu "2023-04-28" wyniósł 8411.47
        * kurs dla pary "PLN/EXT" w dniu "2023-04-28" wyniósł 14538.3853

    @api
    Scenariusz: Generowanie raportu udziału aktywów w portfelu
        Gdy konfiguruję raport
        I podaję nazwę raportu "Testowy raport udziału aktywów w portfelu"
        I podaję portfel "Mój portfel"
        I podaję typ raportu "allocation"
        I podaję datę "2023-04-28 15:00:00"
        I zatwierdzam wprowadzone dane
        Wtedy otrzymuję raport udziału aktywów
        I raport udziału aktywów zawiera:
            | ticker | quantity | value  | share  |
            | ACM    | 4        | 33646  | 20.58% |
            | EXT    | 8        | 116307 | 71.15% |
            | PLN    | 13517    | 13517  | 8.27%  |

    @api
    Scenariusz: Generowanie raportu udziału aktywów w portfelu
        Gdy konfiguruję raport
        I podaję nazwę raportu "Testowy raport udziału aktywów w portfelu"
        I podaję portfel "Mój portfel"
        I podaję typ raportu "allocation"
        I podaję datę "2023-04-07 14:09:59"
        I zatwierdzam wprowadzone dane
        Wtedy otrzymuję raport udziału aktywów
        I raport udziału aktywów zawiera:
            | ticker | quantity | value  | share  |
            | ACM    | 5        | 39891  | 31.98% |
            | PLN    | 84848    | 84848  | 68.02% |

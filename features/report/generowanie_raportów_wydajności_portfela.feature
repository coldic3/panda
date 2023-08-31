# language: pl

Potrzeba biznesowa: Generowanie raportów wydajności portfela
    W celu zbadania wydajności portfela inwestycyjnego
    Jako użytkownik
    Chcę mieć możliwość wygenerowania raportu na temat wydajności

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
        * kurs dla pary "PLN/ACM" w dniu "2023-02-19" wyniósł 4567.4881
        * kurs dla pary "PLN/EXT" w dniu "2023-02-19" wyniósł 1458.01
        * kurs dla pary "PLN/ACM" w dniu "2023-04-07" wyniósł 4981.9457
        * kurs dla pary "PLN/EXT" w dniu "2023-04-07" wyniósł 1451.799
        * kurs dla pary "PLN/ACM" w dniu "2023-04-30" wyniósł 4394.487
        * kurs dla pary "PLN/EXT" w dniu "2023-04-30" wyniósł 1227.5

    @api
    Scenariusz: Generowanie raportu wydajności portfela z wartością początkową
        Gdy generuję raport wydajności w portfelu dla przedziału dat od "2023-04-07 14:00:00" do "2023-04-30 23:59:59"
        Wtedy otrzymuję raport wydajności
        I raport wydajności zawiera:
            | wartość początkowa | wartość końcowa | zysk/strata | stopa zwrotu |
            | 109758             | 40915           | -68843      | -62.7%       |

    @api
    Scenariusz: Generowanie raportu wydajności portfela bez wartości początkowej
        Gdy generuję raport wydajności w portfelu dla przedziału dat od "2023-02-19 00:00:00" do "2023-04-30 23:59:59"
        Wtedy otrzymuję raport wydajności
        I raport wydajności zawiera:
            | wartość początkowa | wartość końcowa | zysk/strata | stopa zwrotu |
            | 0                  | 40915           | 40915       | 4091.5%      |

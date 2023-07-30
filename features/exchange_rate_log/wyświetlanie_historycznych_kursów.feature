# language: pl

@exchange_rate_log
Potrzeba biznesowa: Wyświetlanie historycznych kursów
    W celu śledzenia zmiany kursów dla wybranych aktywów w czasie
    Jako użytkownik
    Chcę wyświetlać historyczne kursy

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * kurs dla pary "USD/PLN" w dniu "2023-07-13" wyniósł 3.9567
        * kurs dla pary "USD/PLN" w dniu "2023-07-14" wyniósł 3.9631
        * kurs dla pary "USD/PLN" w dniu "2023-07-17" wyniósł 3.9505
        * kurs dla pary "USD/PLN" w dniu "2023-07-18" wyniósł 3.9603
        * kurs dla pary "USD/PLN" w dniu "2023-07-19" wyniósł 3.9731
        * kurs dla pary "USD/PLN" w dniu "2023-07-20" wyniósł 3.9981
        * kurs dla pary "USD/PLN" w dniu "2023-07-21" wyniósł 4.0061
        * kurs dla pary "USD/EUR" w dniu "2023-07-13" wyniósł 0.8906
        * kurs dla pary "USD/EUR" w dniu "2023-07-14" wyniósł 0.8904
        * kurs dla pary "USD/EUR" w dniu "2023-07-17" wyniósł 0.8898
        * kurs dla pary "USD/EUR" w dniu "2023-07-18" wyniósł 0.8906
        * kurs dla pary "USD/EUR" w dniu "2023-07-19" wyniósł 0.8927
        * kurs dla pary "USD/EUR" w dniu "2023-07-20" wyniósł 0.8983
        * kurs dla pary "USD/EUR" w dniu "2023-07-21" wyniósł 0.8989

    @api
    Scenariusz: Wyświetlanie historycznych kursów dla pary aktywów
        Gdy wyświetlam historyczne kursy dla pary "USD/PLN"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę 7 historycznych kursów
        I na pierwszej pozycji jest kurs z dnia "2023-07-21" o wartości 4.0061
        I na ostatniej pozycji jest kurs z dnia "2023-07-13" o wartości 3.9567

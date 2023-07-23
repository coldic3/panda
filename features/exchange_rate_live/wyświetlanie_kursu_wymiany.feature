# language: pl

@exchange_rate_live
Potrzeba biznesowa: Wyświetlanie kursów wymiany
    W celu śledzenia zmiany kursów dla wybranych aktywów
    Jako użytkownik
    Chcę wyświetlać kursy wymiany

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "USD" o nazwie "Dolar amerykański"
        * posiadam aktywo "PLN" o nazwie "Polski złoty"
        * posiadam aktywo "EUR" o nazwie "Euro"
        * posiadam aktywo "GBP" o nazwie "Funt brytyjski"
        * posiadam aktywo "CNY" o nazwie "Chiński juan"
        * istnieje kurs wymiany "USD/PLN" na poziomie 4.06
        * istnieje kurs wymiany "EUR/PLN" na poziomie 4.45
        * istnieje kurs wymiany "GBP/USD" na poziomie 1.28

    @api
    Scenariusz: Wyświetlanie kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "USD/PLN"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 4.06

    @api
    Scenariusz: Wyświetlanie odwrotnego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "PLN/USD"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 0.2463

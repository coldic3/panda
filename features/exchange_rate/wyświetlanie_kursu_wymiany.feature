# language: pl

@exchange_rate
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

    @api
    Scenariusz: Wyświetlanie nieistniejącego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "CNY/PLN"
        Wtedy informacje o kursie wymiany nie zostają wyświetlane

    @api-todo
    Scenariusz: Wyświetlanie kursu wymiany dla jednego aktywa
        Gdy wyświetlam kurs wymiany dla pary "USD/USD"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 1

    @api-todo
    Scenariusz: Wyświetlanie krzyżowego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "GBP/PLN"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 5.1968
        # Calculation: GBP/[USD] * [USD]/PLN or [USD]/GBP * PLN/[USD]

    @api-todo
    Scenariusz: Wyświetlanie krzyżowego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "PLN/GBP"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 0.1924
        # Calculation: 1 / (GBP/[USD] * [USD]/PLN) or [USD]/GBP * PLN/[USD]

    @api-todo
    Scenariusz: Wyświetlanie krzyżowego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "USD/EUR"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 0.9124
        # Calculation: USD/[PLN] / EUR/[PLN] or [PLN]/USD / [PLN]/EUR

    @api-todo
    Scenariusz: Wyświetlanie krzyżowego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "EUR/USD"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 1.0960
        # Calculation: 1 / (USD/[PLN] / EUR/[PLN]) or [PLN]/USD / [PLN]/EUR

    @api-todo
    Scenariusz: Wyświetlanie złożonego krzyżowego kursu wymiany dla pary aktywów
        Gdy wyświetlam kurs wymiany dla pary "GBP/EUR"
        Wtedy informacje o kursie wymiany zostają wyświetlane
        I widzę, że kurs wymiany wynosi 1.168
        # Calculation: GBP/[USD] * (USD/[PLN] / EUR/[PLN]) = GBP/[USD] * [USD]/EUR

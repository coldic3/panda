# language: pl

@exchange_rate_live
Potrzeba biznesowa: Zmiana kursu wymiany
    W celu korzystania śledzenia kursu wymiany aktywów
    Jako użytkownik
    Chcę tworzyć kursy wymiany dla wybranych przez siebie aktywów

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "USD" o nazwie "Dolar amerykański"
        * posiadam aktywo "PLN" o nazwie "Polski złoty"
        * istnieje kurs wymiany "USD/PLN" na poziomie 4.06

    @api
    Scenariusz: Zmiana kursu wymiany
        Gdy modyfikuję kurs wymiany dla pary "USD/PLN"
        I podaję nowy kurs "4.48"
        I zatwierdzam wprowadzone dane
        Wtedy edycja kursu wymiany kończy się sukcesem
        I odwrotny kurs wymiany został uaktualniony i wynosi 0.2232

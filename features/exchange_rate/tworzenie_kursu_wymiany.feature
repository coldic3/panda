# language: pl

@exchange_rate
Potrzeba biznesowa: Tworzenie kursu wymiany
    W celu posiadania informacji o kursie wymiany między dwoma aktywami
    Jako użytkownik
    Chcę tworzyć kursy wymiany dla wybranych przez siebie aktywów

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "USD" o nazwie "Dolar amerykański"
        * posiadam aktywo "PLN" o nazwie "Polski złoty"

    @api
    Scenariusz: Tworzenie kursu wymiany
        Gdy tworzę nowy kurs wymiany
        I wybieram aktywo bazowe "USD"
        I wybieram aktywo kwotowane "PLN"
        I wybieram kurs 4.06
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie kursu wymiany kończy się sukcesem
        I kurs wymiany został dodany do listy kursów wymiany
        I odwrotny kurs wymiany w wysokości 0.2463 został dodany do listy kursów wymiany

    @api
    Scenariusz: Tworzenie kursu wymiany dla aktywów, które już posiadają kurs wymiany
        Zakładając, że istnieje kurs wymiany "USD/PLN" na poziomie 4.06
        Gdy tworzę nowy kurs wymiany
        I wybieram aktywo bazowe "USD"
        I wybieram aktywo kwotowane "PLN"
        I wybieram kurs 4.73
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie kursu wymiany kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że kurs wymiany już istnieje

    @api
    Scenariusz: Tworzenie odwrotnego kursu wymiany wobec kursu dla aktywów, które już posiadają kurs wymiany
        Zakładając, że istnieje kurs wymiany "USD/PLN" na poziomie 4.06
        Gdy tworzę nowy kurs wymiany
        I wybieram aktywo bazowe "PLN"
        I wybieram aktywo kwotowane "USD"
        I wybieram kurs 0.2463
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie kursu wymiany kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że kurs wymiany już istnieje

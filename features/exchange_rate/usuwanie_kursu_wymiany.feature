# language: pl

@exchange_rate
Potrzeba biznesowa: Usuwanie kursów wymiany
    W celu posiadania tylko potrzebnych kursów wymiany
    Jako użytkownik
    Chcę usuwać niepotrzebne kursy wymiany

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "USD" o nazwie "Dolar amerykański"
        * posiadam aktywo "PLN" o nazwie "Polski złoty"
        * istnieje kurs wymiany "USD/PLN" na poziomie 4.06

    @api
    Scenariusz: Usuwanie kursu wymiany
        Gdy usuwam kurs wymiany dla pary "USD/PLN"
        Wtedy usuwanie kursu wymiany kończy się sukcesem
        I kurs wymiany został usunięty z listy kursów wymiany

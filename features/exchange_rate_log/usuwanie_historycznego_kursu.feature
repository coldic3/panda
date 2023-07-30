# language: pl

@exchange_rate_log
Potrzeba biznesowa: Usuwanie historycznych kursów
    W celu posiadania tylko poprawnych kursów historycznych
    Jako użytkownik
    Chcę usuwać niepoprawne kursy

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * kurs dla pary "USD/PLN" w dniu "2023-07-13" wyniósł 3.9567
        * kurs dla pary "USD/PLN" w dniu "2023-07-14" wyniósł 5.9631
        * kurs dla pary "USD/PLN" w dniu "2023-07-17" wyniósł 3.9505

    @api
    Scenariusz: Usuwanie historycznego kursu
        Gdy usuwam kurs wymiany dla pary "USD/PLN" w dniu "2023-07-14"
        Wtedy usuwanie kursu wymiany kończy się sukcesem
        I kurs wymiany został usunięty z listy historycznych kursów

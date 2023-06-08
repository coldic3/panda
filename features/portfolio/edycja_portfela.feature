# language: pl

@portfolio
Potrzeba biznesowa: Modyfikacja portfeli inwestycyjnych
    W celu zmiany danych portfela inwestycyjnego
    Jako użytkownik
    Chcę zmieniać informacje o portfelach inwestycyjnych

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel o nazwie "Inwestycje krótkoterminowe"

    @api
    Scenariusz: Zmiana nazwy portfela
        Gdy modyfikuję portfel "Inwestycje krótkoterminowe"
        I podaję nazwę "Spekulacje"
        I zatwierdzam wprowadzone dane
        Wtedy edycja portfela kończy się sukcesem
        I widzę, że portfel ma nazwę "Spekulacje"

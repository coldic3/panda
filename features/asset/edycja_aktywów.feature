# language: pl

@asset
Potrzeba biznesowa: Modyfikowanie aktywów
    W celu korekty danych w utworzonych aktywach
    Jako użytkownik
    Chcę wprowadzać zmiany w stworzonych przez siebie aktywach

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam aktywo "ACM" o nazwie "Acme Corporation"

    @api
    Scenariusz: Zmiana nazwy aktywa
        Gdy modyfikuję aktywo "ACM"
        I podaję nazwę "Acme Inc."
        I zatwierdzam wprowadzone dane
        Wtedy edycja aktywa kończy się sukcesem
        I aktywo zmienia swoją nazwę na "Acme Inc."

    @api
    Scenariusz: Zmiana tickera aktywa
        Gdy modyfikuję aktywo "ACM"
        I podaję ticker "ACME"
        I zatwierdzam wprowadzone dane
        Wtedy edycja aktywa kończy się sukcesem
        I aktywo zmienia swój ticker na "ACME"

    @api
    Scenariusz: Zmiana aktywa należącego do innego użytkownika
        Zakładając, że istnieje użytkownik "bear@example.com"
        I użytkownik "bear@example.com" posiada aktywo "XYZ" o nazwie "XYZ Corp."
        Gdy modyfikuję aktywo "XYZ"
        I podaję nazwę "XYZ Inc."
        I zatwierdzam wprowadzone dane
        Wtedy edycja aktywa kończy się niepowodzeniem

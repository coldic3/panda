# language: pl

@asset
Potrzeba biznesowa: Zmiana tickera aktywów
    Ponieważ na giełdzie zmienił się ticker aktywa
    Jako użytkownik
    Chcę uaktualnić ticker

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "ACM" o nazwie "Acme Corporation"

    @api
    Scenariusz: Zmiana tickera aktywa
        Gdy zmieniam ticker aktywa "ACM"
        I podaję nowy ticker "ACME"
        I zatwierdzam wprowadzone dane
        Wtedy zmiana tickera kończy się sukcesem
        I aktywo zmienia swój ticker na "ACME"

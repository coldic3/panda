# language: pl

@asset
Potrzeba biznesowa: Tworzenie aktywów
    W celu korzystania z dowolnych aktywów
    Jako użytkownik
    Chcę tworzyć wybrane przez siebie aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny

    @api
    Scenariusz: Tworzenie nowego aktywa
        Gdy tworzę nowe aktywo
        I podaję ticker "AAPL"
        I podaję nazwę "Apple Inc."
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie aktywa kończy się sukcesem
        I aktywo zostało dodane do listy aktywów

    @api
    Scenariusz: Tworzenie aktywa z już istniejącym tickerem
        Zakładając, że posiadam aktywo "ACM" o nazwie "Acme Corporation"
        Gdy tworzę nowe aktywo
        I podaję ticker "ACM"
        I podaję nazwę "Acme Corp."
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie aktywa kończy się niepowodzeniem

    @api
    Scenariusz: Tworzenie aktywa z pustymi danymi
        Gdy tworzę nowe aktywo
        I nie podaję żadnych danych
        Wtedy dodawanie aktywa kończy się niepowodzeniem

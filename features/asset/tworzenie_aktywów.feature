# language: pl

@asset
Potrzeba biznesowa: Tworzenie aktywów
    W celu korzystania z dowolnych aktywów
    Jako użytkownik
    Chcę tworzyć wybrane przez siebie aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"

    @api
    Scenariusz: Tworzenie nowego aktywa
        Gdy tworzę nowe aktywo
        I podaję ticker "AAPL"
        I podaję nazwę "Apple Inc."
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie aktywa kończy się sukcesem
        I aktywo zostało dodane do listy aktywów

    @api
    Scenariusz: Tworzenie aktywa z pustymi danymi
        Gdy tworzę nowe aktywo
        I nie podaję żadnych danych
        Wtedy dodawanie aktywa kończy się niepowodzeniem

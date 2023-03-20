# language: pl

Potrzeba biznesowa: Tworzenie aktywów
    W celu korzystania z dowolnych aktywów
    Jako użytkownik
    Chcę tworzyć wybrane przez siebie aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"

    Scenariusz: Tworzenie nowego aktywa
        Gdy tworzę nowe aktywo
        I podaję ticker "AAPL"
        I podaję nazwę "Apple Inc."
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie aktywa kończy się sukcesem
        I aktywo zostaje dodane do listy aktywów

    Scenariusz: Tworzenie aktywa z pustymi danymi
        Gdy tworzę nowe aktywo
        I nie podaję żadnych danych
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie aktywa kończy się niepowodzeniem
        I aktywo nie zostaje dodane do listy aktywów

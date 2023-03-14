# language: pl

@demo
Właściwość:
    W celu udowodnienia, że rozszerzenie Behat Symfony jest poprawnie zainstalowane
    Jako użytkownik
    Chcę mieć scenariusz demonstracyjny

    @app
    Scenariusz: Otrzymanie odpowiedzi od jądra Symfony
        Gdy wysyłam żądanie do "/"
        Wtedy powinienem otrzymać odpowiedź

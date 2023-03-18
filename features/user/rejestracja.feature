# language: pl

@user
Potrzeba biznesowa: Tworzenie konta w systemie
    W celu możliwości korzystania ze wszystkich funkcji systemu
    Jako gość
    Chcę założyć konto w systemie

    @api
    Scenariusz: Tworzenie nowego konta
        Gdy tworzę nowe konto
        I podaję adres email "panda@example.com"
        I podaję hasło "I<3BambooShoots"
        I zatwierdzam wprowadzone dane
        Wtedy rejestracja kończy się sukcesem
        Ale nie widzę żadnych dodatkowych informacji
        Ale konto zostało utworzone w systemie

    @api
    Scenariusz: Tworzenie konta, które zostało już utworzone
        Zakładając, że użytkownik o adresie email "panda@example.com" już istnieje
        Gdy tworzę nowe konto
        I podaję adres email "panda@example.com"
        I podaję hasło "I<3BambooShoots"
        I zatwierdzam wprowadzone dane
        Wtedy rejestracja kończy się sukcesem
        Ale nie widzę żadnych dodatkowych informacji
        Ale kolejne konto nie zostało utworzone w systemie

    @api
    Scenariusz: Tworzenie konta z niepoprawnymi danymi
        Gdy tworzę nowe konto
        I podaję adres email "panda"
        I podaję hasło "bamboo"
        I zatwierdzam wprowadzone dane
        Wtedy rejestracja kończy się niepowodzeniem
        I widzę pojedynczy komunikat o niepoprawnym adresie email
        I widzę pojedynczy komunikat o niepoprawnym haśle
        I konto nie zostało utworzone w systemie

    @api
    Scenariusz: Tworzenie konta z pustymi danymi
        Gdy tworzę nowe konto
        I nie podaję żadnych danych
        Wtedy rejestracja kończy się niepowodzeniem
        I widzę pojedynczy komunikat o pustym adresie email
        I widzę pojedynczy komunikat o pustym haśle

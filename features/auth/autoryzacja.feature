# language: pl

@auth
Potrzeba biznesowa: Autoryzacja użytkownika
    W celu możliwości uzyskiwania dostępu do zasobów użytkownika
    Jako gość
    Chcę dokonać autoryzacji

    @api
    Scenariusz: Poprawna autoryzacja użytkownika
        Zakładając, że istnieje użytkownik "panda@example.com" z hasłem "I<3BambooShoots"
        Gdy rozpoczynam autoryzację
        I podaję adres email "panda@example.com"
        I podaję hasło "I<3BambooShoots"
        I zatwierdzam wprowadzone dane
        Wtedy autoryzacja kończy się sukcesem
        I otrzymuję token autoryzacyjny

    @api
    Scenariusz: Niepoprawna autoryzacja użytkownika poprzez podanie niepoprawnego hasła
        Zakładając, że istnieje użytkownik "panda@example.com" z hasłem "I<3BambooShoots"
        Gdy rozpoczynam autoryzację
        I podaję adres email "panda@example.com"
        I podaję hasło "IHateBambooShoots"
        I zatwierdzam wprowadzone dane
        Wtedy autoryzacja kończy się niepowodzeniem
        I nie otrzymuję tokena autoryzacyjnego

    @api
    Scenariusz: Niepoprawna autoryzacja użytkownika poprzez podanie nieistniejącego adresu email
        Zakładając, że istnieje użytkownik "panda@example.com" z hasłem "I<3BambooShoots"
        Gdy rozpoczynam autoryzację
        I podaję adres email "bear@example.com"
        I podaję hasło "I<3BambooShoots"
        I zatwierdzam wprowadzone dane
        Wtedy autoryzacja kończy się niepowodzeniem
        I nie otrzymuję tokena autoryzacyjnego

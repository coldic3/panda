# language: pl

@user
Potrzeba biznesowa: Wyświetlanie informacji o użytkowniku
    W celu wyświetlenia informacji na temat swojego konta
    Jako użytkownik
    Chcę mieć dostęp do informacji o swoim koncie

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * znam swój identyfikator użytkownika

    @api
    Scenariusz: Wyświetlanie informacji o swoim koncie
        Gdy wyświetlam informacje o koncie "panda@example.com"
        Wtedy informacje o użytkowniku zostają wyświetlone

    @api
    Scenariusz: Wyświetlanie informacji o innym koncie
        Zakładając, że istnieje użytkownik "bear@example.com"
        Gdy wyświetlam informacje o koncie "bear@example.com"
        Wtedy informacje o użytkowniku nie zostają wyświetlone



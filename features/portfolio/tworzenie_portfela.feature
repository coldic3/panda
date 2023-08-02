# language: pl

@portfolio
Potrzeba biznesowa: Tworzenie portfela
    W celu przejrzystego zarządzania aktywami
    Jako użytkownik
    Chcę tworzyć portfele inwestycyjne gromadzące posiadane aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"

    @api
    Scenariusz: Tworzenie pierwszego portfela
        Gdy tworzę nowy portfel
        I podaję nazwę "Inwestycje długoterminowe"
        I podaję ticker głównego aktywa "PLN"
        I podaję nazwę głównego aktywa "Polski złoty"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie portfela kończy się sukcesem
        I widzę, że portfel jest portfelem domyślnym

    @api
    Scenariusz: Tworzenie kolejnego portfela
        Zakładając, że posiadam już domyślny portfel "Inwestycje długoterminowe"
        Gdy tworzę nowy portfel
        I podaję nazwę "Inwestycje krótkoterminowe"
        I podaję ticker głównego aktywa "PLN"
        I podaję nazwę głównego aktywa "Polski złoty"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie portfela kończy się sukcesem
        I widzę, że portfel nie jest portfelem domyślnym

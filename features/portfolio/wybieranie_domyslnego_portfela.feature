# language: pl

@portfolio
Potrzeba biznesowa: Wybieranie domyślnego portfela
    W celu zmiany portfela domyślnego
    Jako użytkownik
    Chcę tworzyć portfele inwestycyjne gromadzące posiadane aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam domyślny portfel o nazwie "Inwestycje długoterminowe"
        * posiadam portfel o nazwie "Inwestycje krótkoterminowe"

    @api
    Scenariusz: Zmiana portfela domyślnego
        Gdy wybieram portfel "Inwestycje krótkoterminowe" jako domyślny
        I zatwierdzam wprowadzone dane
        Wtedy zmiana portfela domyślnego kończy się sukcesem
        I widzę, że portfel jest portfelem domyślnym
        I widzę, że portfel "Inwestycje długoterminowe" nie jest już portfelem domyślnym

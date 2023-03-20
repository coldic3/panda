# language: pl

Potrzeba biznesowa: Wyświetlanie aktywów
    W celu zapoznania się z możliwymi do wyboru aktywami
    Jako użytkownik
    Chcę wyświetlać aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam aktywo "ACM" o nazwie "Acme Corp."
        * posiadam aktywo "EXT" o nazwie "Extra Tower Inc."

    Scenariusz: Wyświetlanie pojedynczego aktywa
        Gdy wyświetlam aktywo "ACM"
        Wtedy informacje o aktywie zostają wyświetlane

    Scenariusz: Wyświetlanie listy aktywów
        Gdy wyświetlam listę aktywów
        Wtedy informacje o aktywach zostają wyświetlone

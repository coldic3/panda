# language: pl

@asset
Potrzeba biznesowa: Wyświetlanie aktywów
    W celu zapoznania się z możliwymi do wyboru aktywami
    Jako użytkownik
    Chcę wyświetlać aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam aktywo "ACM" o nazwie "Acme Corp."
        * posiadam aktywo "EXT" o nazwie "Extra Tower Inc."

    @api
    Scenariusz: Wyświetlanie pojedynczego aktywa
        Gdy wyświetlam aktywo "ACM"
        Wtedy informacje o aktywie zostają wyświetlane

    @api
    Scenariusz: Wyświetlanie listy aktywów
        Gdy wyświetlam listę aktywów
        Wtedy informacje o aktywach zostają wyświetlone

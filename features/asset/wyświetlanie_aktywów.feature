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
        * istnieje użytkownik "bear@example.com"
        * użytkownik "bear@example.com" posiada aktywo "XYZ" o nazwie "XYZ Corp."

    @api
    Scenariusz: Wyświetlanie pojedynczego aktywa
        Gdy wyświetlam aktywo "ACM"
        Wtedy informacje o aktywie zostają wyświetlane

    @api
    Scenariusz: Wyświetlanie aktywa należącego do innego użytkownika
        Gdy wyświetlam aktywo "XYZ"
        Wtedy informacje o aktywie nie zostają wyświetlane

    @api
    Scenariusz: Wyświetlanie listy aktywów
        Gdy wyświetlam listę aktywów
        Wtedy informacje o aktywach zostają wyświetlone

# language: pl

@asset
Potrzeba biznesowa: Usuwanie aktywów
    W celu posiadania aktualnej i przydatnej listy aktywów
    Jako użytkownik
    Chcę usuwać niepotrzebne aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel inwestycyjny
        * posiadam aktywo "ACM" o nazwie "Acme Corporation"

    @api
    Scenariusz: Usuwanie aktywa
        Gdy usuwam aktywo "ACM"
        Wtedy usuwanie aktywa kończy się sukcesem
        I aktywo zostało usunięte z listy aktywów

    @api
    Scenariusz: Usuwanie aktywa należącego do innego użytkownika
        Zakładając, że istnieje użytkownik "bear@example.com"
        I użytkownik "bear@example.com" posiada aktywo "XYZ" o nazwie "XYZ Corp."
        Gdy usuwam aktywo "XYZ"
        Wtedy usuwanie aktywa kończy się niepowodzeniem

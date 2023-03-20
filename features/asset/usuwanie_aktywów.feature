# language: pl

Potrzeba biznesowa: Usuwanie aktywów
    W celu posiadania aktualnej i przydatnej listy aktywów
    Jako użytkownik
    Chcę usuwać niepotrzebne aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam aktywo "ACM" o nazwie "Acme Corporation"

    Scenariusz: Usuwanie aktywa
        Gdy usuwam aktywo "ACM"
        Wtedy usuwanie aktywa kończy się sukcesem
        I aktywo zostaje usunięte z listy aktywów

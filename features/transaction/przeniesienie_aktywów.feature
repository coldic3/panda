# language: pl

@transaction
Potrzeba biznesowa: Przeniesienie aktywów
    W celu możliwości przeniesienia aktywów do innego brokera
    Jako użytkownik
    Chcę zastąpić aktywa

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam 1000 PLN w portfelu inwestycyjnym
        * posiadam 10 akcji spółki "ACM" o nazwie "Acme Corporation"

    @api
    Scenariusz: Przeniesienie aktywów
        Gdy rozpoczynam nową transakcję
        I wybieram typ transakcji "transfer"
        I wybieram do przeniesienia 5 akcji spółki "ACM"
        I za transakcję płacę 5 PLN prowizji
        I podaję datę oraz czas zawarcia transakcji
        I zatwierdzam transakcję
        Wtedy transakcja kończy się sukcesem
        I posiadam teraz 995 PLN w portfelu inwestycyjnym
        I posiadam teraz 10 akcji spółki "ACM"

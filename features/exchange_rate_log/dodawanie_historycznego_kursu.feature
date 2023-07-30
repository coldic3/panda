# language: pl

@exchange_rate_log
Potrzeba biznesowa: Dodawanie historycznego kursu
    W celu posiadania aktualnej bazy historycznych kursów
    Jako użytkownik
    Chcę dodawać kursy dla wybranego przedziału czasowego

    Założenia:
        * jestem zalogowany jako "panda@example.com"

    @api
    Scenariusz: Dodawanie historycznego kursu
        Gdy dodaję historyczny kurs
        I wybieram ticker bazowy "USD"
        I wybieram ticker kwotowany "PLN"
        I wybieram kurs 4.0061
        I wybieram datę oraz czas od kiedy obowiązuje kurs "2023-07-21 00:00:00"
        I wybieram datę oraz czas do kiedy obowiązuje kurs "2023-07-21 23:59:59"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie historycznego kursu kończy się sukcesem
        I kurs został dodany do listy historycznych kursów

    @api
    Scenariusz: Dodawanie historycznego kursu z datą rozpoczęcia dla już istniejącego kursu w tym przedziale czasowym
        Zakładając, że kurs dla pary "USD/PLN" w dniu "2023-07-13" wyniósł 3.9567
        I kurs dla pary "USD/PLN" w dniu "2023-07-14" wyniósł 3.9631
        I kurs dla pary "USD/PLN" w dniu "2023-07-17" wyniósł 3.9505
        Gdy dodaję historyczny kurs
        I wybieram ticker bazowy "USD"
        I wybieram ticker kwotowany "PLN"
        I wybieram kurs 4.0061
        I wybieram datę oraz czas od kiedy obowiązuje kurs "2023-07-17 00:00:00"
        I wybieram datę oraz czas do kiedy obowiązuje kurs "2023-07-18 23:59:59"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie historycznego kursu kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że dla podanej daty rozpoczęcia obowiązywania kursu istnieje już kurs

    @api
    Scenariusz: Dodawanie historycznego kursu z datą zakończenia dla już istniejącego kursu w tym przedziale czasowym
        Zakładając, że kurs dla pary "USD/PLN" w dniu "2023-07-13" wyniósł 3.9567
        I kurs dla pary "USD/PLN" w dniu "2023-07-14" wyniósł 3.9631
        I kurs dla pary "USD/PLN" w dniu "2023-07-17" wyniósł 3.9505
        Gdy dodaję historyczny kurs
        I wybieram ticker bazowy "USD"
        I wybieram ticker kwotowany "PLN"
        I wybieram kurs 4.0061
        I wybieram datę oraz czas od kiedy obowiązuje kurs "2023-07-16 00:00:00"
        I wybieram datę oraz czas do kiedy obowiązuje kurs "2023-07-17 23:59:59"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie historycznego kursu kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że dla podanej daty zakończenia obowiązywania kursu istnieje już kurs

    @api
    Scenariusz: Dodawanie historycznego kursu z datami dla już istniejącego kursu w tym przedziale czasowym
        Zakładając, że kurs dla pary "USD/PLN" w dniu "2023-07-13" wyniósł 3.9567
        I kurs dla pary "USD/PLN" w dniu "2023-07-14" wyniósł 3.9631
        I kurs dla pary "USD/PLN" w dniu "2023-07-17" wyniósł 3.9505
        Gdy dodaję historyczny kurs
        I wybieram ticker bazowy "USD"
        I wybieram ticker kwotowany "PLN"
        I wybieram kurs 4.0061
        I wybieram datę oraz czas od kiedy obowiązuje kurs "2023-07-16 00:00:00"
        I wybieram datę oraz czas do kiedy obowiązuje kurs "2023-07-18 23:59:59"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie historycznego kursu kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że dla podanej daty rozpoczęcia obowiązywania kursu istnieje już kurs
        I widzę pojedynczy komunikat, że dla podanej daty zakończenia obowiązywania kursu istnieje już kurs

    @api
    Scenariusz: Dodawanie historycznego kursu z datą zakończenia mniejszą niż data rozpoczęcia
        Gdy dodaję historyczny kurs
        I wybieram ticker bazowy "USD"
        I wybieram ticker kwotowany "PLN"
        I wybieram kurs 4.0061
        I wybieram datę oraz czas od kiedy obowiązuje kurs "2023-07-18 00:00:00"
        I wybieram datę oraz czas do kiedy obowiązuje kurs "2023-07-17 23:59:59"
        I zatwierdzam wprowadzone dane
        Wtedy dodawanie historycznego kursu kończy się niepowodzeniem
        I widzę pojedynczy komunikat, że data zakończenia obowiązywania kursu nie może być mniejsza niż data rozpoczęcia

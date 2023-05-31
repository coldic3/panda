# language: pl

@portfolio
Potrzeba biznesowa: Przeglądanie portfela
    W celu sprawdzenia jakie aktywa posiadam w portfelu
    Jako użytkownik
    Chcę mieć możliwość wyświetlenia portfela wraz ze wszystkimi aktywami wchodzącymi w jego skład

    Założenia:
        * jestem zalogowany jako "panda@example.com"
        * posiadam portfel o nazwie "Inwestycje krótkoterminowe"
        * posiadam aktywo "PLN" o nazwie "Polski Złoty" w ilości 1000
        * posiadam aktywo "ACM" o nazwie "ACME Inc." w ilości 100
        * posiadam aktywo "EXT" o nazwie "Example Tech" w ilości 5
        * posiadam aktywo "BTC" o nazwie "Bitcoin" w ilości 1
        * posiadam krótką pozycję na aktywie "SPXS" o nazwie "S&P 500 Short" w ilości 16

    @api
    Scenariusz: Przeglądanie portfela
        Gdy wyświetlam skład portfela
        Wtedy widzę 5 aktywów wchodzących w jego skład
        I widzę aktywo "PLN" o nazwie "Polski Złoty" w ilości 1000
        I widzę aktywo "ACM" o nazwie "ACME Inc." w ilości 100
        I widzę aktywo "EXT" o nazwie "Example Tech" w ilości 5
        I widzę aktywo "BTC" o nazwie "Bitcoin" w ilości 1
        I widzę aktywo "SPXS" o nazwie "S&P 500 Short" w ilości -16

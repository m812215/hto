# Hyppytoimintaorganisaattori

Tämä on kevyt Kohana-frameworkiin perustuva softa, jonka tarkoitus on helpottaa
hyppytoiminnan järjestelyjä kerhossa.

Ohjelmisto tukee kahta lentokonotta. Toinen kone on kommentoitu pois tällä
hetkellä.

# Asennus

- Luo MySQL-tietokanta

- Aja taulujen luontikomennot kantaan

```

mysql -u -p

CREATE DATABASE hto CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE USER 'hto'@'localhost' IDENTIFIED BY 'pass_w0rd'; GRANT ALL PRIVILEGES
ON hto.* TO 'hto'@'localhost' WITH GRANT OPTION;

exit

mysql -u hto -p -h localhost hto < hto.sql

```

- Muokkaa tiedostoa hto/application/config/database.php

- Anna hakemistoille /application/logs ja /application/cache sellaiset
oikeudet, että Apache saa kirjoittaa sinne

- Varmista, että Apache tottelee hakemistokohtaisia htaccess-tiedostoja.
Sovelluksen juuressa on htaccess-tiedosto, joka ohjaa pyynnöt oikeaan paikkaan.

# TODO

Muuttaa konemäärä ja konetunnukset konfigin taakse.

# Kohana PHP Framework, version 3.0 (dev)

This is the current development version of [Kohana](http://kohanaframework.org/).

USE xcurda02;
SET NAMES 'utf8';





DROP TABLE if EXISTS pokladni CASCADE;
DROP TABLE IF EXISTS vstupenka CASCADE;
DROP TABLE IF EXISTS projekce CASCADE;
DROP TABLE IF EXISTS promitaci_sal CASCADE;
DROP TABLE IF EXISTS uzivatel CASCADE;

DROP TABLE IF EXISTS film CASCADE;
DROP TABLE IF EXISTS zakaznik CASCADE;
DROP TABLE IF EXISTS multikino CASCADE;


CREATE TABLE pokladni(
    pokladni_id INT NOT NULL AUTO_INCREMENT,
    jmeno VARCHAR(30) NOT NULL,
    prijmeni VARCHAR(30) NOT NULL,
    foreign_key_multikino INTEGER NOT NULL,
    PRIMARY KEY (pokladni_id)
);


CREATE TABLE multikino(
    kino_id INT NOT NULL AUTO_INCREMENT,
    nazev VARCHAR(30) NOT NULL,
    adresa VARCHAR(60) NOT NULL,
    telefon VARCHAR(13) NOT NULL,
    webove_stranky VARCHAR(30) NOT NULL,
    PRIMARY KEY (kino_id)
);

CREATE TABLE promitaci_sal(
    sal_id INT NOT NULL AUTO_INCREMENT,
    cislo_salu INTEGER NOT NULL,
    pocet_mist INTEGER NOT NULL,
    UNIQUE (cislo_salu),
    foreign_key_multikino INTEGER NOT NULL,
    PRIMARY KEY (sal_id)
);

CREATE TABLE projekce(
    projekce_id INT NOT NULL AUTO_INCREMENT,
    cas VARCHAR(5) NOT NULL,
    datum VARCHAR(10) NOT NULL,
    trzba INTEGER,
    foreign_key_film INTEGER NOT NULL,
    foreign_key_promitaci_sal INTEGER NOT NULL,
    PRIMARY KEY (projekce_id)
);

CREATE TABLE zakaznik(
    zakaznik_id INT NOT NULL AUTO_INCREMENT,
    jmeno VARCHAR(30) NOT NULL,
    prijmeni VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    UNIQUE (email),
    PRIMARY KEY (zakaznik_id)
);

CREATE TABLE vstupenka(
    vstupenka_id INT NOT NULL AUTO_INCREMENT,
    sedadlo INTEGER NOT NULL,
    cena INTEGER NOT NULL,
    zarezervovano NUMERIC(1, 0) NOT NULL check(zarezervovano in (0, 1)),
    UNIQUE (sedadlo),
    foreign_key_zakaznik INTEGER,
    foreign_key_projekce INTEGER NOT NULL,
    PRIMARY KEY (vstupenka_id)
);

CREATE TABLE uzivatel(
    uzivatel_id INT NOT NULL AUTO_INCREMENT,
    login VARCHAR (30) NOT NULL,
    heslo VARCHAR (30) NOT NULL,
    foreign_key_zakaznik INTEGER,
    PRIMARY KEY (uzivatel_id)
);

/*
--Navyseni hodnoty trby projekce pri koupi vstupenky
CREATE OR REPLACE TRIGGER vstupenky
BEFORE INSERT ON vstupenka
FOR EACH ROW
BEGIN
    UPDATE projekce
    SET trzba = trzba + :new.cena
    WHERE projekce_id = :new.foreign_key_projekce;
END;
/
*/
CREATE TABLE film(
    film_id INT NOT NULL AUTO_INCREMENT,
    nazev VARCHAR(50) NOT NULL,
    rezie VARCHAR(60) NOT NULL,
    rok_vydani INTEGER NOT NULL,
    zanr VARCHAR(20) NOT NULL,
    PRIMARY KEY (film_id)
);

ALTER TABLE pokladni ADD CONSTRAINT pokladni__multikino FOREIGN KEY (foreign_key_multikino) REFERENCES multikino(kino_id) ON DELETE CASCADE;

ALTER TABLE promitaci_sal ADD CONSTRAINT promitaci_sal__multikino FOREIGN KEY (foreign_key_multikino) REFERENCES multikino(kino_id) ON DELETE CASCADE;

ALTER TABLE projekce ADD CONSTRAINT projekce__promitaci_sal FOREIGN KEY (foreign_key_promitaci_sal) REFERENCES promitaci_sal(sal_id) ON DELETE CASCADE;
ALTER TABLE projekce ADD CONSTRAINT projekce__film FOREIGN KEY (foreign_key_film) REFERENCES film(film_id) ON DELETE CASCADE;

ALTER TABLE vstupenka ADD CONSTRAINT vstupenka__zakaznik FOREIGN KEY (foreign_key_zakaznik) REFERENCES zakaznik(zakaznik_id) ON DELETE CASCADE;
ALTER TABLE vstupenka ADD CONSTRAINT vstupenka__projekce FOREIGN KEY (foreign_key_projekce) REFERENCES projekce(projekce_id) ON DELETE CASCADE;

ALTER TABLE uzivatel ADD CONSTRAINT uzivatel__zakaznik FOREIGN KEY (foreign_key_zakaznik) REFERENCES zakaznik(zakaznik_id) ON DELETE CASCADE;


INSERT INTO multikino(nazev,adresa,telefon,webove_stranky) VALUES ('Kino Brno Cejl', 'Cejl 777, Brno', '+420787888999','www.kinocejl.cz');
INSERT INTO multikino(nazev,adresa,telefon,webove_stranky) VALUES ('Kino Brno Krenova', 'Krenova 78, Brno', '+420787887999','www.kinokrenova.cz');
INSERT INTO multikino(nazev,adresa,telefon,webove_stranky) VALUES ('Kino Brno Husitska', 'Husitska 717, Brno', '+420787488999','www.kinohusitska.cz');

INSERT INTO pokladni(jmeno, prijmeni, foreign_key_multikino) VALUES ('Jana', 'Novakova',1);
INSERT INTO pokladni(jmeno, prijmeni, foreign_key_multikino) VALUES ('Jan', 'Novak',2);
INSERT INTO pokladni(jmeno, prijmeni, foreign_key_multikino) VALUES ('Zbynek', 'Malina',3);

INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (1,200,1);
INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (2,50,1);
INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (3,220,2);
INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (4,280,2);
INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (5,150,3);
INSERT INTO promitaci_sal(cislo_salu, pocet_mist, foreign_key_multikino) VALUES (6,500,3);

INSERT INTO film(nazev, rezie, rok_vydani, zanr) VALUES ('Pulp Fiction', 'Tarantino', '1994', 'Drama');
INSERT INTO film(nazev, rezie, rok_vydani, zanr) VALUES ('Fight Club', 'Fincher', '1999', 'Drama');
INSERT INTO film(nazev, rezie, rok_vydani, zanr) VALUES ('Inception', 'Nolan', '2010', 'Sci-Fi');

INSERT INTO projekce(cas, datum, trzba, foreign_key_promitaci_sal, foreign_key_film) VALUES ('14:00', '19.1.', 56416,1, 1);
INSERT INTO projekce(cas, datum, trzba, foreign_key_promitaci_sal, foreign_key_film) VALUES ('18:00', '19.1.', 58406,3, 1);
INSERT INTO projekce(cas, datum, trzba, foreign_key_promitaci_sal, foreign_key_film) VALUES ('16:00', '18.1.', 42686,5, 2);
INSERT INTO projekce(cas, datum, trzba, foreign_key_promitaci_sal, foreign_key_film) VALUES ('20:00', '16.1.', 43210,6, 3);

INSERT INTO zakaznik(jmeno, prijmeni, email) VALUES ('Jan', 'Novy','jannovy@seznam.cz');
INSERT INTO zakaznik(jmeno, prijmeni, email) VALUES ('Mirek', 'Novak','mireknovak@seznam.cz');
INSERT INTO zakaznik(jmeno, prijmeni, email) VALUES ('Ladislav', 'Maly','ladamaly@seznam.cz');
INSERT INTO zakaznik(jmeno, prijmeni, email) VALUES ('Marek', 'Mares','mm@seznam.cz');
INSERT INTO zakaznik(jmeno, prijmeni, email) VALUES ('Jan', 'Novak','janvoak@seznam.cz');

INSERT INTO vstupenka(sedadlo, cena, zarezervovano, foreign_key_zakaznik, foreign_key_projekce) VALUES (120,200,1,1,1);
INSERT INTO vstupenka(sedadlo, cena, zarezervovano, foreign_key_zakaznik, foreign_key_projekce) VALUES (132,100,1,1,2);
INSERT INTO vstupenka(sedadlo, cena, zarezervovano, foreign_key_zakaznik, foreign_key_projekce) VALUES (50,180,1,2,2);
INSERT INTO vstupenka(sedadlo, cena, zarezervovano, foreign_key_zakaznik, foreign_key_projekce) VALUES (51,160,1,2,2);
INSERT INTO vstupenka(sedadlo, cena, zarezervovano, foreign_key_zakaznik, foreign_key_projekce) VALUES (111,190,1,5,3);
INSERT INTO vstupenka(sedadlo, cena, foreign_key_zakaznik, foreign_key_projekce) VALUES (71,199,3,3);
INSERT INTO vstupenka(sedadlo, cena, foreign_key_zakaznik, foreign_key_projekce) VALUES (11,199,4,4);

INSERT INTO uzivatel(login, heslo, foreign_key_zakaznik) VALUES ('jnovy', '0123456', 1);
INSERT INTO uzivatel(login, heslo) VALUES ('admin', 'admin');

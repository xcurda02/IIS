USE xcurda02;

DROP TABLE if EXISTS seller CASCADE;
DROP TABLE IF EXISTS ticket CASCADE;
DROP TABLE IF EXISTS projection CASCADE;
DROP TABLE IF EXISTS auditorium CASCADE;
DROP TABLE IF EXISTS user CASCADE;
DROP TABLE IF EXISTS movie CASCADE;
DROP TABLE IF EXISTS cinema CASCADE;



CREATE TABLE cinema(
    cinema_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    address VARCHAR(60) NOT NULL,
    phone VARCHAR(9) NOT NULL,
    web VARCHAR(30) NOT NULL,
    PRIMARY KEY (cinema_id)
);

CREATE TABLE auditorium(
    auditorium_id INT NOT NULL AUTO_INCREMENT,
    number INTEGER NOT NULL,
    capacity INTEGER NOT NULL,
    fk_cinema INTEGER NOT NULL,
    PRIMARY KEY (auditorium_id)
);

CREATE TABLE projection(
    projection_id INT NOT NULL AUTO_INCREMENT,
    date DATETIME,
    income INTEGER,
    fk_movie INTEGER NOT NULL,
    fk_auditorium INTEGER NOT NULL,
    PRIMARY KEY (projection_id)
);



CREATE TABLE ticket(
    ticket_id INT NOT NULL AUTO_INCREMENT,
    seat INTEGER NOT NULL,
    price INTEGER NOT NULL,
    cash_advance TINYINT(1),
    agegroup ENUM('adult','senior','child'),
    fk_user INTEGER,
    fk_projection INTEGER NOT NULL,
    PRIMARY KEY (ticket_id)
);

CREATE TABLE user(
    user_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    login VARCHAR (30) NOT NULL,
    password VARCHAR (255) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(9),
    usergroup ENUM('admin','seller','customer'),
    UNIQUE (login),
    UNIQUE (email),
    PRIMARY KEY (user_id)
);

DELIMITER $$
CREATE TRIGGER ticket_income BEFORE INSERT ON ticket FOR EACH ROW
BEGIN
    UPDATE projection
    SET income = income + NEW.price
    WHERE projection_id = NEW.fk_projection;
END; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER ticket_update_income BEFORE UPDATE ON ticket FOR EACH ROW
BEGIN
    UPDATE projection
    SET income = income + (NEW.price - OLD.price)
    WHERE projection_id = NEW.fk_projection;
END; $$
DELIMITER ;

CREATE TABLE movie(
    movie_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    director VARCHAR(60) NOT NULL,
    release_date INTEGER NOT NULL,
    genre VARCHAR(30) NOT NULL,
    price INTEGER NOT NULL,
    PRIMARY KEY (movie_id)
);


ALTER TABLE auditorium ADD CONSTRAINT auditorium__cinema FOREIGN KEY (fk_cinema) REFERENCES cinema(cinema_id) ON DELETE CASCADE;

ALTER TABLE projection ADD CONSTRAINT projection__auditorium FOREIGN KEY (fk_auditorium) REFERENCES auditorium(auditorium_id) ON DELETE CASCADE;
ALTER TABLE projection ADD CONSTRAINT projection__movie FOREIGN KEY (fk_movie) REFERENCES movie(movie_id) ON DELETE CASCADE;

ALTER TABLE ticket ADD CONSTRAINT ticket_user FOREIGN KEY (fk_user) REFERENCES user(user_id) ON DELETE CASCADE;
ALTER TABLE ticket ADD CONSTRAINT ticket_projection FOREIGN KEY (fk_projection) REFERENCES projection(projection_id) ON DELETE CASCADE;



INSERT INTO cinema(name,address,phone,web) VALUES ('Kino Brno Cejl', 'Cejl 777, Brno', '+420787888999','www.kinocejl.cz');
INSERT INTO cinema(name,address,phone,web) VALUES ('Kino Brno Krenova', 'Krenova 78, Brno', '+420787887999','www.kinokrenova.cz');
INSERT INTO cinema(name,address,phone,web) VALUES ('Kino Brno Husitska', 'Husitska 717, Brno', '+420787488999','www.kinohusitska.cz');

INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (1,200,1);
INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (2,50,1);
INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (1,220,2);
INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (2,280,2);
INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (1,150,3);
INSERT INTO auditorium(number, capacity, fk_cinema) VALUES (2,500,3);

INSERT INTO movie(name, director, release_date, genre, price) VALUES ('Pulp Fiction', 'Tarantino', '1994', 'Drama', 100);
INSERT INTO movie(name, director, release_date, genre, price) VALUES ('Fight Club', 'Fincher', '1999', 'Drama', 120);
INSERT INTO movie(name, director, release_date, genre, price) VALUES ('Inception', 'Nolan', '2010', 'Sci-Fi', 140);

INSERT INTO projection(date, income, fk_auditorium, fk_movie) VALUES ('2018-12-24 14:00:00', 56416,1, 1);
INSERT INTO projection(date, income, fk_auditorium, fk_movie) VALUES ('2019-01-22 16:00:00', 58406,3, 1);
INSERT INTO projection(date, income, fk_auditorium, fk_movie) VALUES ('2019-02-14 18:00:00', 42686,5, 2);
INSERT INTO projection(date, income, fk_auditorium, fk_movie) VALUES ('2019-02-25 19:00:00', 43210,6, 3);

INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('Jan', 'Novy','jnovy', '0123456','jannovy@seznam.cz', 'customer');
INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('Martin', 'Novak','mřovak', '0123456','mnovak@seznam.cz', 'customer');
INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('Jaroslav', 'Adamov', 'jadamov', '1111','jadamov@seznam.cz', 'customer');
INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('Admin', 'Adminovsky', 'admin', 'admin','admin@admin.cz', 'admin');
INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('Jařmil', 'Žekl', 'žžžž', '1111','zekl@email.cz', 'customer');
INSERT INTO user(name, surname, login, password, email,usergroup) VALUES ('jarka', 'Žeklová', 'prodavacka', '123456','zeklova@email.cz', 'seller');

INSERT INTO ticket(seat, price, fk_user, agegroup, cash_advance, fk_projection) VALUES (120,200,1,'adult',0,1);
INSERT INTO ticket(seat, price, fk_user, agegroup,cash_advance, fk_projection) VALUES (132,100,1,'adult',0,2);
INSERT INTO ticket(seat, price, fk_user, agegroup,cash_advance, fk_projection) VALUES (50,180,2,'adult',0,2);
INSERT INTO ticket(seat, price, fk_user, agegroup,cash_advance, fk_projection) VALUES (51,160,2,'adult',0,2);
INSERT INTO ticket(seat, price, fk_user, agegroup,cash_advance, fk_projection) VALUES (111,50,3,'adult',1,3);
INSERT INTO ticket(seat, price, agegroup,cash_advance, fk_projection) VALUES (71,199,'adult',0,3);
INSERT INTO ticket(seat, price, agegroup,cash_advance, fk_projection) VALUES (11,199,'adult',0,4);
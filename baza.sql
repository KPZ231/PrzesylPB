CREATE DATABASE IF NOT EXISTS przesyl;

USE przesyl;

CREATE TABLE IF NOT EXISTS uzytkownicy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa_uzytkownika VARCHAR(50) NOT NULL,
    haslo VARCHAR(100) NOT NULL,
    ranga ENUM('zwykly_uzytkownik', 'gosc', 'administrator') NOT NULL
);

CREATE TABLE IF NOT EXISTS plik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL,
    lokalizacja VARCHAR(255) NOT NULL,
    wielkosc FLOAT NOT NULL,
    id_uzytkownika INT,
    FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownicy(id)
);

-- Creare baza si tabele pentru proiect scoala
CREATE DATABASE IF NOT EXISTS scoala_gimnaziala DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scoala_gimnaziala;

CREATE TABLE IF NOT EXISTS utilizatori (
    id_utilizator INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    parola VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS anunturi (
    id_anunt INT AUTO_INCREMENT PRIMARY KEY,
    titlu VARCHAR(100) NOT NULL,
    continut TEXT NOT NULL,
    data_publicare DATE NOT NULL,
    id_utilizator INT,
    FOREIGN KEY (id_utilizator) REFERENCES utilizatori(id_utilizator) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS profesori (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    nume VARCHAR(100) NOT NULL,
    disciplina VARCHAR(100) NOT NULL,
    email VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS contact_mesaje (
    id_mesaj INT AUTO_INCREMENT PRIMARY KEY,
    nume VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mesaj TEXT NOT NULL,
    data_trimitere DATETIME NOT NULL
);

-- Date de test
INSERT INTO profesori (nume, disciplina, email) VALUES
('Popescu Ion', 'Matematica', 'ion.popescu@example.com'),
('Ionescu Maria', 'Limba Română', 'maria.ionescu@example.com');

INSERT INTO utilizatori (username, parola, rol) VALUES
('admin', '$2y$10$examplehashplaceholder...', 'admin');

INSERT INTO anunturi (titlu, continut, data_publicare, id_utilizator) VALUES
('Deschiderea anului școlar', 'Anunț: deschiderea noului an școlar va avea loc pe 1 septembrie.', '2026-09-01', 1),
('Excursie', 'Elevii claselor a VIII-a vor participa la o excursie.', '2026-05-10', 1);

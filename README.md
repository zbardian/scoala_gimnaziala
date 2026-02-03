# Proiect: Website prezentare - Școala Gimnazială

## Pregatire baza de date:

1. Creaza o baza de date "scoala_gimnaziala" în phpMyAdmin sau din CLI MySQL:
    - CLI (PowerShell / Linux):: 
    CREATE DATABASE scoala_gimnaziala;
2. Creaza un utilizator "scoala_user" in baza de date cu drepturi depline asupra bazei de date 
   "scoala_gimnaziala" în phpMyAdmin sau din CLI MySQL:
    - CLI (PowerShell / Linux):
      CREATE USER 'scoala_user'@'localhost' IDENTIFIED BY 'ParolaTare123';
      GRANT ALL PRIVILEGES ON scoala_gimnaziala.* TO 'scoala_user'@'localhost';
      FLUSH PRIVILEGES;

## Creare utilizator cu phpMyAdmin (UI)

1. Deschide phpMyAdmin în browser (ex: http://localhost/phpmyadmin) și autentifică-te ca root sau un utilizator cu drepturi.
2. Mergi la fila "Users" (Utilizatori) și apasă "Add user account" (Adaugă cont utilizator).
3. Completează formularul:
   - User name: `scoala_user`
   - Host name: `localhost` (sau selectează "Local"/"Use text field" și introdu `localhost`)
   - Password: alege o parolă puternică (ex: `ParolaTare123`) și reintrodu-o în Confirm.
4. La secțiunea "Global privileges" poți lăsa totul debifat (recomandat) și să dai privilegii doar pe baza de date specifică în pasul următor.
5. Click "Go" pentru a crea utilizatorul.

Apoi acordă privilegii pentru baza `scoala_gimnaziala`:

1. În phpMyAdmin, accesează fila "Databases" și selectează `scoala_gimnaziala`.
2. Mergi la "Privileges" (Privilegii) pentru acea bază de date.
3. Click "Add user" (sau "Grant privileges" pentru un user existent), alege `scoala_user` și bifează privilegii necesare (SELECT, INSERT, UPDATE, DELETE, CREATE, DROP etc.).
4. Apasă "Go" pentru a salva.

Alternativ, poți folosi fila SQL din phpMyAdmin și executa direct comenzile:

```sql
CREATE USER 'scoala_user'@'localhost' IDENTIFIED BY 'ParolaTare123';
GRANT ALL PRIVILEGES ON scoala_gimnaziala.* TO 'scoala_user'@'localhost';
FLUSH PRIVILEGES;
```

## Pași pentru configurare locală (XAMPP/WAMP/apache/etc...):

1. Pune  folderul în directorul `htdocs` sau `www` al serverului local.
2. Importă `db_init.sql` în phpMyAdmin sau din CLI MySQL:
   - phpMyAdmin: Import -> alege `db_init.sql` -> Execută.
   - CLI (PowerShell / Linux):
     mysql -u root -p scoala_gimnaziala < db_init.sql
3. Configurare conexiune DB:
   - Poți edita `config.php` pentru a pune parametrii de conexiune sau folosește variabile de mediu:
     - DB_HOST, DB_USER, DB_PASS, DB_NAME
     - APP_ENV=development sau production
4. Accesează proiectul în browser la `http://localhost/proiect-folder/index.php`.

Notițe:
- `db_init.sql` include date de test pentru profesori și anunțuri.
- Dacă folosești autentificare ulterior, generează parole cu `password_hash()`.

## Modificări recente (upgrade UI / conținut)

- Am adăugat o secțiune "Despre școala" pe homepage (`index.php`) pentru a afișa o scurtă descriere și un box cu informații utile (adresă, telefon, email, program). Această secțiune are id-ul `despre-scoala` și poate fi modificată în `index.php`:
  - Fișier: `index.php` (secțiunea are un bloc HTML clar delimitat după imaginea principală).
  - Modifică textul, adresa sau contactele direct în acel fișier.

- Am repoziționat titlul din SVG pentru a fi afișat între acoperiș și ferestrele clădirii:
  - Fișier: `assets/school-landscape.svg`
  - Am schimbat atributul `y` al elementului `<text>` la `90` (poziție relativă în cadrul grupului). Pentru a modifica poziția:
    - Deschide `assets/school-landscape.svg` și caută `<text ...>Școala Gimnazială</text>`.
    - Ajustează valoarea `y` (ex: 80 pentru mai sus, 110 pentru mai jos) sau `font-size` pentru altă dimensiune.

Sfaturi rapide:
- După modificarea SVG-ului, curăță cache-ul browserului sau reîncarcă pagina cu Ctrl+F5 pentru a vedea imediat schimbarea.
- Dacă vrei să adaug și o galerie foto, program clase sau o secțiune cu misiune/valori, indică exact ce conținut dorești și îl adaug.

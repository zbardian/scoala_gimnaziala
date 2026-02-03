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

## Deploy (pași detaliați)
Următorii pași prezintă o metodă sigură și repetabilă pentru a publica proiectul în mediu local (XAMPP/WAMP) și pe un server Linux (ex: Ubuntu) cu Nginx + PHP-FPM + MySQL/MariaDB.

### 1) Deploy local (XAMPP / WAMP)
1. Copiază folderul proiectului în directorul serverului local:
   - XAMPP: copiați în `C:\xampp\htdocs\proiect-scoala`
   - WAMP: copiați în `C:\wamp64\www\proiect-scoala`
2. Importă baza de date în phpMyAdmin sau din CLI:
   - phpMyAdmin: Import -> `db_init.sql`
   - CLI (PowerShell):

```powershell
mysql -u root -p scoala_gimnaziala < db_init.sql
```

3. Configurează `config.php` sau variabile de mediu (DB_HOST, DB_USER, DB_PASS, DB_NAME). Pentru test rapid, editează `config.php` cu credentialele locale.
4. Accesează proiectul în browser la `http://localhost/proiect-scoala/index.php`.

### 2) Deploy producție (Ubuntu 20.04/22.04 + Nginx + PHP-FPM + MariaDB)
Prerechizite: acces root/sudo, un server cu Ubuntu, un nume de domeniu setat (ex: scoala.exemplu.ro).

1. Conectează-te la server și clonează repo:

```bash
sudo apt update && sudo apt install -y git
sudo mkdir -p /var/www/scoala
sudo chown $USER:$USER /var/www/scoala
cd /var/www
git clone <URL_REPO> scoala
```

2. Instalează componentele serverului:

```bash
sudo apt install -y nginx php-fpm php-mysql mariadb-server unzip
```

3. Configurează baza de date (MariaDB/MySQL):

```bash
sudo mysql -u root
# în promptul mysql:
CREATE DATABASE scoala_gimnaziala CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'scoala_user'@'localhost' IDENTIFIED BY 'ParolaTareTare!';
GRANT ALL PRIVILEGES ON scoala_gimnaziala.* TO 'scoala_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
# import db
mysql -u scoala_user -p scoala_gimnaziala < /var/www/scoala/db_init.sql
```

4. Setează variabilele de mediu pentru PHP-FPM (recomandat pentru securitate):
   - Editează pool-ul PHP-FPM (ex: `/etc/php/8.1/fpm/pool.d/www.conf`) și adaugă liniile:

```
env[DB_HOST] = "127.0.0.1"
env[DB_USER] = "scoala_user"
env[DB_PASS] = "ParolaTareTare!"
env[DB_NAME] = "scoala_gimnaziala"
```

   - Apoi repornește PHP-FPM:

```bash
sudo systemctl restart php8.1-fpm.service
```

   Observație: proiectul folosește `getenv()` în `config.php` dacă variabilele de mediu sunt setate; altfel editează `config.php` direct (mai puțin recomandat în producție).

5. Configurează Nginx (exemplu):
   - Creează fișierul `/etc/nginx/sites-available/scoala` cu conținut potrivit (poți reutiliza `scoala_nginx.conf` din repository). Exemple:

```nginx
server {
    listen 80;
    server_name scoala.exemplu.ro;
    root /var/www/scoala;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /assets/ {
        expires 7d;
    }
}
```

   - Activează site-ul și verifică Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/scoala /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

6. Setează permisiuni fișiere:

```bash
sudo chown -R www-data:www-data /var/www/scoala
sudo find /var/www/scoala -type d -exec chmod 755 {} \;
sudo find /var/www/scoala -type f -exec chmod 644 {} \;
```

7. Instalează SSL (Let's Encrypt):

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d scoala.exemplu.ro
```

8. Testează aplicația în browser la `https://scoala.exemplu.ro`.

### 3) Actualizări și rollback
- Pentru a aduce schimbări: în folderul `/var/www/scoala` rulezi `git pull origin main` și apoi `sudo systemctl reload php8.1-fpm nginx`.
- Dacă faci schimbări ce afectează schema DB, aplică migrațiile manual (nu există script automat în repo) sau importă SQL actualizat.
- Pentru rollback la o versiune anterioară: `git checkout <commit_sha>` și repornește serviciile.

### 4) Securitate recomandată (pe scurt)
- Schimbă parola DB generată în README cu una unică și puternică.
- Dezactivează accesul root la MariaDB de la distanță și folosește firewall (ufw).

```bash
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

- Păstrează sistemul și pachetele PHP actualizate.

Notițe:
- `db_init.sql` include date de test pentru profesori și anunțuri.
- Dacă folosești autentificare ulterior, generează parole cu `password_hash()`.



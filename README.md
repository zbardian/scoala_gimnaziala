# Proiect: Website prezentare - Școala Gimnazială Locală

Pași pentru configurare locală (XAMPP/WAMP):

1. Pune acel folder în directorul `htdocs` sau `www` al serverului local.
2. Importă `db_init.sql` în phpMyAdmin sau din CLI MySQL:
   - phpMyAdmin: Import -> alege `db_init.sql` -> Execută.
   - CLI (PowerShell):
     mysql -u root -p < d:\Work\Proiect-html-css-php-mysql\db_init.sql

3. Configurare conexiune DB:
   - Poți edita `config.php` pentru a pune parametrii de conexiune sau folosește variabile de mediu:
     - DB_HOST, DB_USER, DB_PASS, DB_NAME
     - APP_ENV=development sau production

4. Accesează proiectul în browser la `http://localhost/proiect-folder/index.php`.

Notițe:
- `db_init.sql` include date de test pentru profesori și anunțuri.
- Dacă folosești autentificare ulterior, generează parole cu `password_hash()`.

Opțiuni viitoare recomandate:
- Mută header/footer în `includes/` (făcut deja).
- Folosește prepared statements peste tot (parțial folosit pentru contact).
- Adaugă validare client-side și CSRF token pentru formulare.

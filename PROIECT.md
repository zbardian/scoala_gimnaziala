# Documentație completă proiect

Acest fișier conține versiunea completă a documentației proiectului (conținutul original din `proiect.txt`), incluzând introducere, analiză, proiectare, implementare, teste și concluzii.

---

INTRODUCERE
Dezvoltarea accelerată a tehnologiei informației a influențat profund toate domeniile societății moderne, inclusiv sistemul educațional. Instituțiile de învățământ sunt nevoite să adopte soluții informatice moderne pentru a facilita comunicarea, organizarea și accesul rapid la informații relevante. În acest context, website-urile instituționale au devenit un instrument esențial pentru prezentarea activității unei școli, pentru informarea elevilor, părinților și cadrelor didactice, precum și pentru crearea unei imagini unitare și profesioniste în mediul online.
Un website de prezentare bine structurat permite publicarea rapidă a informațiilor oficiale, a anunțurilor, a programelor școlare și a altor materiale de interes general. Utilizarea bazelor de date relaționale asigură o gestionare eficientă a informațiilor, permițând actualizarea acestora într-un mod sigur și organizat. Astfel, aplicațiile web bazate pe baze de date devin soluții fiabile și scalabile pentru instituțiile de învățământ.
Scopul prezentei lucrări este proiectarea și dezvoltarea unui website de prezentare pentru o școală gimnazială locală, utilizând tehnologii web moderne și o bază de date relațională implementată cu ajutorul limbajului SQL. Aplicația propusă urmărește să ofere o structură clară a informațiilor, o interfață intuitivă și posibilitatea administrării conținutului prin intermediul unui panou de control.
Obiectivele principale ale lucrării sunt:
    • analizarea conceptelor teoretice privind aplicațiile web și bazele de date relaționale;
    • proiectarea structurii aplicației și a bazei de date;
    • implementarea unei aplicații web funcționale;
    • testarea și validarea soluției propuse.
Lucrarea este structurată în patru capitole. Primul capitol prezintă fundamentele teoretice necesare dezvoltării aplicațiilor web. Al doilea capitol este dedicat analizei și proiectării aplicației. Capitolul al treilea descrie implementarea efectivă a website-ului și a bazei de date, iar ultimul capitol tratează aspecte legate de testarea și validarea aplicației.

**CAPITOLUL 1
FUNDAMENTE TEORETICE PRIVIND APLICAȚIILE WEB**
1.1 Noțiuni generale despre aplicațiile web
Aplicațiile web reprezintă programe software care rulează pe un server și sunt accesate de utilizatori prin intermediul unui browser web. Spre deosebire de aplicațiile desktop, acestea nu necesită instalare locală și pot fi utilizate de pe orice dispozitiv conectat la internet.
Un avantaj major al aplicațiilor web îl constituie ușurința în mentenanță și actualizare, deoarece modificările sunt realizate centralizat, pe server. De asemenea, acestea permit acces simultan pentru mai mulți utilizatori și pot fi integrate cu baze de date pentru stocarea și gestionarea informațiilor.
Aplicațiile web sunt utilizate pe scară largă în domenii precum educația, comerțul electronic, administrația publică și comunicarea online.

1.2 Arhitectura client–server
Arhitectura client–server este modelul fundamental pe care se bazează majoritatea aplicațiilor web. Aceasta presupune existența a două componente principale:
    • Clientul, reprezentat de browserul web al utilizatorului;
    • Serverul, care găzduiește aplicația și baza de date.
Clientul trimite cereri către server sub forma unor solicitări HTTP, iar serverul procesează aceste cereri și returnează răspunsuri sub formă de pagini web sau date. Acest model permite separarea logicii aplicației de interfața utilizatorului, facilitând dezvoltarea și întreținerea aplicațiilor.

1.3 Tehnologii web utilizate
HTML (HyperText Markup Language)
HTML este limbajul standard utilizat pentru structurarea paginilor web. Acesta definește elementele de bază precum titluri, paragrafe, imagini, tabele și formulare. HTML oferă scheletul unei pagini web, fiind completat de alte tehnologii pentru aspect și funcționalitate.
CSS (Cascading Style Sheets)
CSS este utilizat pentru definirea aspectului vizual al paginilor web. Prin intermediul CSS se pot controla culorile, fonturile, poziționarea elementelor și adaptarea interfeței la diferite dimensiuni de ecran.
PHP (Hypertext Preprocessor)
PHP este un limbaj de programare server-side folosit pentru dezvoltarea aplicațiilor web dinamice. Acesta permite prelucrarea datelor, conectarea la baze de date și generarea de conținut dinamic, fiind frecvent utilizat împreună cu baze de date SQL.
SQL (Structured Query Language)
SQL este limbajul standard utilizat pentru gestionarea bazelor de date relaționale. Prin intermediul SQL se pot crea tabele, insera, modifica și interoga date. Utilizarea SQL permite organizarea eficientă a informațiilor și asigură integritatea acestora.

1.4 Baze de date relaționale
Bazele de date relaționale sunt structuri de stocare a informațiilor organizate sub formă de tabele. Fiecare tabel este alcătuit din rânduri și coloane, iar relațiile dintre tabele sunt stabilite prin chei primare și chei străine.
Principalele avantaje ale bazelor de date relaționale sunt:
    • organizarea clară a datelor;
    • reducerea redundanței;
    • asigurarea integrității informațiilor;
    • posibilitatea efectuării de interogări complexe.
În cadrul aplicațiilor web, bazele de date relaționale sunt esențiale pentru stocarea conținutului dinamic, precum anunțuri, pagini informative sau date administrative.

1.5 Sistemul de gestiune a bazelor de date MySQL
MySQL este unul dintre cele mai utilizate sisteme de gestiune a bazelor de date relaționale. Acesta este open-source, performant și ușor de integrat cu aplicații web dezvoltate în PHP.
MySQL oferă suport pentru:
    • tranzacții;
    • relații între tabele;
    • securizarea accesului la date;
    • interogări eficiente.
Datorită acestor caracteristici, MySQL este o alegere potrivită pentru dezvoltarea website-ului de prezentare al unei școli gimnaziale.



**CAPITOLUL 2
ANALIZA ȘI PROIECTAREA APLICAȚIEI WEB**
2.1 Analiza cerințelor aplicației
Analiza cerințelor reprezintă o etapă esențială în dezvoltarea oricărei aplicații informatice, având rolul de a identifica funcționalitățile necesare și modul în care acestea răspund nevoilor utilizatorilor finali. În cazul de față, aplicația web este destinată prezentării unei școli gimnaziale locale și gestionării informațiilor aferente acesteia.
Principalii utilizatori ai aplicației sunt:
    • elevii;
    • părinții;
    • cadrele didactice;
    • personalul administrativ;
    • publicul larg interesat de activitatea școlii.
Aplicația trebuie să ofere acces rapid la informații generale despre școală, precum istoricul instituției, structura organizațională, oferta educațională și activitățile desfășurate. De asemenea, este necesară posibilitatea publicării de anunțuri și noutăți într-un mod dinamic, fără intervenții tehnice complexe.
Cerințele funcționale ale aplicației sunt:
    • afișarea paginilor de prezentare ale școlii;
    • publicarea și administrarea anunțurilor;
    • afișarea informațiilor despre cadrele didactice;
    • gestionarea conținutului prin intermediul unui panou de administrare;
    • stocarea datelor într-o bază de date relațională.
Cerințele nefuncționale includ:
    • ușurința în utilizare;
    • securitatea datelor;
    • compatibilitatea cu browserele web moderne;
    • timp de răspuns optim.

2.2 Structura generală a aplicației
Aplicația web este organizată pe baza arhitecturii client–server, în care browserul utilizatorului reprezintă clientul, iar serverul web găzduiește aplicația și baza de date.
Structura aplicației este împărțită în două componente principale:
    • partea publică, accesibilă tuturor utilizatorilor;
    • partea administrativă, destinată gestionării conținutului.
Partea publică a aplicației conține următoarele secțiuni:
    • pagina principală;
    • pagina „Despre școală”;
    • pagina „Cadre didactice”;
    • pagina „Anunțuri”;
    • pagina „Contact”.
Partea administrativă permite:
    • autentificarea administratorului;
    • adăugarea, modificarea și ștergerea anunțurilor;
    • gestionarea informațiilor despre profesori;
    • actualizarea conținutului paginilor.

2.3 Proiectarea bazei de date
Baza de date reprezintă componenta centrală a aplicației, având rolul de a stoca și organiza informațiile utilizate de website. Pentru implementare s-a ales un sistem de gestiune a bazelor de date relaționale, MySQL, datorită stabilității și compatibilității cu PHP.
Baza de date este proiectată astfel încât să reducă redundanța informațiilor și să asigure integritatea datelor. Structura acesteia este compusă din mai multe tabele interconectate.
Tabelul utilizatori
Acest tabel stochează datele administratorilor aplicației.
Câmpuri principale:
    • id_utilizator – cheie primară;
    • username – nume utilizator;
    • parola – parolă criptată;
    • rol – tip utilizator.
Tabelul anunturi
Tabel utilizat pentru stocarea anunțurilor publicate pe site.
Câmpuri:
    • id_anunt – cheie primară;
    • titlu – titlul anunțului;
    • continut – textul anunțului;
    • data_publicare – data publicării.
Tabelul profesori
This content continues...

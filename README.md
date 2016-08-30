# sincron-sdk
Sincron.biz API

##Metode

Nota 1: consideram in continuare URL ca fiind url-ul de baza furnizat de catre HR Sincron SRL.
Nota 2: raspunsurile de la API Sincron sunt furnizate in format JSON; pentru a primi raspunsul in format XML este nevoie sa adaugati cererii un parametru GET "format" cu valoarea "xml".

#1.  Preluare anunturi recrutare

Pentru preluarea anunturilor de recrutare se executa urmatoarea cerere:

**URL:** URL/apis/get_jobs
**Metoda:** GET
**Parametri GET:**
* data_publicare_lte (optional, date  yyyy-mm-dd): vor fi returnate joburile active incepand cu data data_publicare_lte (inclusiv)
* data_retragere_gte (optional, date  yyyy-mm-dd): vor fi returnate joburile active pana la data data_retragere_gte (inclusiv)
* data_modificare_gte (optional, datetime  yyyy-mm-dd HH:ii:ss): vor fi returnate joburile modificate incepand cu data data_modificare_gte
Nota 1.1: valorile implicite ale parametrilor sunt: data_publicate_lte = current_date(); data_retragere_gte = current_date(); data_modificare_gte = NULL

**Raspuns:**
* Status: success/error
* Message: mesajul in caz de eroare
* Data: vector de obiecte generice [anunt] cu proprietatile
    * id_anunt (integer)
    * nume_anunt (string): numele anuntului publicat
    * pozitie (string): pozitia din companie pentru care se recruteaza
    * departament (string): departamentul pe care a fost deschisa pozitia
    * localitati (string): localitatile in care este valabil job (daca sunt mai multe, sunt separate prin ",")
    * descriere (string): descrierea jobului
    * beneficii (string): beneficiile oferite
    * profiL_candidat (string): profilul candidatului ideal cautat
    * data_publicare (date  yyyy-mm-dd): data la care jobul devine activ
    * data_retragere (date  yyyy-mm-dd): ultima zi in care jobul este activ
    * data_modificare (datetime  yyyy-mm-dd HH:ii:ss): data la care informatiile au fost modificate
    * status (boolean): 1/true = activ; 0/false = inactiv

**Observatii:**
* un request fara parametri GET va intoarce intotdeauna joburile active la data curenta.


#2.  Preluare anunt recrutare

Pentru preluarea unui singur anunt de recrutare se executa urmatoarea cerere:
**URL:** URL/apis/get_job
**Metoda:** GET
**Parametri GET: **
* Id_anunt (integer)
**Raspuns:**
* Status: success/error
* Message: mesajul in caz de eroare
* Data: obiect generic [anunt] cu proprietatile
    * id_anunt (integer)
    * nume_anunt (string): numele anuntului publicat
    * pozitie (string): pozitia din companie pentru care se recruteaza
    * departament (string): departamentul pe care a fost deschisa pozitia
    * localitati (string): localitatile in care este valabil job (daca sunt mai multe, sunt separate prin ",")
    * descriere (string): descrierea jobului
    * beneficii (string): beneficiile oferite
    * profiL_candidat (string): profilul candidatului ideal cautat
    * data_publicare (date  yyyy-mm-dd): data la care jobul devine activ
    * data_retragere (date  yyyy-mm-dd): ultima zi in care jobul mai este activ
    * data_modificare (datetime  yyyy-mm-dd HH:ii:ss): data la care informatiile au fost modificate
    * status (boolean): 1/true = activ; 0/false = inactiv

#3.  Aplicare la job

Pentru a adauga o aplicare a unui candidat la un job se executa urmatoarea cerere:
**URL:** URL/apis/post_candidate_job
**Metoda:** POST
**Parametri POST:**
* id_anunt (integer): id-ul anuntului la care a aplicat candidatul
* email (string): emailul candidatului
* first_name (string)
* last_name (string)
* phone (string)
* cv_file_name[] (vector de stringuri): numele fisierelor incarcate de candidat (inclusiv extensia)
* cv_content[] (vector de stringuri): continutul fisierelor incarcate de candidat encodate base64
**Raspuns:**
* Status: success/error
* Message: mesajul in caz de eroare
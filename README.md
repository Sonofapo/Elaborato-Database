# Base di Dati per un gioco FPS
Questo repository contiene il progetto d'esame per il corso Basi di Dati creato da Antonio Emanuele Pepe e Alex Giamperoli durante l'anno accademico 2022/2023.

## Descrizione
Il database che si è creato vuole modellare alcuni aspetti propri di un gioco del genere FPS. Nello specifico si è scelto un gioco a round composto da due squadre di cinque giocatori ciascuna il cui scopo è quello di assicurarsi il maggior numero di round vinti, secondo delle meccaniche ben definite.

All'utente è data la possibilità di accedere o registrarsi a un portale. Tale piattaforma permette di consultare lo storico e i dettagli delle partite giocate. Similmente, l'utente è in grado di simulare lo svolgimento di una partita in modo tale da avere a disposizione dati nuovi da poter consultare.

## Organizzazione del repository
Questo repository contiene tre elementi principali:

- Un file ```relazione.pdf```, contiene la documentazione dello sviluppo del progetto
- Uno script ```elaborato.sql```, permete l'inizializzazione della base di dati con i valori necessari al corretto funzionamento
- Il sorgente del frontend, produce la piattaforma messa a disposizione dell'utente

## Funzionalità
Sono state sviluppate le seguenti funzionalità principali, definite successivamente come _operazioni_. Si è voluto suddividere tali operazioni in due macro categorie:

### Operazioni generali

1.	Iscrizione di un utente
2.	Eliminazione di un utente
3.	Visualizzazione dell’esito delle partite giocate e loro durata
4.	Visualizzazione della percentuale di vittorie in un certo lasso di tempo
5.	Top 3 delle armi più utilizzate con rispettivo numero di uccisioni effettuate
6.	Top 3 delle mappe più giocate in un certo lasso di tempo

### Operazioni specifiche di una partita

7.	Salvataggio al dettaglio di un round
8.	Conteggio dei round vinti e persi
9.	Visualizzazione al dettaglio di un round
10.	Storico delle armi possedute da un giocatore
11.	Dettaglio delle eliminazioni avvenute
12. Conteggio delle eliminazioni per ogni arma acquistata da un giocatore

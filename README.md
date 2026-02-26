# 📘 PokéYZ – Pokédex & Battle Web App

PokéYZ è un'applicazione web sviluppata in PHP e MySQL che permette agli utenti di:

- Consultare una Pokédex con filtri avanzati
- Registrarsi e creare un account personale
- Costruire una squadra Pokémon personalizzata
- Combattere contro allenatori casuali o selezionati

---

## 🔎 1. Parte Wiki / Pokédex

**File principale:** `index.php`

### 📋 Elenco Pokémon

La homepage mostra la lista completa dei Pokémon presenti nel database (`Pokemon`), ordinati per:

- Numero Pokédex (`cod`)
- Forma secondaria (`sec_form`)

Per ogni Pokémon vengono mostrati:

- Immagine
- Numero Pokédex
- Nome (con forma se diversa da BASE)
- Tipo 1 e Tipo 2

### 🔍 Sistema di Filtri

L'utente può filtrare la lista tramite:

- Nome (ricerca parziale con `LIKE`)
- Tipo 1
- Tipo 2
- Range numero Pokédex (min / max)

I filtri sono combinabili tra loro.  
La query SQL viene costruita dinamicamente in base ai filtri selezionati.  
È presente anche un pulsante **Reset** che ripristina la lista completa.

---

## 👤 2. Registrazione, Login e Account

**File principale:** `login.php`

### 📝 Registrazione

Durante la registrazione:

- Viene verificato che il nome utente non esista già
- La password viene salvata tramite `password_hash()`
- Viene creato automaticamente:
  - Un record nella tabella `Utente`
  - Una squadra associata nella tabella `Squadra`
- L'utente viene loggato automaticamente

### 🔐 Login

Il login:

- Cerca l'utente per nome
- Verifica la password tramite `password_verify()`
- Salva in sessione:
  - `user_id`
  - `username`

### 🚪 Logout

Il logout distrugge la sessione e riporta alla pagina di login.

---

## 🧩 3. Gestione Squadra

**File principale:** `squadra.php`  
**Configurazione Pokémon:** `aggiungi_pokemon.php`

Ogni utente possiede una squadra composta da 6 slot.

### 📦 Visualizzazione Squadra

La pagina mostra:

- Slot (1–6)
- Nome Pokémon
- Tipi
- Mosse selezionate (fino a 4)
- Abilità scelta
- Pulsanti:
  - Modifica
  - Rimuovi

Se uno slot è vuoto, viene mostrato il pulsante **Aggiungi Pokémon**.

### ➕ Aggiunta Pokémon

Procedura guidata in 3 step:

1. **Selezione Pokémon**  
   - Lista completa  
   - Barra di ricerca per nome  
   - Esclusione dei Pokémon già presenti in squadra

2. **Selezione Abilità**  
   - Vengono mostrate solo le abilità disponibili per quel Pokémon.

3. **Selezione Mosse**  
   - Mostra solo le mosse apprendibili  
   - Selezionabili da 1 a 4 (checkbox)

Salvataggio finale nella tabella `Squadra_Pokemon`.

### ✏️ Modifica Pokémon

La modifica:

- Cancella il Pokémon dallo slot
- Re-inserisce il nuovo set (abilità + mosse)

### ❌ Rimozione Pokémon

Elimina il Pokémon selezionato dalla tabella `Squadra_Pokemon` tramite:
## ⚔️ 4. Sistema di Combattimento

**File principale:** `combatti.php`  
**Pagina battaglia:** `prova.php`

### 🎮 Modalità disponibili

#### 🎲 Lotta Random
- Reindirizza a `prova.php`
- Modalità casuale
- L'avversario viene scelto automaticamente

#### 👥 Scegli Allenatore
- Mostra tutti gli utenti con `password = 'allenatore'` (escluso l'utente loggato)
- Selezionando un allenatore: `prova.php?id_avversario=...`

La pagina `prova.php` riceve:
- Squadra utente loggato
- Squadra avversario selezionato

---

## 🗄️ Struttura Database Principale

Tabelle principali:
- `Utente`
- `Squadra`
- `Squadra_Pokemon`
- `Pokemon`
- `Mossa`
- `Abilita`
- `Mossa_x_pokemon`
- `Abilita_Pokemon`

Relazioni:
- 1 Utente → 1 Squadra
- 1 Squadra → max 6 Pokémon
- 1 Pokémon → max 4 Mosse
- 1 Pokémon → 1 Abilità scelta

---

## 🛠️ Tecnologie Utilizzate

- PHP
- MySQL
- HTML5
- CSS3
- Sessioni PHP
- Prepared Statements per sicurezza

---

## 🚀 Funzionalità Chiave

- ✅ Sistema completo di autenticazione
- ✅ Pokédex filtrabile dinamicamente
- ✅ Squadra personalizzabile
- ✅ Sistema di modifica e rimozione Pokémon
- ✅ Modalità di combattimento contro AI o allenatori
- ✅ Interfaccia responsive
```sql
DELETE FROM Squadra_Pokemon
WHERE id_squadra = ? AND cod = ? AND sec_form = ?

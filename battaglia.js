// ============================================================
// VARIABILI GLOBALI
// ============================================================

// Queste variabili verranno inizializzate dal PHP
let teamData = [];
let enemyTeamData = [];
let idSquadraGiocatore = 0;
let idSquadraAvversario = 0;
let nomeGiocatore = '';
let nomeAvversario = '';

// Pokémon attuali
let currentPokemon = null;
let currentEnemyPokemon = null;

// Riferimenti agli elementi DOM (verranno inizializzati dopo il caricamento della pagina)
let playerSprite, playerName, playerLevel, playerHpText, playerHpBar;
let enemyName, enemyLevel, enemyHpText, enemyHpBar, enemySprite;
let mainMenu, movesMenu, pokemonMenu, questionBox, currentPokemonNameSpan;
let movesColumn1, movesColumn2;
let fightBtn, pokemonBtn, bagBtn, runBtn, backFromMovesBtn, backFromPokemonBtn;

// Variabile per tracciare se è in corso un'azione
let isActionInProgress = false;

// ============================================================
// FUNZIONE DI INIZIALIZZAZIONE (chiamata dal PHP)
// ============================================================
function initBattle(data) {
    // Assegna i dati passati dal PHP
    teamData = data.teamData;
    enemyTeamData = data.enemyTeamData;
    idSquadraGiocatore = data.idSquadraGiocatore;
    idSquadraAvversario = data.idSquadraAvversario;
    nomeGiocatore = data.nomeGiocatore;
    nomeAvversario = data.nomeAvversario;
    
    // Inizializza i Pokémon attuali
    currentPokemon = teamData.find(p => p.slot == data.currentPokemonSlot);
    currentEnemyPokemon = enemyTeamData[0];
    
    // Inizializza i riferimenti DOM
    initDOMReferences();
    
    // Debug
    debugTeamData();
    
    // Inizializza gli event listener
    attachMoveListeners();
    attachPokemonListeners();
    
    // Seleziona FIGHT di default
    fightBtn.classList.add('selected');
    
    // Carica le mosse iniziali
    setTimeout(() => {
        updateMovesForPokemon(currentPokemon.cod, currentPokemon.sec_form, currentPokemon.slot);
    }, 100);
}

// ============================================================
// INIZIALIZZAZIONE RIFERIMENTI DOM
// ============================================================
function initDOMReferences() {
    playerSprite = document.getElementById('playerSprite');
    playerName = document.getElementById('playerName');
    playerLevel = document.getElementById('playerLevel');
    playerHpText = document.getElementById('playerHpText');
    playerHpBar = document.getElementById('playerHpBar');
    
    enemyName = document.getElementById('enemyName');
    enemyLevel = document.getElementById('enemyLevel');
    enemyHpText = document.getElementById('enemyHpText');
    enemyHpBar = document.getElementById('enemyHpBar');
    enemySprite = document.getElementById('enemySprite');
    
    mainMenu = document.getElementById('mainMenu');
    movesMenu = document.getElementById('movesMenu');
    pokemonMenu = document.getElementById('pokemonMenu');
    questionBox = document.getElementById('questionBox');
    currentPokemonNameSpan = document.getElementById('currentPokemonName');
    
    movesColumn1 = document.getElementById('movesColumn1');
    movesColumn2 = document.getElementById('movesColumn2');
    
    fightBtn = document.getElementById('fightBtn');
    pokemonBtn = document.getElementById('pokemonBtn');
    bagBtn = document.getElementById('bagBtn');
    runBtn = document.getElementById('runBtn');
    backFromMovesBtn = document.getElementById('backFromMovesBtn');
    backFromPokemonBtn = document.getElementById('backFromPokemonBtn');
    
    // Attacca gli event listener principali
    attachMainListeners();
}

// ============================================================
// FUNZIONE DI DEBUG
// ============================================================
function debugTeamData() {
    console.log('=== DEBUG TEAM DATA ===');
    console.log('Giocatore:', nomeGiocatore);
    teamData.forEach(pokemon => {
        console.log(`Slot ${pokemon.slot}: ${pokemon.name} (cod: ${pokemon.cod}, form: ${pokemon.sec_form})`);
    });
    console.log('=== DEBUG ENEMY TEAM DATA ===');
    console.log('Avversario:', nomeAvversario);
    enemyTeamData.forEach(pokemon => {
        console.log(`Slot ${pokemon.slot}: ${pokemon.name} (cod: ${pokemon.cod}, form: ${pokemon.sec_form})`);
    });
    console.log('=======================');
}

// ============================================================
// FUNZIONE PER DISABILITARE/ABILITARE TUTTI I BOTTONI
// ============================================================
function disableAllButtons(disable) {
    const buttons = [
        fightBtn, pokemonBtn, bagBtn, runBtn,
        backFromMovesBtn, backFromPokemonBtn,
        ...document.querySelectorAll('.move-button'),
        ...document.querySelectorAll('.pokemon-button')
    ];
    
    buttons.forEach(button => {
        if (button) {
            button.disabled = disable;
            if (disable) {
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
                button.style.pointerEvents = 'none';
            } else {
                button.style.opacity = '1';
                button.style.cursor = 'pointer';
                button.style.pointerEvents = 'auto';
            }
        }
    });
}

// ============================================================
// FUNZIONE PER TORNARE AL MENU PRINCIPALE
// ============================================================
function backToMainMenu() {
    mainMenu.classList.remove('hidden');
    movesMenu.classList.remove('active');
    pokemonMenu.classList.remove('active');
    questionBox.innerHTML = 'WHAT WILL<br>' + currentPokemon.name + ' DO?';
    
    document.querySelectorAll('.command-button, .move-button, .pokemon-button').forEach(btn => {
        if(btn.id !== 'backFromMovesBtn' && btn.id !== 'backFromPokemonBtn') {
            btn.classList.remove('selected');
        }
    });
    
    fightBtn.classList.add('selected');
}

// ============================================================
// FUNZIONE PER USARE UNA MOSSA
// ============================================================
function usaMossa(mossa) {
    // Se è già in corso un'azione, non fare niente
    if (isActionInProgress) return;
    
    console.log('Mossa usata:', mossa);
    
    // Imposta che un'azione è in corso
    isActionInProgress = true;
    
    // Disabilita tutti i bottoni
    disableAllButtons(true);
    
    questionBox.innerHTML = currentPokemon.name + '<br>USED ' + mossa.nome + '!';
    
    // Qui implementerai la logica di danno
    setTimeout(() => {
        // Simula il turno dell'avversario
        const mossaAvversario = 'ATTACK';
        questionBox.innerHTML = 'ENEMY ' + currentEnemyPokemon.name + '<br>USED ' + mossaAvversario + '!';
        
        setTimeout(() => {
            backToMainMenu();
            // Riabilita i bottoni
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
    }, 2000);
}

// ============================================================
// FUNZIONE PER MOSTRARE MOSSE VUOTE
// ============================================================
function showEmptyMoves() {
    movesColumn1.innerHTML = '';
    movesColumn2.innerHTML = '';
    
    // Mostra 4 mosse vuote
    for (let i = 0; i < 2; i++) {
        const emptyButton = document.createElement('button');
        emptyButton.className = 'move-button disabled';
        emptyButton.textContent = '-';
        emptyButton.disabled = true;
        movesColumn1.appendChild(emptyButton);
    }
    
    for (let i = 0; i < 2; i++) {
        const emptyButton = document.createElement('button');
        emptyButton.className = 'move-button disabled';
        emptyButton.textContent = '-';
        emptyButton.disabled = true;
        movesColumn2.appendChild(emptyButton);
    }
}

// ============================================================
// FUNZIONE PER ATTACCARE GLI EVENT LISTENER ALLE MOSSE
// ============================================================
function attachMoveListeners() {
    // Prima rimuovi tutti i listener esistenti clonando e sostituendo
    const oldButtons = document.querySelectorAll('.move-button');
    oldButtons.forEach(button => {
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
    });
    
    // Ora attacha i listener ai nuovi bottoni
    document.querySelectorAll('.move-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            if (isActionInProgress) return;
            if (this.disabled) return;
            
            // Rimuovi selected da tutti i bottoni mossa
            document.querySelectorAll('.move-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Aggiungi selected a questo bottone
            this.classList.add('selected');
            
            // Crea oggetto mossa
            const mossa = {
                id: this.dataset.moveId,
                nome: this.textContent.trim(),
                potenza: parseInt(this.dataset.power) || 0,
                tipo: this.dataset.type || 'normale',
                accuratezza: parseInt(this.dataset.accuracy) || 100
            };
            
            console.log('Mossa selezionata:', mossa);
            usaMossa(mossa);
        });
    });
    
    console.log('Listener mosse riattaccati');
}

// ============================================================
// FUNZIONE PER AGGIORNARE LE MOSSE VIA AJAX
// ============================================================
function updateMovesForPokemon(cod, secForm, slot, cacheBuster = null) {
    console.log('Caricamento mosse per Pokémon cod:', cod, 'secForm:', secForm, 'slot:', slot);
    
    // Disabilita i bottoni durante il caricamento
    disableAllButtons(true);
    
    // Costruisci URL con cache buster per evitare problemi di cache
    let url = 'get_mosse.php?cod=' + cod + '&sec_form=' + encodeURIComponent(secForm) + '&slot=' + slot;
    if (cacheBuster) {
        url += '&_=' + cacheBuster;
    } else {
        url += '&_=' + new Date().getTime();
    }
    
    // Fai una chiamata AJAX per recuperare le mosse
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nella risposta del server');
            }
            return response.json();
        })
        .then(mosse => {
            console.log('Mosse ricevute:', mosse);
            
            if (mosse.error) {
                console.error('Errore nel caricamento mosse:', mosse.error);
                // Mostra mosse di default o vuote
                showEmptyMoves();
                disableAllButtons(false);
                return;
            }
            
            // Pulisci le colonne delle mosse
            movesColumn1.innerHTML = '';
            movesColumn2.innerHTML = '';
            
            if (mosse.length === 0) {
                // Nessuna mossa trovata
                showEmptyMoves();
            } else {
                // Popola le mosse (massimo 4)
                for (let i = 0; i < Math.min(4, mosse.length); i++) {
                    const move = mosse[i];
                    const moveButton = document.createElement('button');
                    moveButton.className = 'move-button' + (i === 0 ? ' selected' : '');
                    moveButton.setAttribute('data-move-id', move.id_mossa);
                    moveButton.setAttribute('data-move', move.nome.toLowerCase());
                    moveButton.setAttribute('data-power', move.danno);
                    moveButton.setAttribute('data-type', move.tipo.toLowerCase());
                    moveButton.setAttribute('data-accuracy', move.accuratezza);
                    moveButton.textContent = move.nome.toUpperCase();
                    
                    // Aggiungi alla colonna appropriata
                    if (i < 2) {
                        movesColumn1.appendChild(moveButton);
                    } else {
                        movesColumn2.appendChild(moveButton);
                    }
                }
                
                // Se ci sono meno di 2 mosse, aggiungi placeholder
                if (mosse.length < 2) {
                    for (let i = mosse.length; i < 2; i++) {
                        const emptyButton = document.createElement('button');
                        emptyButton.className = 'move-button disabled';
                        emptyButton.textContent = '-';
                        emptyButton.disabled = true;
                        movesColumn1.appendChild(emptyButton);
                    }
                }
                
                // Se ci sono meno di 4 mosse, aggiungi placeholder
                if (mosse.length < 4) {
                    for (let i = Math.max(2, mosse.length); i < 4; i++) {
                        const emptyButton = document.createElement('button');
                        emptyButton.className = 'move-button disabled';
                        emptyButton.textContent = '-';
                        emptyButton.disabled = true;
                        movesColumn2.appendChild(emptyButton);
                    }
                }
            }
            
            // Riattacca gli event listener alle nuove mosse
            attachMoveListeners();
            
            // Riabilita i bottoni
            disableAllButtons(false);
        })
        .catch(error => {
            console.error('Errore nella chiamata AJAX:', error);
            showEmptyMoves();
            disableAllButtons(false);
        });
}

// ============================================================
// FUNZIONE PER AGGIORNARE IL MENU POKEMON
// ============================================================
function updatePokemonMenu() {
    // Ricrea il menu Pokémon con i dati aggiornati
    pokemonMenu.innerHTML = '';
    
    teamData.forEach(pokemon => {
        const hpPercentage = (pokemon.hp / pokemon.max_hp) * 100;
        const selectedClass = (pokemon.slot == currentPokemon.slot) ? ' selected' : '';
        
        const pokemonButton = document.createElement('button');
        pokemonButton.className = 'pokemon-button' + selectedClass;
        pokemonButton.id = 'pokemonSlot' + pokemon.slot;
        pokemonButton.dataset.slot = pokemon.slot;
        pokemonButton.dataset.cod = pokemon.cod;
        pokemonButton.dataset.name = pokemon.name;
        pokemonButton.dataset.level = pokemon.level;
        pokemonButton.dataset.hp = pokemon.hp;
        pokemonButton.dataset.maxhp = pokemon.max_hp;
        pokemonButton.dataset.atk = pokemon.atk;
        pokemonButton.dataset.def = pokemon.def;
        pokemonButton.dataset.spa = pokemon.spa;
        pokemonButton.dataset.spd = pokemon.spd;
        pokemonButton.dataset.spe = pokemon.spe;
        pokemonButton.dataset.secform = pokemon.sec_form;
        
        pokemonButton.innerHTML = `
            <span>${pokemon.name}</span>
            <span class="pokemon-status">Lv${pokemon.level}</span>
            <div class="pokemon-hp-bar">
                <div class="pokemon-hp-fill" style="width: ${hpPercentage}%;"></div>
            </div>
            <span>${pokemon.hp}/${pokemon.max_hp}</span>
        `;
        
        pokemonMenu.appendChild(pokemonButton);
    });
    
    // Aggiungi il bottone BACK
    const backButton = document.createElement('button');
    backButton.className = 'back-button';
    backButton.id = 'backFromPokemonBtn';
    backButton.textContent = '← BACK';
    pokemonMenu.appendChild(backButton);
    
    // Riattacca gli event listener ai nuovi bottoni Pokémon
    attachPokemonListeners();
    
    // Riattacca l'event listener al nuovo bottone BACK
    document.getElementById('backFromPokemonBtn').addEventListener('click', function() {
        if (isActionInProgress) return;
        backToMainMenu();
    });
}

// ============================================================
// FUNZIONE PER ATTACCARE LISTENER AI BOTTONI POKEMON
// ============================================================
function attachPokemonListeners() {
    document.querySelectorAll('.pokemon-button').forEach(button => {
        if(button.id !== 'backFromPokemonBtn') {
            button.addEventListener('click', function() {
                if (isActionInProgress) return;
                const slot = this.dataset.slot;
                const hp = this.dataset.hp;
                const maxhp = this.dataset.maxhp;
                
                console.log('Bottone cliccato - Slot:', slot, 'HP:', hp, 'MaxHP:', maxhp);
                
                if (!hp || !maxhp) {
                    console.error('Dati HP mancanti nel bottone!');
                }
                
                switchPokemon(slot);
            });
        }
    });
}

// ============================================================
// FUNZIONE PER CAMBIARE POKEMON
// ============================================================
function switchPokemon(slot) {
    // Se è già in corso un'azione, non fare niente
    if (isActionInProgress) return;
    
    // Trova il Pokémon selezionato nei teamData
    const selectedPokemon = teamData.find(p => p.slot == slot);
    
    if (!selectedPokemon) {
        console.error('Pokémon non trovato per slot:', slot);
        return;
    }
    
    // Non permettere di selezionare lo stesso Pokémon
    if (selectedPokemon.slot == currentPokemon.slot) {
        alert(currentPokemon.name + ' è già in battaglia!');
        return;
    }
    
    // Imposta che un'azione è in corso
    isActionInProgress = true;
    
    // Disabilita tutti i bottoni
    disableAllButtons(true);
    
    // Aggiorna il Pokémon corrente
    currentPokemon = selectedPokemon;
    
    // Aggiorna l'immagine del Pokémon
    if (playerSprite) {
        playerSprite.src = 'Img/' + currentPokemon.name.toLowerCase() + '.png';
        playerSprite.alt = currentPokemon.name;
    }
    
    // Aggiorna le informazioni visualizzate
    playerName.innerHTML = currentPokemon.name + '<span class="registered">®</span>';
    playerLevel.textContent = 'Lv' + currentPokemon.level;
    playerHpText.textContent = currentPokemon.hp + '/' + currentPokemon.max_hp;
    
    const hpPercentage = (currentPokemon.hp / currentPokemon.max_hp) * 100;
    playerHpBar.style.width = hpPercentage + '%';
    
    // Aggiorna il nome nel question box
    currentPokemonNameSpan.textContent = currentPokemon.name;
    
    // FORZA L'AGGIORNAMENTO DELLE MOSSE
    const cacheBuster = new Date().getTime();
    updateMovesForPokemon(currentPokemon.cod, currentPokemon.sec_form, currentPokemon.slot, cacheBuster);
    
    // Aggiorna il menu Pokémon
    updatePokemonMenu();
    
    // Mostra messaggio di cambio
    questionBox.innerHTML = 'GO!<br>' + currentPokemon.name + '!';
    
    // Torna al menu principale dopo 2 secondi
    setTimeout(() => {
        backToMainMenu();
        // Riabilita i bottoni
        disableAllButtons(false);
        isActionInProgress = false;
    }, 2000);
    
    console.log('Cambiato a:', currentPokemon.name);
}

// ============================================================
// FUNZIONE PER ATTACCARE I LISTENER PRINCIPALI
// ============================================================
function attachMainListeners() {
    // FIGHT - mostra menu mosse
    fightBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        mainMenu.classList.add('hidden');
        movesMenu.classList.add('active');
        pokemonMenu.classList.remove('active');
        questionBox.innerHTML = 'CHOOSE A<br>MOVE';
        
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        const firstMove = document.querySelector('.move-button:not(.disabled)');
        if(firstMove) {
            firstMove.classList.add('selected');
        }
        
        this.classList.add('selected');
    });

    // POKEMON - mostra menu Pokémon
    pokemonBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        mainMenu.classList.add('hidden');
        pokemonMenu.classList.add('active');
        movesMenu.classList.remove('active');
        questionBox.innerHTML = 'CHOOSE A<br>POKÉMON';
        
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        this.classList.add('selected');
    });

    // BAG
    bagBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        this.classList.add('selected');
        questionBox.innerHTML = 'BAG IS<br>EMPTY';
        
        // Imposta che un'azione è in corso
        isActionInProgress = true;
        disableAllButtons(true);
        
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
    });

    // RUN
    runBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        this.classList.add('selected');
        questionBox.innerHTML = "CAN'T ESCAPE!";
        
        // Imposta che un'azione è in corso
        isActionInProgress = true;
        disableAllButtons(true);
        
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
    });

    // BACK dai menu
    backFromMovesBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        backToMainMenu();
    });

    backFromPokemonBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        backToMainMenu();
    });
}
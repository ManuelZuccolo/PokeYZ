let teamData = [];
let enemyTeamData = [];
let idSquadraGiocatore = 0;
let idSquadraAvversario = 0;
let nomeGiocatore = '';
let nomeAvversario = '';

let currentPokemon = null;
let currentEnemyPokemon = null;
let currentEnemyIndex = 0; // Indice del Pokémon nemico corrente nell'array
let enemyMoves = []; // Mosse del Pokémon nemico corrente

let playerSprite, playerName, playerLevel, playerHpText, playerHpBar;
let enemyName, enemyLevel, enemyHpText, enemyHpBar, enemySprite;
let mainMenu, movesMenu, pokemonMenu, questionBox, currentPokemonNameSpan;
let movesColumn1, movesColumn2;
let fightBtn, pokemonBtn, bagBtn, runBtn, backFromMovesBtn, backFromPokemonBtn;

let isActionInProgress = false;
let isEnemyTurn = false;
let isSwitchingPokemon = false; // Flag per il cambio Pokémon
let calcoloDannoReady = false;
let battleTurnOrder = []; // Array per l'ordine di turno
let currentTurnIndex = 0; // Indice del turno corrente
let battleInitialized = false; // Flag per evitare inizializzazioni multiple
let isTurnProcessing = false; // Flag per evitare turni multipli

function initBattle(data) {
    teamData = data.teamData;
    enemyTeamData = data.enemyTeamData;
    idSquadraGiocatore = data.idSquadraGiocatore;
    idSquadraAvversario = data.idSquadraAvversario;
    nomeGiocatore = data.nomeGiocatore;
    nomeAvversario = data.nomeAvversario;
    
    currentPokemon = teamData.find(p => p.slot == data.currentPokemonSlot);
    currentEnemyIndex = 0;
    currentEnemyPokemon = enemyTeamData[currentEnemyIndex];
    
    initDOMReferences();
    
    if (window.calcoloDanno) {
        calcoloDannoReady = true;
        aggiornaStatisticheCalcoloDanno();
    }
    
    attachMoveListeners();
    attachPokemonListeners();
    
    fightBtn.classList.add('selected');
    
    setTimeout(() => {
        updateMovesForPokemon(currentPokemon.cod, currentPokemon.sec_form, currentPokemon.slot);
        caricaMosseNemico();
    }, 100);
    
    // Avvia il primo turno dopo l'inizializzazione (una sola volta)
    if (!battleInitialized) {
        battleInitialized = true;
        setTimeout(() => {
            determinaOrdineTurno();
            prossimoTurno();
        }, 500);
    }
}

function caricaMosseNemico() {
    if (!currentEnemyPokemon) return;
    
    let url = 'get_mosse_nemico.php?cod=' + currentEnemyPokemon.cod + 
              '&sec_form=' + encodeURIComponent(currentEnemyPokemon.sec_form) + 
              '&slot=' + currentEnemyPokemon.slot +
              '&id_squadra=' + idSquadraAvversario;
    
    fetch(url)
        .then(response => response.json())
        .then(mosse => {
            enemyMoves = mosse;
            console.log("Mosse nemiche caricate:", enemyMoves);
        })
        .catch(error => {
            console.error("Errore nel caricamento delle mosse nemiche:", error);
            // Crea mosse di default se non riesce a caricare
            enemyMoves = [{
                id_mossa: 0,
                nome: "ATTACK",
                danno: 40,
                tipo: "Normal",
                accuratezza: 100,
                categoria: "physical"
            }];
        });
}

function aggiornaStatisticheCalcoloDanno() {
    if (!calcoloDannoReady || !window.calcoloDanno) return;
    
    const pokemon1 = {
        atk: currentPokemon.atk,
        def: currentPokemon.def,
        spa: currentPokemon.spa,
        spd: currentPokemon.spd,
        spe: currentPokemon.spe,
        hp: currentPokemon.hp,
        max_hp: currentPokemon.max_hp,
        tipo1: currentPokemon.tipo1,
        tipo2: currentPokemon.tipo2,
        name: currentPokemon.name
    };
    
    const pokemon2 = {
        atk: currentEnemyPokemon.atk,
        def: currentEnemyPokemon.def,
        spa: currentEnemyPokemon.spa,
        spd: currentEnemyPokemon.spd,
        spe: currentEnemyPokemon.spe,
        hp: currentEnemyPokemon.hp,
        max_hp: currentEnemyPokemon.max_hp,
        tipo1: currentEnemyPokemon.tipo1,
        tipo2: currentEnemyPokemon.tipo2,
        name: currentEnemyPokemon.name
    };
    
    window.calcoloDanno.init(
        pokemon1,
        pokemon2,
        updatePlayerHP,
        updateEnemyHP,
        updateBattleMessage,
        onEnemyPokemonFainted // Callback quando il Pokémon nemico viene sconfitto
    );
}

function updatePlayerHP(newHP) {
    if (!currentPokemon) return;
    currentPokemon.hp = newHP;
    playerHpText.textContent = newHP + '/' + currentPokemon.max_hp;
    const hpPercentage = (newHP / currentPokemon.max_hp) * 100;
    playerHpBar.style.width = hpPercentage + '%';
    
    // Se il Pokémon del giocatore è stato sconfitto
    if (newHP <= 0 && !isSwitchingPokemon) {
        onPlayerPokemonFainted();
    }
}

function updateEnemyHP(newHP) {
    if (!currentEnemyPokemon) return;
    currentEnemyPokemon.hp = newHP;
    enemyHpText.textContent = newHP + '/' + currentEnemyPokemon.max_hp;
    const hpPercentage = (newHP / currentEnemyPokemon.max_hp) * 100;
    enemyHpBar.style.width = hpPercentage + '%';
}

function updateBattleMessage(msg) {
    questionBox.innerHTML = msg.replace(/\n/g, '<br>');
}

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
    
    attachMainListeners();
}

function disableAllButtons(disable) {
    // Non disabilitare i pulsanti durante il cambio Pokémon
    if (isSwitchingPokemon) return;
    
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

function backToMainMenu() {
    mainMenu.classList.remove('hidden');
    movesMenu.classList.remove('active');
    pokemonMenu.classList.remove('active');
    
    if (currentPokemon) {
        questionBox.innerHTML = 'WHAT WILL<br>' + currentPokemon.name + ' DO?';
    }
    
    document.querySelectorAll('.command-button, .move-button, .pokemon-button').forEach(btn => {
        if(btn.id !== 'backFromMovesBtn' && btn.id !== 'backFromPokemonBtn') {
            btn.classList.remove('selected');
        }
    });
    
    fightBtn.classList.add('selected');
}

function determinaOrdineTurno() {
    battleTurnOrder = [];
    
    // Aggiungi il Pokémon del giocatore se è vivo
    if (currentPokemon && currentPokemon.hp > 0) {
        battleTurnOrder.push({
            type: 'player',
            pokemon: currentPokemon,
            speed: currentPokemon.spe
        });
    }
    
    // Aggiungi il Pokémon nemico se è vivo
    if (currentEnemyPokemon && currentEnemyPokemon.hp > 0) {
        battleTurnOrder.push({
            type: 'enemy',
            pokemon: currentEnemyPokemon,
            speed: currentEnemyPokemon.spe
        });
    }
    
    // Ordina per velocità (dal più alto al più basso)
    battleTurnOrder.sort((a, b) => b.speed - a.speed);
    
    console.log("Ordine di turno:", battleTurnOrder);
    currentTurnIndex = 0;
}

function prossimoTurno() {
    // Se siamo in modalità cambio o un turno è già in elaborazione, non procedere
    if (isSwitchingPokemon || isTurnProcessing) return;
    
    isTurnProcessing = true;
    
    if (battleTurnOrder.length === 0) {
        determinaOrdineTurno();
    }
    
    // Se non ci sono più turni da eseguire
    if (currentTurnIndex >= battleTurnOrder.length) {
        // Ricomincia il ciclo di turni
        determinaOrdineTurno();
        
        // Se dopo la determinazione non ci sono turni, esci
        if (battleTurnOrder.length === 0) {
            isTurnProcessing = false;
            return;
        }
    }
    
    const turnoCorrente = battleTurnOrder[currentTurnIndex];
    
    // Se il Pokémon di questo turno è morto, passa al prossimo turno senza eseguire azioni
    if (turnoCorrente.pokemon.hp <= 0) {
        console.log("Pokémon morto, salta il turno");
        currentTurnIndex++;
        isTurnProcessing = false;
        prossimoTurno(); // Passa direttamente al prossimo turno
        return;
    }
    
    // Esegui il turno in base al tipo
    if (turnoCorrente.type === 'player') {
        // Turno del giocatore - mostra il menu
        isActionInProgress = false;
        isEnemyTurn = false;
        backToMainMenu();
        disableAllButtons(false);
        isTurnProcessing = false; // Turno completato
    } else {
        // Turno del nemico
        isEnemyTurn = true;
        isActionInProgress = true;
        disableAllButtons(true);
        
        // Esegui la mossa del nemico
        setTimeout(() => {
            eseguiTurnoNemico();
        }, 500);
    }
    
    currentTurnIndex++;
}

function eseguiTurnoNemico() {
    // Se il Pokémon nemico è morto durante l'attesa, passa al prossimo turno
    if (!currentEnemyPokemon || currentEnemyPokemon.hp <= 0 || isSwitchingPokemon) {
        isTurnProcessing = false;
        prossimoTurno();
        return;
    }
    
    // Se non ci sono mosse caricate, usa una mossa di default
    if (!enemyMoves || enemyMoves.length === 0) {
        enemyMoves = [{
            id_mossa: 0,
            nome: "ATTACK",
            danno: 40,
            tipo: "Normal",
            accuratezza: 100,
            categoria: "physical"
        }];
    }
    
    // Seleziona una mossa casuale tra quelle disponibili
    const randomIndex = Math.floor(Math.random() * enemyMoves.length);
    const mossaNemico = enemyMoves[randomIndex];
    
    updateBattleMessage('ENEMY ' + currentEnemyPokemon.name + '<br>USED ' + mossaNemico.nome + '!');
    
    setTimeout(async () => {
        await eseguiMossaNemico(mossaNemico);
        
        // Dopo la mossa del nemico, passa al prossimo turno
        setTimeout(() => {
            isTurnProcessing = false;
            prossimoTurno();
        }, 1500);
    }, 1000);
}

async function eseguiMossaNemico(mossa) {
    return new Promise((resolve) => {
        // Se il Pokémon del giocatore è morto, non eseguire la mossa
        if (!currentPokemon || currentPokemon.hp <= 0) {
            resolve();
            return;
        }
        
        // Determina se è speciale o fisico
        const isSpecial = (mossa.categoria === 'special');
        
        // Calcola il danno
        let attack = isSpecial ? currentEnemyPokemon.spa : currentEnemyPokemon.atk;
        let defense = isSpecial ? currentPokemon.spd : currentPokemon.def;
        
        let level = 50;
        let baseDamage = Math.floor(Math.floor((2 * level / 5 + 2) * attack * mossa.danno / defense) / 50) + 2;
        
        // Calcola efficacia
        let efficacia = 1;
        if (window.calcoloDanno && window.calcoloDanno.calcolaEfficacia) {
            efficacia = window.calcoloDanno.calcolaEfficacia(mossa.tipo, currentPokemon.tipo1, currentPokemon.tipo2);
        }
        
        // Calcola STAB
        let stab = 1;
        if (mossa.tipo.toLowerCase() === currentEnemyPokemon.tipo1.toLowerCase() || 
            (currentEnemyPokemon.tipo2 && mossa.tipo.toLowerCase() === currentEnemyPokemon.tipo2.toLowerCase())) {
            stab = 1.5;
        }
        
        // Controlla precisione
        let colpito = true;
        if (mossa.accuratezza && mossa.accuratezza < 100) {
            let random = Math.random() * 100;
            colpito = random <= mossa.accuratezza;
        }
        
        if (!colpito) {
            updateBattleMessage("Il colpo di " + currentEnemyPokemon.name + " è fallito!");
            setTimeout(() => {
                resolve();
            }, 1500);
            return;
        }
        
        let random = 0.85 + (Math.random() * 0.15);
        
        let danno = Math.floor(baseDamage * stab * efficacia * random);
        danno = Math.max(1, danno);
        
        // Applica il danno al Pokémon del giocatore
        currentPokemon.hp -= danno;
        currentPokemon.hp = Math.max(0, Math.floor(currentPokemon.hp));
        
        updatePlayerHP(currentPokemon.hp);
        
        // Mostra messaggio di efficacia
        if (efficacia >= 2) {
            updateBattleMessage("È superefficace!");
        } else if (efficacia <= 0.5 && efficacia > 0) {
            updateBattleMessage("Non è molto efficace...");
        } else if (efficacia === 0) {
            updateBattleMessage("Non ha effetto...");
        }
        
        setTimeout(() => {
            resolve();
        }, 1500);
    });
}

function onPlayerPokemonFainted() {
    // Cerca il prossimo Pokémon vivo nella squadra del giocatore
    let nextPokemonIndex = -1;
    for (let i = 0; i < teamData.length; i++) {
        if (teamData[i].slot != currentPokemon.slot && teamData[i].hp > 0) {
            nextPokemonIndex = i;
            break;
        }
    }
    
    // Se non ci sono più Pokémon vivi, il giocatore ha perso
    if (nextPokemonIndex === -1) {
        setTimeout(() => {
            updateBattleMessage("Tutti i tuoi Pokémon sono esausti! Hai perso!");
            disableAllButtons(true);
            
            setTimeout(() => {
                // Torna alla pagina precedente
                window.history.back();
            }, 3000);
        }, 2000);
        return;
    }
    
    // Mostra messaggio e permette di scegliere il prossimo Pokémon
    setTimeout(() => {
        updateBattleMessage(currentPokemon.name + " è esausto! Scegli un altro Pokémon!");
        
        // Resetta i flag per permettere il cambio
        isActionInProgress = false;
        isEnemyTurn = false;
        isSwitchingPokemon = true; // Abilita la modalità cambio
        isTurnProcessing = false; // Resetta il flag di elaborazione turno
        
        // Mostra automaticamente il menu Pokémon
        mainMenu.classList.add('hidden');
        pokemonMenu.classList.add('active');
        movesMenu.classList.remove('active');
        
        // Riabilita tutti i pulsanti del menu Pokémon
        document.querySelectorAll('.pokemon-button').forEach(btn => {
            if (btn.id !== 'backFromPokemonBtn') {
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.style.pointerEvents = 'auto';
                btn.disabled = false;
                
                // Disabilita solo i Pokémon con HP 0
                if (btn.dataset.hp <= 0) {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                }
            }
        });
        
        // Riabilita anche il pulsante BACK
        const backBtn = document.getElementById('backFromPokemonBtn');
        if (backBtn) {
            backBtn.style.opacity = '1';
            backBtn.style.cursor = 'pointer';
            backBtn.style.pointerEvents = 'auto';
            backBtn.disabled = false;
        }
        
    }, 2000);
}

function onEnemyPokemonFainted() {
    // Cerca il prossimo Pokémon vivo nella squadra nemica
    let nextEnemyIndex = -1;
    for (let i = currentEnemyIndex + 1; i < enemyTeamData.length; i++) {
        if (enemyTeamData[i].hp > 0) {
            nextEnemyIndex = i;
            break;
        }
    }
    
    // Se non ci sono più Pokémon vivi, l'avversario ha perso
    if (nextEnemyIndex === -1) {
        setTimeout(() => {
            updateBattleMessage("Hai sconfitto " + nomeAvversario + "! Hai vinto la battaglia!");
            disableAllButtons(true);
            
            setTimeout(() => {
                // Torna alla pagina precedente
                window.history.back();
            }, 3000);
        }, 2000);
        return;
    }
    
    // Cambia al prossimo Pokémon
    currentEnemyIndex = nextEnemyIndex;
    currentEnemyPokemon = enemyTeamData[currentEnemyIndex];
    
    // Aggiorna il calcolatore di danno con il nuovo Pokémon
    if (window.calcoloDanno) {
        window.calcoloDanno.aggiornaPokemonNemico({
            atk: currentEnemyPokemon.atk,
            def: currentEnemyPokemon.def,
            spa: currentEnemyPokemon.spa,
            spd: currentEnemyPokemon.spd,
            spe: currentEnemyPokemon.spe,
            hp: currentEnemyPokemon.hp,
            max_hp: currentEnemyPokemon.max_hp,
            tipo1: currentEnemyPokemon.tipo1,
            tipo2: currentEnemyPokemon.tipo2,
            name: currentEnemyPokemon.name
        });
    }
    
    // Aggiorna l'HTML con il nuovo Pokémon
    setTimeout(() => {
        updateBattleMessage(nomeAvversario + " manda " + currentEnemyPokemon.name + "!");
        
        // Aggiorna sprite e informazioni
        if (enemySprite) {
            enemySprite.src = 'Img/' + currentEnemyPokemon.name.toLowerCase() + '.png';
            enemySprite.alt = currentEnemyPokemon.name;
        }
        
        enemyName.innerHTML = currentEnemyPokemon.name + '<span class="registered">®</span>';
        enemyLevel.textContent = 'Lv' + currentEnemyPokemon.level;
        enemyHpText.textContent = currentEnemyPokemon.hp + '/' + currentEnemyPokemon.max_hp;
        
        const hpPercentage = (currentEnemyPokemon.hp / currentEnemyPokemon.max_hp) * 100;
        enemyHpBar.style.width = hpPercentage + '%';
        
        // Carica le mosse del nuovo Pokémon nemico
        caricaMosseNemico();
        
        // Dopo il cambio, ricalcola l'ordine di turno
        setTimeout(() => {
            determinaOrdineTurno();
            isTurnProcessing = false;
            prossimoTurno();
        }, 2000);
    }, 1500);
}

function usaMossa(mossa) {
    if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
    
    isActionInProgress = true;
    disableAllButtons(true);
    
    if (!calcoloDannoReady || !window.calcoloDanno) {
        questionBox.innerHTML = 'ERROR: Damage calculator not ready!';
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
        return;
    }
    
    const isSpecial = (mossa.categoria === 'special');
    updateBattleMessage(currentPokemon.name + '<br>USED ' + mossa.nome + '!');
    
    setTimeout(async () => {
        try {
            await window.calcoloDanno.eseguiMossa(mossa, isSpecial);
            
            // Dopo la mossa del giocatore, passa al prossimo turno
            setTimeout(() => {
                isTurnProcessing = false;
                prossimoTurno();
            }, 1500);
            
        } catch (error) {
            console.error("Errore durante l'esecuzione della mossa:", error);
            setTimeout(() => {
                isTurnProcessing = false;
                prossimoTurno();
            }, 2000);
        }
    }, 1000);
}

function showEmptyMoves() {
    movesColumn1.innerHTML = '';
    movesColumn2.innerHTML = '';
    
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

function attachMoveListeners() {
    const oldButtons = document.querySelectorAll('.move-button');
    oldButtons.forEach(button => {
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
    });
    
    document.querySelectorAll('.move-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
            if (this.disabled) return;
            
            document.querySelectorAll('.move-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            
            const mossa = {
                id: this.dataset.moveId,
                nome: this.textContent.trim(),
                potenza: parseInt(this.dataset.power) || 0,
                tipo: this.dataset.type || 'normal',
                accuratezza: parseInt(this.dataset.accuracy) || 100,
                categoria: this.dataset.category || 'physical',
                effetto: null
            };
            
            usaMossa(mossa);
        });
    });
}

function updateMovesForPokemon(cod, secForm, slot, cacheBuster = null) {
    disableAllButtons(true);
    
    let url = 'get_mosse.php?cod=' + cod + '&sec_form=' + encodeURIComponent(secForm) + '&slot=' + slot;
    if (cacheBuster) {
        url += '&_=' + cacheBuster;
    } else {
        url += '&_=' + new Date().getTime();
    }
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nella risposta del server');
            }
            return response.json();
        })
        .then(mosse => {
            if (mosse.error) {
                showEmptyMoves();
                disableAllButtons(false);
                return;
            }
            
            movesColumn1.innerHTML = '';
            movesColumn2.innerHTML = '';
            
            if (mosse.length === 0) {
                showEmptyMoves();
            } else {
                for (let i = 0; i < Math.min(4, mosse.length); i++) {
                    const move = mosse[i];
                    const moveButton = document.createElement('button');
                    moveButton.className = 'move-button' + (i === 0 ? ' selected' : '');
                    moveButton.setAttribute('data-move-id', move.id_mossa);
                    moveButton.setAttribute('data-move', move.nome.toLowerCase());
                    moveButton.setAttribute('data-power', move.danno);
                    moveButton.setAttribute('data-type', move.tipo.toLowerCase());
                    moveButton.setAttribute('data-accuracy', move.accuratezza);
                    moveButton.setAttribute('data-category', move.categoria);
                    moveButton.textContent = move.nome.toUpperCase();
                    
                    if (i < 2) {
                        movesColumn1.appendChild(moveButton);
                    } else {
                        movesColumn2.appendChild(moveButton);
                    }
                }
                
                if (mosse.length < 2) {
                    for (let i = mosse.length; i < 2; i++) {
                        const emptyButton = document.createElement('button');
                        emptyButton.className = 'move-button disabled';
                        emptyButton.textContent = '-';
                        emptyButton.disabled = true;
                        movesColumn1.appendChild(emptyButton);
                    }
                }
                
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
            
            attachMoveListeners();
            disableAllButtons(false);
        })
        .catch(error => {
            console.error("Errore nel caricamento delle mosse:", error);
            showEmptyMoves();
            disableAllButtons(false);
        });
}

function updatePokemonMenu() {
    pokemonMenu.innerHTML = '';
    
    teamData.forEach(pokemon => {
        const hpPercentage = (pokemon.hp / pokemon.max_hp) * 100;
        const selectedClass = (pokemon.slot == currentPokemon.slot) ? ' selected' : '';
        const disabledClass = (pokemon.hp <= 0) ? ' disabled' : '';
        
        const pokemonButton = document.createElement('button');
        pokemonButton.className = 'pokemon-button' + selectedClass + disabledClass;
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
        pokemonButton.dataset.tipo1 = pokemon.tipo1;
        pokemonButton.dataset.tipo2 = pokemon.tipo2 || '';
        
        if (pokemon.hp <= 0) {
            pokemonButton.disabled = true;
        }
        
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
    
    const backButton = document.createElement('button');
    backButton.className = 'back-button';
    backButton.id = 'backFromPokemonBtn';
    backButton.textContent = '← BACK';
    pokemonMenu.appendChild(backButton);
    
    attachPokemonListeners();
    
    document.getElementById('backFromPokemonBtn').addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn) return;
        // Se siamo in modalità cambio, permetti di tornare al menu principale
        if (isSwitchingPokemon) {
            isSwitchingPokemon = false;
        }
        backToMainMenu();
    });
}

function attachPokemonListeners() {
    document.querySelectorAll('.pokemon-button').forEach(button => {
        if(button.id !== 'backFromPokemonBtn' && !button.disabled) {
            button.addEventListener('click', function() {
                // Permetti il click solo se non siamo in azione OPPURE se siamo in modalità cambio
                if ((isActionInProgress || isEnemyTurn) && !isSwitchingPokemon) return;
                
                const slot = this.dataset.slot;
                switchPokemon(slot);
            });
        }
    });
}

function switchPokemon(slot) {
    // Permetti il cambio solo se non siamo in azione OPPURE se siamo in modalità cambio
    if ((isActionInProgress || isEnemyTurn) && !isSwitchingPokemon) return;
    
    const selectedPokemon = teamData.find(p => p.slot == slot);
    
    if (!selectedPokemon) {
        return;
    }
    
    if (selectedPokemon.slot == currentPokemon.slot) {
        alert(currentPokemon.name + ' è già in battaglia!');
        return;
    }
    
    if (selectedPokemon.hp <= 0) {
        alert(selectedPokemon.name + ' non è in grado di lottare!');
        return;
    }
    
    isActionInProgress = true;
    isSwitchingPokemon = false; // Disabilita la modalità cambio
    disableAllButtons(true);
    
    currentPokemon = selectedPokemon;
    
    if (playerSprite) {
        playerSprite.src = 'Img/' + currentPokemon.name.toLowerCase() + '.png';
        playerSprite.alt = currentPokemon.name;
    }
    
    playerName.innerHTML = currentPokemon.name + '<span class="registered">®</span>';
    playerLevel.textContent = 'Lv' + currentPokemon.level;
    playerHpText.textContent = currentPokemon.hp + '/' + currentPokemon.max_hp;
    
    const hpPercentage = (currentPokemon.hp / currentPokemon.max_hp) * 100;
    playerHpBar.style.width = hpPercentage + '%';
    
    currentPokemonNameSpan.textContent = currentPokemon.name;
    
    aggiornaStatisticheCalcoloDanno();
    
    const cacheBuster = new Date().getTime();
    updateMovesForPokemon(currentPokemon.cod, currentPokemon.sec_form, currentPokemon.slot, cacheBuster);
    
    updatePokemonMenu();
    
    updateBattleMessage('GO!<br>' + currentPokemon.name + '!');
    
    setTimeout(() => {
        // Resetta tutti i flag
        isActionInProgress = false;
        isEnemyTurn = false;
        isTurnProcessing = false;
        
        // Dopo il cambio, ricalcola l'ordine di turno
        determinaOrdineTurno();
        
        // Torna al menu principale
        backToMainMenu();
        disableAllButtons(false);
        
        // Avvia il prossimo turno
        prossimoTurno();
    }, 2000);
}

function attachMainListeners() {
    fightBtn.addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
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

    pokemonBtn.addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
        mainMenu.classList.add('hidden');
        pokemonMenu.classList.add('active');
        movesMenu.classList.remove('active');
        questionBox.innerHTML = 'CHOOSE A<br>POKÉMON';
        
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        this.classList.add('selected');
        
        // Aggiorna il menu Pokémon con gli HP correnti
        updatePokemonMenu();
    });

    bagBtn.addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        this.classList.add('selected');
        questionBox.innerHTML = 'BAG IS<br>EMPTY';
        
        isActionInProgress = true;
        disableAllButtons(true);
        
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
    });

    runBtn.addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
        document.querySelectorAll('.command-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        this.classList.add('selected');
        questionBox.innerHTML = "CAN'T ESCAPE!";
        
        isActionInProgress = true;
        disableAllButtons(true);
        
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 2000);
    });

    backFromMovesBtn.addEventListener('click', function() {
        if (isActionInProgress || isEnemyTurn || isSwitchingPokemon || isTurnProcessing) return;
        backToMainMenu();
    });
}
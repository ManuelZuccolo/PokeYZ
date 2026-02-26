let teamData = [];
let enemyTeamData = [];
let idSquadraGiocatore = 0;
let idSquadraAvversario = 0;
let nomeGiocatore = '';
let nomeAvversario = '';

let currentPokemon = null;
let currentEnemyPokemon = null;

let playerSprite, playerName, playerLevel, playerHpText, playerHpBar;
let enemyName, enemyLevel, enemyHpText, enemyHpBar, enemySprite;
let mainMenu, movesMenu, pokemonMenu, questionBox, currentPokemonNameSpan;
let movesColumn1, movesColumn2;
let fightBtn, pokemonBtn, bagBtn, runBtn, backFromMovesBtn, backFromPokemonBtn;

let isActionInProgress = false;
let calcoloDannoReady = false;

function initBattle(data) {
    teamData = data.teamData;
    enemyTeamData = data.enemyTeamData;
    idSquadraGiocatore = data.idSquadraGiocatore;
    idSquadraAvversario = data.idSquadraAvversario;
    nomeGiocatore = data.nomeGiocatore;
    nomeAvversario = data.nomeAvversario;
    
    currentPokemon = teamData.find(p => p.slot == data.currentPokemonSlot);
    currentEnemyPokemon = enemyTeamData[0];
    
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
    }, 100);
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
        updateBattleMessage
    );
}

function updatePlayerHP(newHP) {
    if (!currentPokemon) return;
    currentPokemon.hp = newHP;
    playerHpText.textContent = newHP + '/' + currentPokemon.max_hp;
    const hpPercentage = (newHP / currentPokemon.max_hp) * 100;
    playerHpBar.style.width = hpPercentage + '%';
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
    questionBox.innerHTML = 'WHAT WILL<br>' + currentPokemon.name + ' DO?';
    
    document.querySelectorAll('.command-button, .move-button, .pokemon-button').forEach(btn => {
        if(btn.id !== 'backFromMovesBtn' && btn.id !== 'backFromPokemonBtn') {
            btn.classList.remove('selected');
        }
    });
    
    fightBtn.classList.add('selected');
}

function usaMossa(mossa) {
    if (isActionInProgress) return;
    
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
    questionBox.innerHTML = currentPokemon.name + '<br>USED ' + mossa.nome + '!';
    
    setTimeout(async () => {
        try {
            const risultato = await window.calcoloDanno.eseguiMossa(mossa, isSpecial);
            
            if (window.calcoloDanno.isPokemon2Morto()) {
                setTimeout(() => {
                    questionBox.innerHTML = 'ENEMY ' + currentEnemyPokemon.name + '<br>FAINTED!';
                    
                    setTimeout(() => {
                        backToMainMenu();
                        disableAllButtons(false);
                        isActionInProgress = false;
                    }, 2000);
                }, 1500);
            } else {
                setTimeout(() => {
                    mossaAvversario();
                }, 2000);
            }
        } catch (error) {
            setTimeout(() => {
                backToMainMenu();
                disableAllButtons(false);
                isActionInProgress = false;
            }, 2000);
        }
    }, 1000);
}

function mossaAvversario() {
    if (!calcoloDannoReady) return;
    
    const mossaFinta = {
        nome: "ATTACK",
        tipo: "Normal",
        potenza: 40,
        accuratezza: 100,
        categoria: "physical"
    };
    
    questionBox.innerHTML = 'ENEMY ' + currentEnemyPokemon.name + '<br>USED ' + mossaFinta.nome + '!';
    
    setTimeout(async () => {
        setTimeout(() => {
            backToMainMenu();
            disableAllButtons(false);
            isActionInProgress = false;
        }, 1500);
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
            
            if (isActionInProgress) return;
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
            showEmptyMoves();
            disableAllButtons(false);
        });
}

function updatePokemonMenu() {
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
        pokemonButton.dataset.tipo1 = pokemon.tipo1;
        pokemonButton.dataset.tipo2 = pokemon.tipo2 || '';
        
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
        if (isActionInProgress) return;
        backToMainMenu();
    });
}

function attachPokemonListeners() {
    document.querySelectorAll('.pokemon-button').forEach(button => {
        if(button.id !== 'backFromPokemonBtn') {
            button.addEventListener('click', function() {
                if (isActionInProgress) return;
                const slot = this.dataset.slot;
                switchPokemon(slot);
            });
        }
    });
}

function switchPokemon(slot) {
    if (isActionInProgress) return;
    
    const selectedPokemon = teamData.find(p => p.slot == slot);
    
    if (!selectedPokemon) {
        return;
    }
    
    if (selectedPokemon.slot == currentPokemon.slot) {
        alert(currentPokemon.name + ' è già in battaglia!');
        return;
    }
    
    isActionInProgress = true;
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
    
    questionBox.innerHTML = 'GO!<br>' + currentPokemon.name + '!';
    
    setTimeout(() => {
        backToMainMenu();
        disableAllButtons(false);
        isActionInProgress = false;
    }, 2000);
}

function attachMainListeners() {
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

    bagBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
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
        if (isActionInProgress) return;
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
        if (isActionInProgress) return;
        backToMainMenu();
    });

    backFromPokemonBtn.addEventListener('click', function() {
        if (isActionInProgress) return;
        backToMainMenu();
    });
}
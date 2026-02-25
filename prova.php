<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon FireRed - Battaglia</title>
    
    <!-- COLLEGA IL FILE CSS ESTERNO -->
    <link rel="stylesheet" href="csscombati.css">
    
    <!-- COLLEGA IL FILE JAVASCRIPT -->
    <script src="calcolodanno.js"></script>
</head>
<body>
    <?php
    // CONFIGURAZIONE DATABASE
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pokeyz_db";

    // Creazione della connessione
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }


    // ============================================================
    // RECUPERO DATI DALLA TABELLA SQUADRA
    // ============================================================
    
    // Recupero l'id_squadra per l'utente 7
    $sql_squadra = "SELECT id_squadra FROM squadra WHERE codice_utente = 11";
    $result_squadra = $conn->query($sql_squadra);
    
    if ($result_squadra === false) {
        die("Errore nella query squadra: " . $conn->error);
    }
    
    if ($result_squadra->num_rows > 0) {
        $row_squadra = $result_squadra->fetch_assoc();
        $id_squadra = $row_squadra['id_squadra'];
        
        // Recupero tutti i Pokémon nella squadra
        $sql_pokemon_squadra = "SELECT p.*, sp.slot 
                                FROM squadra_pokemon sp
                                JOIN pokemon p ON sp.cod = p.cod AND sp.sec_form = p.sec_form
                                WHERE sp.id_squadra = $id_squadra
                                ORDER BY sp.slot";
        
        $result_pokemon_squadra = $conn->query($sql_pokemon_squadra);
        
        if ($result_pokemon_squadra === false) {
            die("Errore nella query pokemon squadra: " . $conn->error);
        }
        
        $team_pokemon = [];
        while($row = $result_pokemon_squadra->fetch_assoc()) {
            $team_pokemon[] = $row;
        }
        
        if (empty($team_pokemon)) {
            die("Nessun Pokémon trovato nella squadra con ID $id_squadra");
        }
    } else {
        die("Nessuna squadra trovata per l'utente 7");
    }

    // ============================================================
    // RECUPERO IL POKEMON NEMICO (Mewtwo)
    // ============================================================
    
    $sql_nemico = "SELECT * FROM pokemon WHERE cod = 150 AND sec_form = 'BASE'";
    $result_nemico = $conn->query($sql_nemico);
    
    if ($result_nemico === false) {
        die("Errore nella query nemico: " . $conn->error);
    }
    
    if ($result_nemico->num_rows > 0) {
        $pokemon_nemico = $result_nemico->fetch_assoc();
    } else {
        die("Mewtwo non trovato nel database");
    }

    // ============================================================
    // IMPOSTAZIONE LIVELLI E HP
    // ============================================================
    
    $livello_base_giocatore = 50;
    
    // Prepara i dati di tutti i Pokémon della squadra
    $team_data = [];
    foreach($team_pokemon as $pokemon) {
        $hp_base = (int) ($pokemon['HP'] * 2 + 31)*50/100+50+10;
        $hp_massimi =(int) $hp_base;
        
        $team_data[] = [
            'cod' => $pokemon['cod'],
            'name' => strtoupper($pokemon['nome']),
            'level' => $livello_base_giocatore,
            'hp' => $hp_massimi, // Inizia con HP pieni
            'max_hp' => $hp_massimi,
            'atk' => (int) ($pokemon['ATK'] * 2 + 31)*50/100+50+10,
            'def' => (int) ($pokemon['DEF'] * 2 + 31)*50/100+50+10,
            'spa' => (int) ($pokemon['SP_ATK'] * 2 + 31)*50/100+50+10,
            'spd' => (int) ($pokemon['SP_DEF'] * 2 + 31)*50/100+50+10,
            'spe' => (int) ($pokemon['SPE'] * 2 + 31)*50/100+50+10,
            'slot' => $pokemon['slot'],
            'sec_form' => $pokemon['sec_form']
        ];
    }
    
    // Il primo Pokémon (slot 1) è quello attuale
    $pokemon_attuale = null;
    foreach($team_data as $pokemon) {
        if($pokemon['slot'] == 1) {
            $pokemon_attuale = $pokemon;
            break;
        }
    }
    
    if (!$pokemon_attuale) {
        $pokemon_attuale = $team_data[0];
    }
    
    // Recupero le mosse del Pokémon attuale
    $sql_mosse = "SELECT m.* FROM mossa m
                  JOIN mossa_x_pokemon mxp ON m.id_mossa = mxp.id_mossa
                  WHERE mxp.cod = " . $pokemon_attuale['cod'] . " 
                  AND mxp.sec_form = '" . $pokemon_attuale['sec_form'] . "'";
    
    $result_mosse = $conn->query($sql_mosse);
    
    if ($result_mosse === false) {
        die("Errore nella query mosse: " . $conn->error);
    }
    
    $mosse_attuali = [];
    while($row = $result_mosse->fetch_assoc()) {
        $mosse_attuali[] = $row;
    }
    ?>
    
    <div class="game-container">
        <div class="battle-screen">
            <!-- AREA POKEMON IN BATTAGLIA -->
            <div class="pokemon-battle-area">
                <!-- POKEMON GIOCATORE (attuale) - in basso a sinistra -->
                <div class="player-pokemon" id="playerPokemonContainer">
                    <div class="sprite-container">
                        <img src="Img/<?php echo strtolower($pokemon_attuale['name']); ?>.png" 
                             alt="<?php echo $pokemon_attuale['name']; ?>"
                             class="pokemon-sprite"
                             onerror="this.src='Img/default.png'; this.classList.add('error');">
                    </div>
                    <div class="info-frame" id="playerInfo">
                        <div class="pokemon-name" id="playerName">
                            <?php echo $pokemon_attuale['name']; ?><span class="registered">®</span>
                        </div>
                        <div class="level-info" id="playerLevel">Lv<?php echo $pokemon_attuale['level']; ?></div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="playerHpText">
                                    <?php echo $pokemon_attuale['hp'] . '/' . $pokemon_attuale['max_hp']; ?>
                                </span>
                            </div>
                            <div class="hp-bar-bg">
                                <div class="hp-bar-fill" id="playerHpBar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POKEMON NEMICO - in alto a destra -->
                <div class="enemy-pokemon">
                    <div class="info-frame enemy-frame" id="enemyInfo">
                        <div class="pokemon-name">
                            <?php echo strtoupper($pokemon_nemico['nome']); ?><span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv50</div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="enemyHpText">
                                    <?php echo $pokemon_nemico['HP']; ?>/<?php echo $pokemon_nemico['HP']; ?>
                                </span>
                            </div>
                            <div class="hp-bar-bg">
                                <div class="hp-bar-fill" id="enemyHpBar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="sprite-container">
                        <img src="Img/<?php echo strtolower($pokemon_nemico['nome']); ?>.png" 
                             alt="<?php echo strtoupper($pokemon_nemico['nome']); ?>"
                             class="pokemon-sprite"
                             onerror="this.src='Img/default.png'; this.classList.add('error');">
                    </div>
                </div>
            </div>

            <!-- AREA COMANDI -->
            <div class="command-area">
                <div class="question-box" id="questionBox">
                    WHAT WILL<br><span id="currentPokemonName"><?php echo $pokemon_attuale['name']; ?></span> DO?
                </div>

                <!-- MENU PRINCIPALE -->
                <div class="main-menu" id="mainMenu">
                    <div class="command-column">
                        <button class="command-button" id="fightBtn">FIGHT</button>
                        <button class="command-button" id="pokemonBtn">POKÉMON</button>
                    </div>
                    <div class="command-column">
                        <button class="command-button" id="bagBtn">BAG</button>
                        <button class="command-button" id="runBtn">RUN</button>
                    </div>
                </div>

                <!-- MENU MOSSE (dinamico) -->
                <div class="moves-menu" id="movesMenu">
                    <div class="moves-column" id="movesColumn1">
                        <?php 
                        for($i = 0; $i < min(2, count($mosse_attuali)); $i++): 
                            $move = $mosse_attuali[$i];
                        ?>
                        <button class="move-button <?php echo $i === 0 ? 'selected' : ''; ?>" 
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <div class="moves-column" id="movesColumn2">
                        <?php for($i = 2; $i < min(4, count($mosse_attuali)); $i++): 
                            $move = $mosse_attuali[$i];
                        ?>
                        <button class="move-button" 
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <button class="back-button" id="backFromMovesBtn">← BACK</button>
                </div>

                <!-- MENU POKEMON -->
                <div class="pokemon-menu" id="pokemonMenu">
                    <?php foreach($team_data as $index => $pokemon): 
                        $hpPercentage = ($pokemon['hp'] / $pokemon['max_hp']) * 100;
                        $selectedClass = ($pokemon['slot'] == $pokemon_attuale['slot']) ? 'selected' : '';
                    ?>
                    <button class="pokemon-button <?php echo $selectedClass; ?>" 
                            id="pokemonSlot<?php echo $pokemon['slot']; ?>"
                            data-slot="<?php echo $pokemon['slot']; ?>"
                            data-cod="<?php echo $pokemon['cod']; ?>"
                            data-name="<?php echo $pokemon['name']; ?>"
                            data-level="<?php echo $pokemon['level']; ?>"
                            data-hp="<?php echo $pokemon['hp']; ?>"
                            data-maxhp="<?php echo $pokemon['max_hp']; ?>"
                            data-atk="<?php echo $pokemon['atk']; ?>"
                            data-def="<?php echo $pokemon['def']; ?>"
                            data-spa="<?php echo $pokemon['spa']; ?>"
                            data-spd="<?php echo $pokemon['spd']; ?>"
                            data-spe="<?php echo $pokemon['spe']; ?>"
                            data-secform="<?php echo $pokemon['sec_form']; ?>">
                        <span><?php echo $pokemon['name']; ?></span>
                        <span class="pokemon-status">Lv<?php echo $pokemon['level']; ?></span>
                        <div class="pokemon-hp-bar">
                            <div class="pokemon-hp-fill" style="width: <?php echo $hpPercentage; ?>%;"></div>
                        </div>
                        <span><?php echo $pokemon['hp']; ?>/<?php echo $pokemon['max_hp']; ?></span>
                    </button>
                    <?php endforeach; ?>
                    <button class="back-button" id="backFromPokemonBtn">← BACK</button>
                </div>
            </div>

            <div class="battle-info">
                <span>▶▼◀▲</span>
                <span>GAME BOY ADVANCE</span>
                <span>SELECT  START</span>
            </div>
        </div>
    </div>

    <script>
        // DATI DEI POKEMON (passati dal PHP)
        const teamData = <?php echo json_encode($team_data); ?>;
        
        // Il Pokémon attuale deve essere preso da teamData per avere tutte le proprietà
        let currentPokemon = teamData.find(p => p.slot == <?php echo $pokemon_attuale['slot']; ?>);
        
        // Riferimento allo sprite del giocatore
        const playerSprite = document.querySelector('.player-pokemon .pokemon-sprite');
        
        const enemyPokemon = {
            name: '<?php echo strtoupper($pokemon_nemico['nome']); ?>',
            cod: <?php echo $pokemon_nemico['cod']; ?>,
            level: 70,
            hp: <?php echo $pokemon_nemico['HP']; ?>,
            maxHp: <?php echo $pokemon_nemico['HP']; ?>,
            atk: <?php echo $pokemon_nemico['ATK']; ?>,
            def: <?php echo $pokemon_nemico['DEF']; ?>,
            spa: <?php echo $pokemon_nemico['SP_ATK']; ?>,
            spd: <?php echo $pokemon_nemico['SP_DEF']; ?>,
            spe: <?php echo $pokemon_nemico['SPE']; ?>
        };

        // Riferimenti agli elementi DOM
        const mainMenu = document.getElementById('mainMenu');
        const movesMenu = document.getElementById('movesMenu');
        const pokemonMenu = document.getElementById('pokemonMenu');
        const questionBox = document.getElementById('questionBox');
        const currentPokemonNameSpan = document.getElementById('currentPokemonName');
        
        // Elementi del Pokémon in battaglia
        const playerName = document.getElementById('playerName');
        const playerLevel = document.getElementById('playerLevel');
        const playerHpText = document.getElementById('playerHpText');
        const playerHpBar = document.getElementById('playerHpBar');
        
        // Colonne mosse
        const movesColumn1 = document.getElementById('movesColumn1');
        const movesColumn2 = document.getElementById('movesColumn2');

        // Bottoni
        const fightBtn = document.getElementById('fightBtn');
        const pokemonBtn = document.getElementById('pokemonBtn');
        const bagBtn = document.getElementById('bagBtn');
        const runBtn = document.getElementById('runBtn');
        const backFromMovesBtn = document.getElementById('backFromMovesBtn');
        const backFromPokemonBtn = document.getElementById('backFromPokemonBtn');

        // ============================================================
        // FUNZIONE PER CAMBIARE POKEMON
        // ============================================================
        function switchPokemon(slot) {
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
            
            // Aggiorna le mosse nel menu FIGHT
            updateMovesForPokemon(currentPokemon.cod, currentPokemon.sec_form);
            
            // Aggiorna la classe selected nel menu Pokémon
            document.querySelectorAll('.pokemon-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            document.getElementById('pokemonSlot' + slot).classList.add('selected');
            
            // Mostra messaggio di cambio
            questionBox.innerHTML = 'GO!<br>' + currentPokemon.name + '!';
            
            // Torna al menu principale dopo 2 secondi
            setTimeout(() => {
                backToMainMenu();
            }, 2000);
            
            console.log('Cambiato a:', currentPokemon.name);
            console.log('HP:', currentPokemon.hp, '/', currentPokemon.max_hp);
        }

        // ============================================================
        // FUNZIONE PER AGGIORNARE LE MOSSE
        // ============================================================
        function updateMovesForPokemon(cod, secForm) {
            // Per ora manteniamo le mosse attuali
            console.log('Caricamento mosse per Pokémon cod:', cod, 'secForm:', secForm);
            
            // In una implementazione reale, qui faresti una chiamata AJAX
            // Per ora mostriamo solo un messaggio
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
            console.log('Mossa usata:', mossa);
            console.log('Pokemon attuale:', currentPokemon);
            console.log('Pokemon nemico:', enemyPokemon);
            
            questionBox.innerHTML = currentPokemon.name + '<br>USED ' + mossa.nome + '!';
            
            // Qui implementerai la logica di danno
            setTimeout(() => {
                backToMainMenu();
            }, 2000);
        }

        // ============================================================
        // FUNZIONE PER ATTACCARE GLI EVENT LISTENER ALLE MOSSE
        // ============================================================
        function attachMoveListeners() {
            document.querySelectorAll('.move-button').forEach(button => {
                button.addEventListener('click', function() {
                    if(this.id !== 'backFromMovesBtn') {
                        document.querySelectorAll('.move-button').forEach(btn => {
                            btn.classList.remove('selected');
                        });
                        this.classList.add('selected');
                        
                        const mossa = {
                            nome: this.textContent.trim(),
                            potenza: parseInt(this.dataset.power) || 0,
                            tipo: this.dataset.type || 'normale',
                            accuratezza: parseInt(this.dataset.accuracy) || 100
                        };
                        
                        usaMossa(mossa);
                    }
                });
            });
        }

        // ============================================================
        // VERIFICA INIZIALE DEI DATI
        // ============================================================
        console.log('=== VERIFICA DATI INIZIALI ===');
        console.log('Team Data:', teamData);
        console.log('Current Pokemon:', currentPokemon);
        console.log('HP currentPokemon:', currentPokemon.hp);
        console.log('Max HP currentPokemon:', currentPokemon.max_hp);
        console.log('==============================');

        // ============================================================
        // EVENT LISTENER
        // ============================================================
        
        // FIGHT - mostra menu mosse
        fightBtn.addEventListener('click', function() {
            mainMenu.classList.add('hidden');
            movesMenu.classList.add('active');
            pokemonMenu.classList.remove('active');
            questionBox.innerHTML = 'CHOOSE A<br>MOVE';
            
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            const firstMove = document.querySelector('.move-button');
            if(firstMove) {
                firstMove.classList.add('selected');
            }
            
            this.classList.add('selected');
        });

        // POKEMON - mostra menu Pokémon
        pokemonBtn.addEventListener('click', function() {
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
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = 'BAG IS<br>EMPTY';
            setTimeout(backToMainMenu, 2000);
        });

        // RUN
        runBtn.addEventListener('click', function() {
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = "CAN'T ESCAPE!";
            setTimeout(backToMainMenu, 2000);
        });

        // BACK dai menu
        backFromMovesBtn.addEventListener('click', backToMainMenu);
        backFromPokemonBtn.addEventListener('click', backToMainMenu);

        // Event listener per i Pokémon nel menu di cambio
        document.querySelectorAll('.pokemon-button').forEach(button => {
            if(button.id !== 'backFromPokemonBtn') {
                button.addEventListener('click', function() {
                    const slot = this.dataset.slot;
                    const hp = this.dataset.hp;
                    const maxhp = this.dataset.maxhp;
                    
                    console.log('Bottone cliccato - Slot:', slot, 'HP:', hp, 'MaxHP:', maxhp);
                    
                    // Verifica che i dati siano presenti
                    if (!hp || !maxhp) {
                        console.error('Dati HP mancanti nel bottone!');
                        console.log('Dataset completo:', this.dataset);
                    }
                    
                    switchPokemon(slot);
                });
            }
        });

        // Seleziona FIGHT di default
        fightBtn.classList.add('selected');
        
        // Attacca listener alle mosse iniziali
        attachMoveListeners();

        // ============================================================
        // DEBUG - Mostra tutti i data attributes dei bottoni Pokémon
        // ============================================================
        document.querySelectorAll('.pokemon-button').forEach(button => {
            if(button.id !== 'backFromPokemonBtn') {
                console.log('Pokemon button data:', {
                    slot: button.dataset.slot,
                    name: button.dataset.name,
                    hp: button.dataset.hp,
                    maxhp: button.dataset.maxhp
                });
            }
        });
    </script>
</body>
</html>
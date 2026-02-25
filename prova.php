<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon FireRed - Battaglia</title>
    
    <!-- COLLEGA IL FILE CSS ESTERNO -->
    <link rel="stylesheet" href="csscombati">
    
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

    echo "<!-- Connessione al database riuscita -->\n";

    // ============================================================
    // RECUPERO DATI DALLA TABELLA SQUADRA
    // ============================================================
    
    // 1. Prima recupero l'id_squadra per l'utente 7
    $sql_squadra = "SELECT id_squadra FROM squadra WHERE codice_utente = 7";
    echo "<!-- Query squadra: $sql_squadra -->\n";
    
    $result_squadra = $conn->query($sql_squadra);
    
    if ($result_squadra === false) {
        die("Errore nella query squadra: " . $conn->error);
    }
    
    if ($result_squadra->num_rows > 0) {
        $row_squadra = $result_squadra->fetch_assoc();
        $id_squadra = $row_squadra['id_squadra'];
        echo "<!-- ID squadra trovato: $id_squadra -->\n";
        
        // 2. Recupero tutti i Pokémon nella squadra con i loro dettagli
        // NOTA: Ho rimosso 'sp.livello' perché non esiste, useremo il livello base dal database
        $sql_pokemon_squadra = "SELECT p.*, sp.slot 
                                FROM squadra_pokemon sp
                                JOIN pokemon p ON sp.cod = p.cod AND sp.sec_form = p.sec_form
                                WHERE sp.id_squadra = $id_squadra
                                ORDER BY sp.slot";
        
        echo "<!-- Query pokemon squadra: $sql_pokemon_squadra -->\n";
        
        $result_pokemon_squadra = $conn->query($sql_pokemon_squadra);
        
        if ($result_pokemon_squadra === false) {
            die("Errore nella query pokemon squadra: " . $conn->error);
        }
        
        $team_pokemon = [];
        while($row = $result_pokemon_squadra->fetch_assoc()) {
            $team_pokemon[] = $row;
            echo "<!-- Trovato Pokémon: " . $row['nome'] . " (slot " . $row['slot'] . ") -->\n";
        }
        
        if (empty($team_pokemon)) {
            die("Nessun Pokémon trovato nella squadra con ID $id_squadra");
        }
    } else {
        die("Nessuna squadra trovata per l'utente 7. Verifica che nella tabella squadra ci sia un record con codice_utente = 7");
    }

    // ============================================================
    // RECUPERO IL PRIMO POKEMON DELLA SQUADRA PER IL COMBATTIMENTO
    // ============================================================
    
    // Prendo il primo Pokémon della squadra (slot 1)
    $pokemon_giocatore = null;
    foreach($team_pokemon as $pokemon) {
        if($pokemon['slot'] == 1) {
            $pokemon_giocatore = $pokemon;
            break;
        }
    }
    
    if (!$pokemon_giocatore) {
        // Se non c'è Pokémon nello slot 1, prendo il primo disponibile
        $pokemon_giocatore = $team_pokemon[0];
        echo "<!-- Attenzione: Nessun Pokémon nello slot 1, usando " . $pokemon_giocatore['nome'] . " -->\n";
    }

    echo "<!-- Pokémon giocatore: " . $pokemon_giocatore['nome'] . " -->\n";

    // RECUPERO LE MOSSE DEL POKEMON GIOCATORE
    $sql_mosse = "SELECT m.* FROM mossa m
                  JOIN mossa_x_pokemon mxp ON m.id_mossa = mxp.id_mossa
                  WHERE mxp.cod = " . $pokemon_giocatore['cod'] . " 
                  AND mxp.sec_form = '" . $pokemon_giocatore['sec_form'] . "'";
    
    echo "<!-- Query mosse: $sql_mosse -->\n";
    
    $result_mosse = $conn->query($sql_mosse);
    
    if ($result_mosse === false) {
        die("Errore nella query mosse: " . $conn->error);
    }
    
    $pokemon_mosse = [];
    while($row = $result_mosse->fetch_assoc()) {
        $pokemon_mosse[] = $row;
        echo "<!-- Mossa trovata: " . $row['nome'] . " (danno: " . $row['danno'] . ") -->\n";
    }

    // ============================================================
    // RECUPERO IL POKEMON NEMICO (fisso Mewtwo per ora)
    // ============================================================
    
    $sql_nemico = "SELECT * FROM pokemon WHERE cod = 150 AND sec_form = 'BASE'";
    echo "<!-- Query nemico: $sql_nemico -->\n";
    
    $result_nemico = $conn->query($sql_nemico);
    
    if ($result_nemico === false) {
        die("Errore nella query nemico: " . $conn->error);
    }
    
    if ($result_nemico->num_rows > 0) {
        $pokemon_nemico = $result_nemico->fetch_assoc();
        echo "<!-- Pokémon nemico: " . $pokemon_nemico['nome'] . " -->\n";
    } else {
        die("Mewtwo non trovato nel database (cod=150, sec_form='BASE')");
    }

    // ============================================================
    // IMPOSTAZIONE LIVELLI (fissi per ora)
    // ============================================================
    
    // Livello fisso per tutti i Pokémon del giocatore
    $livello_base_giocatore = 50;
    
    // ============================================================
    // CALCOLO HP E PREPARAZIONE ARRAY TEAM PER IL MENU
    // ============================================================
    
    $team_menu = [];
    foreach($team_pokemon as $pokemon) {
        // Calcolo HP massimi in base al livello fisso
        // Formula: HP_max = HP_base + (livello * 2)
        $hp_base = $pokemon['HP'];
        $hp_massimi = $hp_base + ($livello_base_giocatore * 2);
        
        // Tutti i Pokémon partono con HP pieni
        $hp_attuali = $hp_massimi;
        
        $team_menu[] = [
            'name' => strtoupper($pokemon['nome']),
            'level' => $livello_base_giocatore,
            'hp' => $hp_attuali,
            'max_hp' => $hp_massimi,
            'slot' => $pokemon['slot']
        ];
    }
    
    // Calcolo HP per il Pokémon giocatore in battaglia
    $hp_base_giocatore = $pokemon_giocatore['HP'];
    $hp_massimi_giocatore = $hp_base_giocatore + ($livello_base_giocatore * 2);
    $hp_attuali_giocatore = $hp_massimi_giocatore;
    $percentuale_hp_giocatore = 100;
    
    echo "<!-- Team preparato con " . count($team_menu) . " Pokémon (livello fisso: $livello_base_giocatore) -->\n";
    ?>
    
    <div class="game-container">
        <div class="battle-screen">
            <!-- AREA POKEMON IN BATTAGLIA -->
            <div class="pokemon-battle-area">
                <!-- POKEMON GIOCATORE (dalla squadra) - in basso a sinistra -->
                <div class="player-pokemon">
                    <div class="sprite-container">
                        <div class="sprite-placeholder"></div>
                    </div>
                    <div class="info-frame" id="playerInfo">
                        <div class="pokemon-name">
                            <?php echo strtoupper($pokemon_giocatore['nome']); ?><span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv<?php echo $livello_base_giocatore; ?></div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="playerHpText">
                                    <?php echo $hp_attuali_giocatore . '/' . $hp_massimi_giocatore; ?>
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
                        <div class="level-info">Lv70</div>
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
                        <div class="sprite-placeholder"></div>
                    </div>
                </div>
            </div>

            <!-- AREA COMANDI -->
            <div class="command-area">
                <div class="question-box" id="questionBox">
                    WHAT WILL<br><?php echo strtoupper($pokemon_giocatore['nome']); ?> DO?
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

                <!-- MENU MOSSE (del Pokémon giocatore) -->
                <div class="moves-menu" id="movesMenu">
                    <?php if (count($pokemon_mosse) > 0): ?>
                    <div class="moves-column">
                        <?php 
                        for($i = 0; $i < min(2, count($pokemon_mosse)); $i++): 
                            $move = $pokemon_mosse[$i];
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
                    <div class="moves-column">
                        <?php for($i = 2; $i < min(4, count($pokemon_mosse)); $i++): 
                            $move = $pokemon_mosse[$i];
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
                    <?php else: ?>
                    <div class="no-moves">NESSUNA MOSSA DISPONIBILE</div>
                    <?php endif; ?>
                    <button class="back-button" id="backFromMovesBtn">← BACK</button>
                </div>

                <!-- MENU POKEMON (con tutta la squadra) -->
                <div class="pokemon-menu" id="pokemonMenu">
                    <?php foreach($team_menu as $index => $pokemon): 
                        $hpPercentage = ($pokemon['hp'] / $pokemon['max_hp']) * 100;
                        $selectedClass = ($index === 0) ? 'selected' : '';
                        // Tutti i Pokémon hanno HP pieni all'inizio
                        $disabled = '';
                    ?>
                    <button class="pokemon-button <?php echo $selectedClass; ?>" 
                            id="pokemon<?php echo $pokemon['slot']; ?>"
                            data-slot="<?php echo $pokemon['slot']; ?>"
                            data-hp="<?php echo $pokemon['hp']; ?>"
                            data-maxhp="<?php echo $pokemon['max_hp']; ?>"
                            <?php echo $disabled; ?>>
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
        // PASSAGGIO DATI DAL PHP AL JAVASCRIPT
        const playerPokemon = {
            name: '<?php echo strtoupper($pokemon_giocatore['nome']); ?>',
            cod: <?php echo $pokemon_giocatore['cod']; ?>,
            level: <?php echo $livello_base_giocatore; ?>,
            hp: <?php echo $hp_attuali_giocatore; ?>,
            maxHp: <?php echo $hp_massimi_giocatore; ?>,
            atk: <?php echo $pokemon_giocatore['ATK']; ?>,
            def: <?php echo $pokemon_giocatore['DEF']; ?>,
            spa: <?php echo $pokemon_giocatore['SP_ATK']; ?>,
            spd: <?php echo $pokemon_giocatore['SP_DEF']; ?>,
            spe: <?php echo $pokemon_giocatore['SPE']; ?>
        };
        
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

        // DATI DELLA SQUADRA COMPLETA
        const teamData = <?php echo json_encode($team_menu); ?>;

        console.log('Player Pokémon:', playerPokemon);
        console.log('Enemy Pokémon:', enemyPokemon);
        console.log('Team Data:', teamData);

        // Riferimenti ai menu
        const mainMenu = document.getElementById('mainMenu');
        const movesMenu = document.getElementById('movesMenu');
        const pokemonMenu = document.getElementById('pokemonMenu');
        const questionBox = document.getElementById('questionBox');

        // Riferimenti ai bottoni
        const fightBtn = document.getElementById('fightBtn');
        const pokemonBtn = document.getElementById('pokemonBtn');
        const bagBtn = document.getElementById('bagBtn');
        const runBtn = document.getElementById('runBtn');
        const backFromMovesBtn = document.getElementById('backFromMovesBtn');
        const backFromPokemonBtn = document.getElementById('backFromPokemonBtn');

        // Funzione per tornare al menu principale
        function backToMainMenu() {
            mainMenu.classList.remove('hidden');
            movesMenu.classList.remove('active');
            pokemonMenu.classList.remove('active');
            questionBox.innerHTML = 'WHAT WILL<br>' + playerPokemon.name + ' DO?';
            
            document.querySelectorAll('.command-button, .move-button, .pokemon-button').forEach(btn => {
                if(btn.id !== 'backFromMovesBtn' && btn.id !== 'backFromPokemonBtn') {
                    btn.classList.remove('selected');
                }
            });
            
            fightBtn.classList.add('selected');
        }

        // Mostra il menu delle mosse
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

        // Mostra il menu Pokémon
        pokemonBtn.addEventListener('click', function() {
            mainMenu.classList.add('hidden');
            pokemonMenu.classList.add('active');
            movesMenu.classList.remove('active');
            questionBox.innerHTML = 'CHOOSE A<br>POKÉMON';
            
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Seleziona il primo Pokémon
            const firstPokemon = document.querySelector('.pokemon-button');
            if(firstPokemon) {
                firstPokemon.classList.add('selected');
            }
            
            this.classList.add('selected');
        });

        // Gestione BAG
        bagBtn.addEventListener('click', function() {
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = 'BAG IS<br>EMPTY';
            setTimeout(backToMainMenu, 2000);
        });

        // Gestione RUN
        runBtn.addEventListener('click', function() {
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = 'CAN\'T ESCAPE!';
            setTimeout(backToMainMenu, 2000);
        });

        // Torna indietro
        backFromMovesBtn.addEventListener('click', backToMainMenu);
        backFromPokemonBtn.addEventListener('click', backToMainMenu);

        fightBtn.classList.add('selected');

        // Funzione per usare una mossa
        function usaMossa(mossa) {
            console.log('Mossa usata:', mossa);
            alert('Hai usato ' + mossa.nome + '! (Danno: ' + mossa.potenza + ')');
            // Qui implementerai la logica di combattimento
        }

        // Event listener per le mosse
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

        // Event listener per i Pokémon
        document.querySelectorAll('.pokemon-button').forEach(button => {
            if(button.id !== 'backFromPokemonBtn') {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.pokemon-button').forEach(btn => {
                        if(btn.id !== 'backFromPokemonBtn') {
                            btn.classList.remove('selected');
                        }
                    });
                    this.classList.add('selected');
                    
                    const slot = this.dataset.slot;
                    const pokemonName = this.querySelector('span:first-child').textContent;
                    
                    questionBox.innerHTML = 'GO!<br>' + pokemonName + '!';
                    
                    console.log('Cambio con Pokémon slot:', slot);
                    
                    setTimeout(backToMainMenu, 2000);
                });
            }
        });
    </script>
</body>
</html>
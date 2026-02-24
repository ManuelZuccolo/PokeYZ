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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pokeyz_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Recupera Charmeleon (cod = 5)
    $sql = "SELECT * FROM pokemon WHERE cod = 5 AND sec_form = 'BASE'";
    $result = $conn->query($sql);
    $charmeleon = $result->fetch_assoc();

    // Recupera Mewtwo (cod = 150)
    $sql = "SELECT * FROM pokemon WHERE cod = 150 AND sec_form = 'BASE'";
    $result = $conn->query($sql);
    $mewtwo = $result->fetch_assoc();

    // Recupera le mosse di Charmeleon
    $sql = "SELECT m.* FROM mossa m
            JOIN mossa_x_pokemon mxp ON m.id_mossa = mxp.id_mossa
            WHERE mxp.cod = 5 AND mxp.sec_form = 'BASE'";
    $result = $conn->query($sql);
    $charmeleon_moves = [];
    while($row = $result->fetch_assoc()) {
        $charmeleon_moves[] = $row;
    }

    // Recupera la squadra dell'utente (id_utente = 7)
    $sql = "SELECT p.*, sp.slot FROM squadra s
            JOIN squadra_pokemon sp ON s.id_squadra = sp.id_squadra
            JOIN pokemon p ON sp.cod = p.cod AND sp.sec_form = p.sec_form
            WHERE s.codice_utente = 7
            ORDER BY sp.slot";
    $result = $conn->query($sql);
    $team_pokemon = [];
    while($row = $result->fetch_assoc()) {
        $team_pokemon[] = $row;
    }

    // Prepara l'array team per il menu Pokémon
    $team = [];
    foreach($team_pokemon as $pokemon) {
        $team[] = [
            'name' => strtoupper($pokemon['nome']),
            'level' => 50,
            'hp' => $pokemon['HP'],
            'max_hp' => $pokemon['HP']
        ];
    }
    ?>
    
    <div class="game-container">
        <div class="battle-screen">
            <!-- Area Pokémon con posizionamento personalizzato -->
            <div class="pokemon-battle-area">
                <!-- CHARMELEON (giocatore) - in basso a sinistra -->
                <div class="player-pokemon">
                    <!-- Sprite a sinistra -->
                    <div class="sprite-container">
                        <div class="sprite-placeholder"></div>
                    </div>
                    <!-- Info HP a destra -->
                    <div class="info-frame" id="playerInfo">
                        <div class="pokemon-name">
                            <?php echo strtoupper($charmeleon['nome']); ?><span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv50</div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="playerHpText"><?php echo $charmeleon['HP']; ?>/<?php echo $charmeleon['HP']; ?></span>
                            </div>
                            <div class="hp-bar-bg">
                                <div class="hp-bar-fill" id="playerHpBar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MEWTWO (nemico) - in alto a destra -->
                <div class="enemy-pokemon">
                    <!-- Info HP a sinistra -->
                    <div class="info-frame enemy-frame" id="enemyInfo">
                        <div class="pokemon-name">
                            <?php echo strtoupper($mewtwo['nome']); ?><span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv70</div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="enemyHpText"><?php echo $mewtwo['HP']; ?>/<?php echo $mewtwo['HP']; ?></span>
                            </div>
                            <div class="hp-bar-bg">
                                <div class="hp-bar-fill" id="enemyHpBar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Sprite a destra -->
                    <div class="sprite-container">
                        <div class="sprite-placeholder"></div>
                    </div>
                </div>
            </div>

            <!-- Area comandi: domanda + bottoni -->
            <div class="command-area">
                <!-- Barra domanda -->
                <div class="question-box" id="questionBox">
                    WHAT WILL<br><?php echo strtoupper($charmeleon['nome']); ?> DO?
                </div>

                <!-- Menu principale -->
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

                <!-- Menu mosse per Charmeleon (dinamico dal database) -->
                <div class="moves-menu" id="movesMenu">
                    <div class="moves-column">
                        <?php 
                        for($i = 0; $i < min(2, count($charmeleon_moves)); $i++): 
                            $move = $charmeleon_moves[$i];
                        ?>
                        <button class="move-button <?php echo $i === 0 ? 'selected' : ''; ?>" 
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>" 
                                data-effect="">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <div class="moves-column">
                        <?php for($i = 2; $i < min(4, count($charmeleon_moves)); $i++): 
                            $move = $charmeleon_moves[$i];
                        ?>
                        <button class="move-button" 
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>" 
                                data-effect="">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <button class="back-button" id="backFromMovesBtn">← BACK</button>
                </div>

                <!-- Menu Pokémon con dati dal database -->
                <div class="pokemon-menu" id="pokemonMenu">
                    <?php foreach($team as $index => $pokemon): 
                        $hpPercentage = ($pokemon['hp'] / $pokemon['max_hp']) * 100;
                        $selectedClass = ($index === 0) ? 'selected' : '';
                    ?>
                    <button class="pokemon-button <?php echo $selectedClass; ?>" id="pokemon<?php echo $index + 1; ?>">
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

            <!-- Info extra -->
            <div class="battle-info">
                <span>▶▼◀▲</span>
                <span>GAME BOY ADVANCE</span>
                <span>SELECT  START</span>
            </div>
        </div>
    </div>

    <script>
        // Passa i dati dal PHP al JavaScript
        const playerPokemon = {
            name: '<?php echo strtoupper($charmeleon['nome']); ?>',
            hp: <?php echo $charmeleon['HP']; ?>,
            maxHp: <?php echo $charmeleon['HP']; ?>,
            atk: <?php echo $charmeleon['ATK']; ?>,
            def: <?php echo $charmeleon['DEF']; ?>,
            spa: <?php echo $charmeleon['SP_ATK']; ?>,
            spd: <?php echo $charmeleon['SP_DEF']; ?>,
            spe: <?php echo $charmeleon['SPE']; ?>
        };
        
        const enemyPokemon = {
            name: '<?php echo strtoupper($mewtwo['nome']); ?>',
            hp: <?php echo $mewtwo['HP']; ?>,
            maxHp: <?php echo $mewtwo['HP']; ?>,
            atk: <?php echo $mewtwo['ATK']; ?>,
            def: <?php echo $mewtwo['DEF']; ?>,
            spa: <?php echo $mewtwo['SP_ATK']; ?>,
            spd: <?php echo $mewtwo['SP_DEF']; ?>,
            spe: <?php echo $mewtwo['SPE']; ?>
        };

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
            
            // Rimuovi selected da tutti i bottoni
            document.querySelectorAll('.command-button, .move-button, .pokemon-button').forEach(btn => {
                if(btn.id !== 'backFromMovesBtn' && btn.id !== 'backFromPokemonBtn') {
                    btn.classList.remove('selected');
                }
            });
            
            // Seleziona FIGHT di default
            fightBtn.classList.add('selected');
        }

        // Mostra il menu delle mosse quando si clicca FIGHT
        fightBtn.addEventListener('click', function() {
            mainMenu.classList.add('hidden');
            movesMenu.classList.add('active');
            pokemonMenu.classList.remove('active');
            questionBox.innerHTML = 'CHOOSE A<br>MOVE';
            
            // Rimuovi selected da tutti i bottoni del menu principale
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Seleziona la prima mossa di default
            document.querySelectorAll('.move-button')[0].classList.add('selected');
            this.classList.add('selected');
        });

        // Mostra il menu Pokémon quando si clicca POKÉMON
        pokemonBtn.addEventListener('click', function() {
            mainMenu.classList.add('hidden');
            pokemonMenu.classList.add('active');
            movesMenu.classList.remove('active');
            questionBox.innerHTML = 'CHOOSE A<br>POKÉMON';
            
            // Rimuovi selected da tutti i bottoni del menu principale
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Seleziona il primo Pokémon di default
            document.getElementById('pokemon1').classList.add('selected');
            this.classList.add('selected');
        });

        // Gestione BAG
        bagBtn.addEventListener('click', function() {
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = 'BAG IS<br>EMPTY';
            
            // Dopo 2 secondi torna al menu principale
            setTimeout(backToMainMenu, 2000);
        });

        // Gestione RUN
        runBtn.addEventListener('click', function() {
            document.querySelectorAll('.command-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            questionBox.innerHTML = 'CAN\'T ESCAPE!';
            
            // Dopo 2 secondi torna al menu principale
            setTimeout(backToMainMenu, 2000);
        });

        // Torna al menu principale dal menu mosse
        backFromMovesBtn.addEventListener('click', backToMainMenu);

        // Torna al menu principale dal menu Pokémon
        backFromPokemonBtn.addEventListener('click', backToMainMenu);

        // Seleziona FIGHT di default all'avvio
        fightBtn.classList.add('selected');

        // Funzioni per il calcolo del danno (da collegare)
        function usaMossa(mossa) {
            console.log('Mossa usata:', mossa);
            // Qui andrà la logica di calcolo del danno
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
                        effetto: this.dataset.effect || ''
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
                    
                    const pokemonName = this.textContent.split('\n')[0].trim();
                    console.log('Pokémon selezionato:', pokemonName);
                    questionBox.innerHTML = 'GO!<br>' + pokemonName + '!';
                    
                    setTimeout(backToMainMenu, 2000);
                });
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon FireRed - Battaglia</title>
    
    <!-- COLLEGA IL FILE JAVASCRIPT -->
    <script src="calcolodanno.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            image-rendering: pixelated;
            image-rendering: crisp-edges;
        }

        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Press Start 2P', 'Courier New', monospace;
            padding: 20px;
        }

        .game-container {
            background-color: #9bbc7a;
            width: 900px;
            padding: 25px 20px 20px 20px;
            border: 8px solid #4a4a4a;
            border-radius: 15px 15px 25px 25px;
            box-shadow: 0 0 0 5px #7a7a7a, 0 20px 30px rgba(0,0,0,0.7);
        }

        .battle-screen {
            background-color: #b0d08c;
            padding: 20px;
            border: 3px solid #306850;
            min-height: 500px;
            display: flex;
            flex-direction: column;
        }

        /* Area Pokémon - layout personalizzato */
        .pokemon-battle-area {
            position: relative;
            height: 320px;
            margin-bottom: 20px;
        }

        /* Pokémon giocatore (Charmeleon) - in basso a sinistra */
        .player-pokemon {
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            gap: 15px;
        }

        /* Pokémon nemico (Mewtwo) - in alto a destra */
        .enemy-pokemon {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 15px;
        }

        /* Sprite container */
        .sprite-container {
            width: 140px;
            height: 140px;
            background-color: #306850;
            border: 4px solid #f0f8d8;
            box-shadow: 4px 4px 0 #1e3f2a;
            display: flex;
            align-items: center;
            justify-content: center;
            image-rendering: pixelated;
            overflow: hidden;
        }

        .sprite-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2a5a2a;
            color: #9bbc7a;
            font-size: 12px;
            text-align: center;
        }

        .sprite-placeholder::before {
            content: "🧩 SPRITE";
            display: block;
        }

        /* Cornici info Pokémon */
        .info-frame {
            background-color: #f0f8d8;
            border: 4px solid #306850;
            padding: 12px;
            box-shadow: 4px 4px 0 #1e3f2a;
            width: 220px;
            align-self: center;
        }

        .enemy-frame {
            border-color: #b84030;
            box-shadow: 4px 4px 0 #6b281e;
        }

        .pokemon-name {
            font-size: 18px;
            color: #181818;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .registered {
            font-size: 10px;
            vertical-align: super;
        }

        .level-info {
            font-size: 14px;
            color: #181818;
            margin-bottom: 8px;
        }

        /* Barra HP */
        .hp-container {
            margin: 8px 0;
        }

        .hp-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3px;
        }

        .hp-label {
            font-size: 14px;
            color: #d82828;
        }

        .hp-numbers {
            font-size: 12px;
            color: #181818;
        }

        .hp-bar-bg {
            width: 100%;
            height: 8px;
            background-color: #707070;
            border: 2px solid #383838;
        }

        .hp-bar-fill {
            width: 100%;
            height: 100%;
            background-color: #38b848;
        }

        /* Area comandi inferiore */
        .command-area {
            display: flex;
            margin-top: auto;
            border: 4px solid #306850;
            background-color: #f0f8d8;
            box-shadow: 6px 6px 0 #1e3f2a;
            min-height: 150px;
        }

        /* Barra domanda */
        .question-box {
            background-color: #f0f8d8;
            padding: 20px;
            font-size: 16px;
            color: #181818;
            text-transform: uppercase;
            border-right: 4px solid #306850;
            width: 60%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.5;
        }

        /* Menu principale (FIGHT, POKÉMON, BAG, RUN) */
        .main-menu {
            width: 40%;
            display: flex;
            padding: 15px;
            gap: 15px;
            background-color: #d0e0b0;
        }

        /* Menu mosse (appare quando si clicca FIGHT) */
        .moves-menu {
            width: 40%;
            display: none;
            padding: 15px;
            gap: 15px;
            background-color: #d0e0b0;
            grid-template-columns: 1fr 1fr;
        }

        /* Menu Pokémon (appare quando si clicca POKÉMON) */
        .pokemon-menu {
            width: 40%;
            display: none;
            padding: 15px;
            background-color: #d0e0b0;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            overflow-y: auto;
            max-height: 250px;
        }

        .moves-menu.active {
            display: grid;
        }

        .pokemon-menu.active {
            display: grid;
        }

        .main-menu.hidden {
            display: none;
        }

        .command-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 50%;
        }

        .moves-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .command-button {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 18px;
            color: #181818;
            background: none;
            border: 3px solid #306850;
            padding: 12px 8px;
            cursor: pointer;
            text-align: center;
            text-transform: uppercase;
            background-color: #e0f0d0;
            box-shadow: 4px 4px 0 #1e3f2a;
            transition: all 0.05s linear;
            width: 100%;
        }

        .move-button {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 16px;
            color: #181818;
            background: none;
            border: 3px solid #306850;
            padding: 15px 8px;
            cursor: pointer;
            text-align: center;
            text-transform: uppercase;
            background-color: #e0f0d0;
            box-shadow: 4px 4px 0 #1e3f2a;
            transition: all 0.05s linear;
            width: 100%;
            white-space: nowrap;
        }

        .pokemon-button {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 14px;
            color: #181818;
            background: none;
            border: 3px solid #306850;
            padding: 12px 5px;
            cursor: pointer;
            text-align: center;
            text-transform: uppercase;
            background-color: #e0f0d0;
            box-shadow: 4px 4px 0 #1e3f2a;
            transition: all 0.05s linear;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .pokemon-button:hover {
            background-color: #306850;
            color: #f0f8d8;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #1e3f2a;
        }

        .pokemon-button:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 #1e3f2a;
        }

        .pokemon-button.selected {
            background-color: #306850;
            color: #f0f8d8;
            border-color: #f0f8d8;
            box-shadow: 4px 4px 0 #1e3f2a;
            position: relative;
        }

        .pokemon-button.selected::before {
            content: "▶";
            position: absolute;
            left: 5px;
            color: #f0f8d8;
            font-size: 14px;
        }

        .pokemon-hp-bar {
            width: 100%;
            height: 6px;
            background-color: #707070;
            border: 1px solid #383838;
            margin-top: 3px;
        }

        .pokemon-hp-fill {
            height: 100%;
            background-color: #38b848;
        }

        .pokemon-status {
            font-size: 10px;
            color: #666;
        }

        .pokemon-button:hover .pokemon-status {
            color: #f0f8d8;
        }

        .command-button:hover, .move-button:hover {
            background-color: #306850;
            color: #f0f8d8;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #1e3f2a;
        }

        .command-button:active, .move-button:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 #1e3f2a;
        }

        .command-button.selected {
            background-color: #306850;
            color: #f0f8d8;
            border-color: #f0f8d8;
            box-shadow: 4px 4px 0 #1e3f2a;
            position: relative;
        }

        .command-button.selected::before {
            content: "▶";
            position: absolute;
            left: 5px;
            color: #f0f8d8;
            font-size: 18px;
        }

        .move-button.selected {
            background-color: #306850;
            color: #f0f8d8;
            border-color: #f0f8d8;
            box-shadow: 4px 4px 0 #1e3f2a;
            position: relative;
        }

        .move-button.selected::before {
            content: "▶";
            position: absolute;
            left: 5px;
            color: #f0f8d8;
            font-size: 16px;
        }

        /* Pulsante per tornare indietro */
        .back-button {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 14px;
            color: #181818;
            background: none;
            border: 3px solid #306850;
            padding: 8px 12px;
            cursor: pointer;
            text-align: center;
            background-color: #e0f0d0;
            box-shadow: 4px 4px 0 #1e3f2a;
            margin-top: 10px;
            grid-column: span 2;
        }

        .back-button:hover {
            background-color: #306850;
            color: #f0f8d8;
        }

        /* Info battaglia */
        .battle-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 10px;
            color: #1e3f2a;
            padding: 0 5px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .game-container {
                width: 100%;
            }
            
            .enemy-pokemon, .player-pokemon {
                flex-direction: column;
                align-items: center;
            }
            
            .command-area {
                flex-direction: column;
            }
            
            .question-box {
                width: 100%;
                border-right: none;
                border-bottom: 4px solid #306850;
            }
            
            .main-menu, .moves-menu, .pokemon-menu {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    // Array con i dati dei Pokémon (solo per il menu Pokémon)
    $team = [
        ['name' => 'CHARMELEON', 'level' => 50, 'hp' => 134, 'max_hp' => 134],
        ['name' => 'BULBASAUR', 'level' => 50, 'hp' => 120, 'max_hp' => 120],
        ['name' => 'SQUIRTLE', 'level' => 50, 'hp' => 120, 'max_hp' => 120],
        ['name' => 'PIKACHU', 'level' => 50, 'hp' => 100, 'max_hp' => 100],
        ['name' => 'RATTATA', 'level' => 50, 'hp' => 90, 'max_hp' => 90],
        ['name' => 'PIDGEY', 'level' => 50, 'hp' => 100, 'max_hp' => 100]
    ];
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
                            CHARMELEON<span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv50</div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="playerHpText">134/134</span>
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
                            MEWTWO<span class="registered">®</span>
                        </div>
                        <div class="level-info">Lv70</div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="enemyHpText">182/182</span>
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
                    WHAT WILL<br>CHARMELEON DO?
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

                <!-- Menu mosse per Charmeleon -->
                <div class="moves-menu" id="movesMenu">
                    <div class="moves-column">
                        <button class="move-button selected" data-move="scratch" data-power="40" data-type="normale" data-accuracy="100" data-effect="">SCRATCH</button>
                        <button class="move-button" data-move="ember" data-power="40" data-type="fuoco" data-accuracy="100" data-effect="burn30">EMBER</button>
                    </div>
                    <div class="moves-column">
                        <button class="move-button" data-move="dragonrage" data-power="40" data-type="drago" data-accuracy="100" data-effect="">DRAGON RAGE</button>
                        <button class="move-button" data-move="flamethrower" data-power="90" data-type="fuoco" data-accuracy="100" data-effect="burn30">FLAMETHROWER</button>
                    </div>
                    <button class="back-button" id="backFromMovesBtn">← BACK</button>
                </div>

                <!-- Menu Pokémon con placeholder -->
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
            questionBox.innerHTML = 'WHAT WILL<br>CHARMELEON DO?';
            
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
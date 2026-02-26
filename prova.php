<?php
session_start();

// Verifica che l'utente sia loggato
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_giocatore = $_SESSION['user_id']; // ID dell'utente loggato
$id_avversario = isset($_GET['id_avversario']) ? intval($_GET['id_avversario']) : 0;
$modalita = isset($_GET['modalita']) ? $_GET['modalita'] : '';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon FireRed - Battaglia</title>
    
    <!-- COLLEGA IL FILE CSS ESTERNO -->
    <link rel="stylesheet" href="csscombati.css">
    
    <!-- COLLEGA I FILE JAVASCRIPT ESTERNI -->
    <script src="calcolodanno.js" defer></script>
    <script src="battaglia.js" defer></script>
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

    function getSquadraCompletaByUtente($conn, $id_utente) {
        // Recupero l'id_squadra per l'utente
        $sql_squadra = "SELECT id_squadra FROM squadra WHERE codice_utente = $id_utente";
        $result_squadra = $conn->query($sql_squadra);
        
        if ($result_squadra === false || $result_squadra->num_rows == 0) {
            return null;
        }
        
        $row_squadra = $result_squadra->fetch_assoc();
        $id_squadra = $row_squadra['id_squadra'];
        
        // Recupero tutti i Pokémon nella squadra con le loro mosse
        $sql_pokemon_squadra = "SELECT p.*, sp.slot, sp.mossa1, sp.mossa2, sp.mossa3, sp.mossa4, sp.abilita_scelta
                                FROM squadra_pokemon sp
                                JOIN pokemon p ON sp.cod = p.cod AND sp.sec_form = p.sec_form
                                WHERE sp.id_squadra = $id_squadra
                                ORDER BY sp.slot";
        
        $result_pokemon_squadra = $conn->query($sql_pokemon_squadra);
        
        if ($result_pokemon_squadra === false) {
            return null;
        }
        
        $team_pokemon = [];
        while($row = $result_pokemon_squadra->fetch_assoc()) {
            $team_pokemon[] = $row;
        }
        
        return [
            'id_squadra' => $id_squadra,
            'pokemon' => $team_pokemon
        ];
    }

    function calcolaStatistiche($pokemon, $livello) {
        // Formula Pokémon originale con floor (arrotondamento per difetto)
        $hp = (($pokemon['HP'] * 2 + 31) * $livello / 100) + $livello + 10;
        $atk = (($pokemon['ATK'] * 2 + 31) * $livello / 100) + 5;
        $def = (($pokemon['DEF'] * 2 + 31) * $livello / 100) + 5;
        $spa = (($pokemon['SP_ATK'] * 2 + 31) * $livello / 100) + 5;
        $spd = (($pokemon['SP_DEF'] * 2 + 31) * $livello / 100) + 5;
        $spe = (($pokemon['SPE'] * 2 + 31) * $livello / 100) + 5;
        
        return [
            'cod' => $pokemon['cod'],
            'name' => strtoupper($pokemon['nome']),
            'level' => $livello,
            'hp' => (int) floor($hp),
            'max_hp' => (int) floor($hp),
            'atk' => (int) floor($atk),
            'def' => (int) floor($def),
            'spa' => (int) floor($spa),
            'spd' => (int) floor($spd),
            'spe' => (int) floor($spe),
            'slot' => $pokemon['slot'],
            'sec_form' => $pokemon['sec_form'],
            'mossa1' => $pokemon['mossa1'] ?? null,
            'mossa2' => $pokemon['mossa2'] ?? null,
            'mossa3' => $pokemon['mossa3'] ?? null,
            'mossa4' => $pokemon['mossa4'] ?? null,
            // AGGIUNTO: includi i tipi del Pokémon
            'tipo1' => $pokemon['tipo1'],
            'tipo2' => $pokemon['tipo2']
        ];
    }

    function getMossePokemonSquadra($conn, $id_squadra, $cod, $sec_form, $slot) {
        // Prima ottieni gli ID delle mosse dalla squadra_pokemon
        $sql_squadra = "SELECT mossa1, mossa2, mossa3, mossa4 
                        FROM squadra_pokemon 
                        WHERE id_squadra = " . $id_squadra . "
                        AND cod = " . $cod . "
                        AND sec_form = '" . $sec_form . "'
                        AND slot = " . $slot;
        
        $result = $conn->query($sql_squadra);
        
        if (!$result || $result->num_rows == 0) {
            return [];
        }
        
        $row = $result->fetch_assoc();
        $mosse_ids = [];
        
        // Raccogli tutti gli ID delle mosse non null
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($row['mossa' . $i])) {
                $mosse_ids[] = $row['mossa' . $i];
            }
        }
        
        if (empty($mosse_ids)) {
            return [];
        }
        
        // Ora recupera i dettagli delle mosse
        $mosse_ids_str = implode(',', $mosse_ids);
        $sql_mosse = "SELECT * FROM mossa WHERE id_mossa IN (" . $mosse_ids_str . ")";
        
        $result_mosse = $conn->query($sql_mosse);
        
        $mosse = [];
        if ($result_mosse && $result_mosse->num_rows > 0) {
            while($row = $result_mosse->fetch_assoc()) {
                $mosse[] = $row;
            }
        }
        
        return $mosse;
    }

    function getNomeUtente($conn, $id_utente) {
        $sql = "SELECT nome FROM utente WHERE codice = " . $id_utente;
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['nome'];
        }
        return "Allenatore " . $id_utente;
    }

    
    $squadra_giocatore = getSquadraCompletaByUtente($conn, $id_giocatore);
    
    if (!$squadra_giocatore) {
        die("Nessuna squadra trovata per l'utente $id_giocatore");
    }
    
    $id_squadra_giocatore = $squadra_giocatore['id_squadra'];
    $team_pokemon_giocatore = $squadra_giocatore['pokemon'];
    
    
    $livello_giocatore = 50;
    $livello_avversario = 50;
    $nome_avversario = "Allenatore sconosciuto";
    $id_squadra_avversario = 0;
    
    if ($modalita == 'random') {
        // Modalità random: crea una squadra casuale di 3 Pokémon
        $sql_random = "SELECT * FROM pokemon ORDER BY RAND() LIMIT 3";
        $result_random = $conn->query($sql_random);
        
        $team_avversario = [];
        if ($result_random && $result_random->num_rows > 0) {
            $slot = 1;
            while($row = $result_random->fetch_assoc()) {
                $row['slot'] = $slot;
                // Assegna mosse casuali per il Pokémon
                $sql_mosse_random = "SELECT id_mossa FROM mossa_x_pokemon 
                                     WHERE cod = " . $row['cod'] . " 
                                     AND sec_form = '" . $row['sec_form'] . "'
                                     ORDER BY RAND() LIMIT 4";
                $result_mosse_random = $conn->query($sql_mosse_random);
                $mosse_counter = 1;
                if ($result_mosse_random && $result_mosse_random->num_rows > 0) {
                    while($mossa_row = $result_mosse_random->fetch_assoc()) {
                        $row['mossa' . $mosse_counter] = $mossa_row['id_mossa'];
                        $mosse_counter++;
                    }
                }
                // Riempi le mosse rimanenti con NULL
                for ($i = $mosse_counter; $i <= 4; $i++) {
                    $row['mossa' . $i] = null;
                }
                $team_avversario[] = $row;
                $slot++;
            }
        }
        $nome_avversario = "Pokémon Selvatico";
    } else if ($id_avversario > 0) {
        // Modalità allenatore specifico
        $squadra_avversario = getSquadraCompletaByUtente($conn, $id_avversario);
        if (!$squadra_avversario) {
            die("Nessuna squadra trovata per l'avversario $id_avversario");
        }
        $id_squadra_avversario = $squadra_avversario['id_squadra'];
        $team_avversario = $squadra_avversario['pokemon'];
        $nome_avversario = getNomeUtente($conn, $id_avversario);
    } else {
        die("Modalità di battaglia non valida");
    }

    
    $team_data = [];
    foreach($team_pokemon_giocatore as $pokemon) {
        $team_data[] = calcolaStatistiche($pokemon, $livello_giocatore);
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
    
    $team_avversario_data = [];
    foreach($team_avversario as $pokemon) {
        $stats = calcolaStatistiche($pokemon, $livello_avversario);
        $team_avversario_data[] = $stats;
    }
    
    // Il primo Pokémon dell'avversario è quello attuale
    $pokemon_nemico_attuale = $team_avversario_data[0];

    // Recupero le mosse del Pokémon attuale del giocatore
    $mosse_attuali = getMossePokemonSquadra(
        $conn, 
        $id_squadra_giocatore, 
        $pokemon_attuale['cod'], 
        $pokemon_attuale['sec_form'], 
        $pokemon_attuale['slot']
    );
    ?>
    
    <div class="game-container">
        <div class="battle-screen">
            <!-- HEADER CON NOMI ALLENATORI -->
            <div class="battle-header">
                <div class="trainer-name player-trainer"><?php echo getNomeUtente($conn, $id_giocatore); ?></div>
                <div class="vs">VS</div>
                <div class="trainer-name enemy-trainer"><?php echo $nome_avversario; ?></div>
            </div>
            
            <!-- AREA POKEMON IN BATTAGLIA -->
            <div class="pokemon-battle-area">
                <!-- POKEMON GIOCATORE (attuale) - in basso a sinistra -->
                <div class="player-pokemon" id="playerPokemonContainer">
                    <div class="sprite-container">
                        <img src="Img/<?php echo strtolower($pokemon_attuale['name']); ?>.png" 
                             alt="<?php echo $pokemon_attuale['name']; ?>"
                             class="pokemon-sprite"
                             id="playerSprite"
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
                        <div class="pokemon-name" id="enemyName">
                            <?php echo strtoupper($pokemon_nemico_attuale['name']); ?><span class="registered">®</span>
                        </div>
                        <div class="level-info" id="enemyLevel">Lv<?php echo $pokemon_nemico_attuale['level']; ?></div>
                        <div class="hp-container">
                            <div class="hp-header">
                                <span class="hp-label">HP</span>
                                <span class="hp-numbers" id="enemyHpText">
                                    <?php echo $pokemon_nemico_attuale['hp'] . '/' . $pokemon_nemico_attuale['max_hp']; ?>
                                </span>
                            </div>
                            <div class="hp-bar-bg">
                                <div class="hp-bar-fill" id="enemyHpBar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="sprite-container">
                        <img src="Img/<?php echo strtolower($pokemon_nemico_attuale['name']); ?>.png" 
                             alt="<?php echo strtoupper($pokemon_nemico_attuale['name']); ?>"
                             class="pokemon-sprite"
                             id="enemySprite"
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
                                data-move-id="<?php echo $move['id_mossa']; ?>"
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>"
                                data-category="<?php echo $move['categoria']; ?>">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                        <?php for($i = count($mosse_attuali); $i < 2; $i++): ?>
                        <button class="move-button disabled" disabled>-</button>
                        <?php endfor; ?>
                    </div>
                    <div class="moves-column" id="movesColumn2">
                        <?php for($i = 2; $i < min(4, count($mosse_attuali)); $i++): 
                            $move = $mosse_attuali[$i];
                        ?>
                        <button class="move-button" 
                                data-move-id="<?php echo $move['id_mossa']; ?>"
                                data-move="<?php echo strtolower($move['nome']); ?>" 
                                data-power="<?php echo $move['danno']; ?>" 
                                data-type="<?php echo strtolower($move['tipo']); ?>" 
                                data-accuracy="<?php echo $move['accuratezza']; ?>"
                                data-category="<?php echo $move['categoria']; ?>">
                            <?php echo strtoupper($move['nome']); ?>
                        </button>
                        <?php endfor; ?>
                        <?php for($i = max(2, count($mosse_attuali)); $i < 4; $i++): ?>
                        <button class="move-button disabled" disabled>-</button>
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
                            data-secform="<?php echo $pokemon['sec_form']; ?>"
                            data-tipo1="<?php echo $pokemon['tipo1']; ?>"
                            data-tipo2="<?php echo $pokemon['tipo2']; ?>">
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

    <style>
        .battle-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #306850;
            color: white;
            padding: 8px 16px;
            border-radius: 8px 8px 0 0;
            font-family: 'Pokemon', 'Arial', sans-serif;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .vs {
            background-color: #f8d030;
            color: #306850;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
        }
        .trainer-name {
            font-weight: bold;
            text-shadow: 1px 1px 0 #000;
        }
        .player-trainer {
            color: #f8d030;
        }
        .enemy-trainer {
            color: #ff6b6b;
        }
    </style>

    <script>
        // Passaggio dei dati dal PHP al JavaScript
        const battleData = {
            teamData: <?php echo json_encode($team_data); ?>,
            enemyTeamData: <?php echo json_encode($team_avversario_data); ?>,
            idSquadraGiocatore: <?php echo $id_squadra_giocatore; ?>,
            idSquadraAvversario: <?php echo $id_squadra_avversario; ?>,
            nomeGiocatore: "<?php echo getNomeUtente($conn, $id_giocatore); ?>",
            nomeAvversario: "<?php echo $nome_avversario; ?>",
            currentPokemonSlot: <?php echo $pokemon_attuale['slot']; ?>
        };
        
        // Inizializza il gioco quando la pagina è caricata
        window.addEventListener('DOMContentLoaded', function() {
            initBattle(battleData);
        });
    </script>
</body>
</html>
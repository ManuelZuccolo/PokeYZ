<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$conn = new mysqli("localhost", "root", "", "pokeyz_db");
if ($conn->connect_error) die("Connessione fallita: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];

// Verifichiamo se l'utente ha almeno un Pokémon in squadra prima di lottare
$stmt_check = $conn->prepare("SELECT COUNT(*) as totale FROM Squadra_Pokemon sp JOIN Squadra s ON sp.id_squadra = s.id_squadra WHERE s.codice_utente = ?");
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$count = $stmt_check->get_result()->fetch_assoc()['totale'];

// LOGICA LOTTA RANDOM
if (isset($_GET['mode']) && $_GET['mode'] === 'random') {
    // FIX: password è una stringa, quindi va tra apici 'allenatore'
    $res = $conn->query("SELECT codice_utente FROM utente WHERE password = 'allenatore' ORDER BY RAND() LIMIT 1");
    if ($row = $res->fetch_assoc()) {
        $id_avversario = $row['codice_utente'];
        header("Location: prova.php?id_avversario=$id_avversario");
        exit();
    }
}

// Visualizzazione lista allenatori (se richiesto)
$mostra_lista = isset($_GET['mode']) && $_GET['mode'] === 'scegli';
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Scegli la sfida - PokéYZ</title>
<link rel="stylesheet" href="style.css?v=1">
<style>
    .auth-card { width: 90%; max-width: 800px; text-align: center; }
    .battle-options { display: flex; justify-content: center; gap: 30px; margin: 40px 0; }
    .battle-card { 
        flex: 1; padding: 20px; border: 3px solid #3b4cca; border-radius: 15px; 
        background: #f9f9f9; transition: 0.3s; cursor: pointer; text-decoration: none; color: black;
    }
    .battle-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); background: #ffde00; }
    .battle-card h2 { color: #ff0000; margin-bottom: 5px; }
    .trainer-list { width: 100%; margin-top: 20px; border-collapse: collapse; }
    .trainer-list td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    .choice-btn { padding: 5px 15px; background: #ffde00; border: 2px solid #3b4cca; border-radius: 5px; cursor: pointer; font-weight: bold; }
</style>
</head>
<body>
<header><div class="logo"><h1>PokéYZ</h1></div></header>
<div class="auth-wrapper">
<div class="auth-card">
    
    <?php if($count == 0): ?>
        <h2>Ouch! La tua squadra è vuota.</h2>
        <p>Devi aggiungere almeno un Pokémon per poter lottare.</p>
        <a href="squadra.php" class="main-btn" style="margin-top:20px; display:inline-block;">Vai alla Squadra</a>

    <?php elseif(!$mostra_lista): ?>
        <h2>Preparati alla Lotta!</h2>
        <p>Scegli come vuoi affrontare il tuo prossimo avversario:</p>

        <div class="battle-options">
            <a href="combatti.php?mode=random" class="battle-card">
                <h2>Lotta Random</h2><br>
                <p>Affronta un allenatore casuale immediatamente!</p>
            </a>

            <a href="combatti.php?mode=scegli" class="battle-card">
                <h2>Scegli Allenatore</h2> <br>
                <p>Sfoglia la lista e decidi chi sfidare.</p>
            </a>
        </div>
        <a href="squadra.php" class="cancel-btn">Indietro</a>

    <?php else: ?>
        <h2>Seleziona il tuo avversario</h2>
        <table class="trainer-list">
            <?php
            // FIX: Selezioniamo dalla tabella 'utente' filtrando per password 'allenatore'
            $res = $conn->query("SELECT codice_utente, nome FROM utente WHERE password = 'allenatore'");
            while($trainer = $res->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($trainer['nome']) ?></strong></td>
                    <td style="text-align: right;">
                        <a href="prova.php?id_avversario=<?= $trainer['codice_utente'] ?>" class="choice-btn" style="text-decoration:none;">Sfida</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <a href="combatti.php" class="cancel-btn">Torna alle opzioni</a>
    <?php endif; ?>

</div>
</div>
</body>
</html>
<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$conn = new mysqli("localhost","root","","pokeyz_db");
if($conn->connect_error) die("Errore connessione");

$user_id = $_SESSION['user_id'];
$mode = $_GET['mode'] ?? null;
$mostra_lista = ($mode === "scegli");

// Conto Pokémon nella squadra
$stmt = $conn->prepare("
    SELECT COUNT(*) as totale
    FROM squadra_pokemon sp
    JOIN squadra s ON sp.id_squadra = s.id_squadra
    WHERE s.codice_utente = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['totale'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<style>

.battle-options {
    display: flex;
    gap: 40px;
    justify-content: center;
    margin-top: 40px;
}

.battle-card {
    flex: 1;
    max-width: 350px;
    padding: 50px 30px;
    text-align: center;
    text-decoration: none;
    border-radius: 20px;
    font-weight: bold;
    transition: all 0.15s ease;
    cursor: pointer;
    box-shadow: 0 8px 0 rgba(0,0,0,0.25);
    background: linear-gradient(145deg, #ffde00, #f5c400);
    border: 3px solid #3b4cca;
    color: #1a1a1a;
}

.battle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 13px 0 rgba(0,0,0,0.25);
}

.battle-card:active {
    transform: translateY(4px);
    box-shadow: 0 3px 0 rgba(0,0,0,0.25);
}

.cancel-btn {
    display: inline-block;
    margin-top: 30px;
    text-decoration: none;
    font-weight: bold;
}

.trainer-list {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
}

.trainer-list td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.trainer-list a {
    text-decoration: none;
    font-weight: bold;
    color: #3b4cca;
}

</style>
</head>
<body>

<div class="auth-wrapper">
<div class="auth-card">

<?php if($count == 0): ?>

    <h2>Ouch! La tua squadra è vuota.</h2>
    <p>Devi aggiungere almeno un Pokémon per poter lottare.</p>
    <a href="squadra.php" class="main-btn" style="margin-top:20px; display:inline-block;">
        Vai alla Squadra
    </a>

<?php elseif(!$mostra_lista): ?>

    <h2>Preparati alla Lotta!</h2>
    <p>Scegli come vuoi affrontare il tuo prossimo avversario:</p>

    <div class="battle-options">

        <a href="prova.php?modalita=random" class="battle-card">
            <h2>Lotta Random</h2><br>
            <p>Affronta un allenatore casuale immediatamente!</p>
        </a>

        <a href="combatti.php?mode=scegli" class="battle-card">
            <h2>Scegli Allenatore</h2><br>
            <p>Sfoglia la lista e decidi chi sfidare.</p>
        </a>

    </div>

    <a href="squadra.php" class="cancel-btn">Indietro</a>

<?php else: ?>

    <h2>Seleziona il tuo avversario</h2>

    <table class="trainer-list">
    <?php
    $query = "SELECT codice, nome 
              FROM utente 
              WHERE password = 'allenatore'";

    $res = $conn->query($query);

    if(!$res){
        die("Errore query: ".$conn->error);
    }

    while($row = $res->fetch_assoc()):
    ?>
        <tr>
            <td>
                <a href="prova.php?id_avversario=<?= $row['codice'] ?>">
                    <?= $row['nome'] ?>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>

    <a href="combatti.php" class="cancel-btn">← Indietro</a>

<?php endif; ?>

</div>
</div>

</body>
</html>
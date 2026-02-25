<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$conn = new mysqli("localhost","root","","pokeyz_db");
if($conn->connect_error) die("Errore connessione");

$scegli = isset($_GET['scegli']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<style>
.button-row {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}
</style>
</head>
<body>

<div class="auth-wrapper">
<div class="auth-card">

<h2>Combatti</h2>

<?php if(!$scegli): ?>

<div class="button-row">
    <!-- RANDOM -->
    <form action="prova.php" method="GET">
        <input type="hidden" name="modalita" value="random">
        <button type="submit" class="main-btn">Lotta Random</button>
    </form>

    <!-- SCEGLI ALLENATORE -->
    <form method="GET">
        <input type="hidden" name="scegli" value="1">
        <button type="submit" class="main-btn">Scegli Allenatore</button>
    </form>
</div>

<?php else: ?>

<h3>Seleziona un allenatore:</h3>

<?php
// Prende tutti gli utenti che sono allenatori (tranne quello loggato)
$query = "SELECT codice, nome
          FROM utente 
          WHERE password = 'allenatore' AND codice != " . $_SESSION['user_id'];

$res = $conn->query($query);

if(!$res){
    die("Errore query: ".$conn->error);
}

if($res->num_rows == 0) {
    echo "<p>Nessun allenatore disponibile</p>";
}

while($row = $res->fetch_assoc()):
?>

<div style="margin-bottom:10px;">
    <a href="prova.php?id_avversario=<?= $row['codice'] ?>" 
       class="choice-btn">
        <?= $row['nome'] ?>
    </a>
</div>

<?php endwhile; ?>

<br>
<a href="combatti.php" class="main-btn">← Indietro</a>

<?php endif; ?>

</div>
</div>

</body>
</html>
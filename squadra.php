<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "pokeyz_db");
if ($conn->connect_error) die("Connessione fallita: " . $conn->connect_error);

// Recupero id_squadra
$stmt = $conn->prepare("SELECT id_squadra FROM Squadra WHERE codice_utente = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_squadra = $row['id_squadra'];

// GESTIONE RIMOZIONE
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['azione']) && $_POST['azione']==='rimuovi'){
    $cod = (int)($_POST['cod'] ?? 0);
    $sec_form = $_POST['sec_form'] ?? '';
    
    if($cod > 0 && $sec_form !== ''){
        // Eliminiamo quel pokemon specifico da quella specifica squadra
        $stmt_del = $conn->prepare("DELETE FROM Squadra_Pokemon WHERE id_squadra=? AND cod=? AND sec_form=?");
        $stmt_del->bind_param("iis", $id_squadra, $cod, $sec_form);
        
        if($stmt_del->execute()){
            header("Location: squadra.php?success=rimosso"); // Redirect pulito
            exit();
        }
    }
}

// Recupero Pokémon della squadra
$query = "
SELECT sp.slot, p.cod, p.nome, p.sec_form, p.tipo1, p.tipo2, sp.mossa1, sp.mossa2, sp.mossa3, sp.mossa4, sp.abilita_scelta
FROM Squadra_Pokemon sp
JOIN Pokemon p ON sp.cod = p.cod AND sp.sec_form = p.sec_form
WHERE sp.id_squadra = ?
";
$stmt2 = $conn->prepare($query);
$stmt2->bind_param("i", $id_squadra);
$stmt2->execute();
$result2 = $stmt2->get_result();

$squadra = [];
while($r = $result2->fetch_assoc()){
    $squadra[$r['slot']] = $r;
}

// Funzione sicura per recuperare nome mossa/abilità
function getNome($conn, $id, $table){
    if(!$id) return "-";
    $stmt = $conn->prepare("SELECT nome FROM $table WHERE id_{$table}=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res=$stmt->get_result();
    return $res->num_rows ? $res->fetch_assoc()['nome'] : "-";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>La mia squadra - PokéYZ</title>
<link rel="stylesheet" href="style.css?v=1">
<style>
.choice-btn {padding:5px 10px; margin:2px; cursor:pointer; background:#ffde00; border:2px solid #3b4cca; border-radius:5px; transition:.2s;}
.choice-btn:hover {background:#3b4cca; color:white;}
.type {padding:2px 6px; border-radius:3px; color:white;}
</style>
</head>
<body>
<header><div class="logo"><h1>PokéYZ</h1></div></header>
<div class="auth-wrapper">
<div class="auth-card">
<h2>La mia squadra</h2>

<table class="pokedex-table">
<tr>
    <th>Slot</th>
    <th>Nome</th>
    <th>Tipo</th>
    <th>Mosse</th>
    <th>Abilità</th>
    <th>Azione</th>
</tr>

<?php
for($slot=1; $slot<=6; $slot++):
    if(isset($squadra[$slot])):
        $p = $squadra[$slot];
        ?>
        <tr>
            <td><?= $slot ?></td>
            <td><?= ucfirst($p['nome']) ?></td>
            <td>
                <span class="type <?= strtolower($p['tipo1']) ?>"><?= $p['tipo1'] ?></span>
                <?= $p['tipo2'] ? '<span class="type '.strtolower($p['tipo2']).'">'.$p['tipo2'].'</span>' : '' ?>
            </td>
            <td>
                <div style="text-align: left; display: inline-block;">
                    <?= getNome($conn, $p['mossa1'], 'Mossa') ?><br>
                    <?php if($p['mossa2']): ?> <?= getNome($conn, $p['mossa2'], 'Mossa') ?><br><?php endif; ?>
                    <?php if($p['mossa3']): ?> <?= getNome($conn, $p['mossa3'], 'Mossa') ?><br><?php endif; ?>
                    <?php if($p['mossa4']): ?> <?= getNome($conn, $p['mossa4'], 'Mossa') ?><?php endif; ?>
                </div>
            </td>
            <td><?= getNome($conn,$p['abilita_scelta'],'Abilita') ?></td>
           <td>
                <form method="GET" action="aggiungi_pokemon.php" style="display:inline;">
                    <input type="hidden" name="modifica" value="1">
                    <input type="hidden" name="pokemon_cod" value="<?= $p['cod'] ?>">
                    <input type="hidden" name="sec_form" value="<?= $p['sec_form'] ?>">
                    <input type="hidden" name="slot" value="<?= $slot ?>"> <button type="submit" class="choice-btn">Modifica</button>
                </form>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="azione" value="rimuovi">
                    <input type="hidden" name="cod" value="<?= $p['cod'] ?>">
                    <input type="hidden" name="sec_form" value="<?= $p['sec_form'] ?>">
                    <button type="submit" class="choice-btn" onclick="return confirm('Vuoi davvero rimuovere <?= ucfirst($p['nome']) ?>?')">Rimuovi</button>
                </form>
            </td>
        </tr>
    <?php else: ?>
        <tr>
            <td><?= $slot ?></td>
            <td colspan="4">Vuoto</td>
            <td>
                <form method="GET" action="aggiungi_pokemon.php">
                    <input type="hidden" name="slot" value="<?= $slot ?>">
                    <button type="submit" class="main-btn">Aggiungi Pokémon</button>
                </form>
            </td>
        </tr>
    <?php endif;
endfor;
?>
</table>

<a href="index.php" class="main-btn" style="margin-top:20px;">Torna all'homepage</a>
</div></div>
</body>
</html>
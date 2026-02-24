<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$host = "localhost";
$user = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($host, $user, $password, $dbname);
if($conn->connect_error) die("Connessione fallita: ".$conn->connect_error);

$stmt = $conn->prepare("SELECT id_squadra FROM Squadra WHERE codice_utente=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$id_squadra = $stmt->get_result()->fetch_assoc()['id_squadra'];

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Squadra_Pokemon WHERE id_squadra=?");
$stmt->bind_param("i", $id_squadra);
$stmt->execute();
$slot_libero = $stmt->get_result()->fetch_assoc()['count'] + 1;

$step = $_POST['step'] ?? 'scegli_pokemon';
$errors = [];

// Helper
function getNome($conn,$table,$id){
    if(!$id) return "-";
    $stmt=$conn->prepare("SELECT nome FROM $table WHERE id_$table=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res=$stmt->get_result();
    return $res->num_rows?$res->fetch_assoc()['nome']:"-";
}

// STEP logica (come prima)
if($step==='scegli_pokemon' && isset($_POST['pokemon_cod'])){
    $pokemon_cod = (int)$_POST['pokemon_cod'];
    $pokemon_sec_form = $_POST['pokemon_sec_form'];

    // Controllo se è già in squadra (stessa forma!)
    $stmt = $conn->prepare("
        SELECT * 
        FROM Squadra_Pokemon 
        WHERE id_squadra=? AND cod=? AND sec_form=?
    ");
    $stmt->bind_param("iis", $id_squadra, $pokemon_cod, $pokemon_sec_form);
    $stmt->execute();

    if($stmt->get_result()->num_rows>0){
        $errors[]="Hai già scelto questo Pokémon!";
    } else {
        $_SESSION['scegliendo_pokemon']=$pokemon_cod;
        $_SESSION['scegliendo_sec_form']=$pokemon_sec_form;
        $step='scegli_abilita';
    }
}

if($step==='scegli_abilita' && isset($_POST['abilita_id'])){
    $abilita_id=(int)$_POST['abilita_id'];
    if(!$abilita_id) $errors[]="Devi scegliere un'abilità";
    else { $_SESSION['abilita_scelta']=$abilita_id; $step='scegli_mosse'; }
}

if($step==='scegli_mosse' && isset($_POST['mosse'])){
    $mosse=array_map('intval',$_POST['mosse']);
    if(count($mosse)<1) $errors[]="Devi scegliere almeno 1 mossa";
    else {
        $pokemon_cod=$_SESSION['scegliendo_pokemon'];
        $abilita_id=$_SESSION['abilita_scelta'];
        $sec_form = $_SESSION['scegliendo_sec_form'];
        $m1=$mosse[0]??null;
        $m2=$mosse[1]??null;
        $m3=$mosse[2]??null;
        $m4=$mosse[3]??null;
        $stmt=$conn->prepare("INSERT INTO Squadra_Pokemon
            (id_squadra, slot, cod, sec_form, mossa1, mossa2, mossa3, mossa4, abilita_scelta)
            VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("iiisiiiii",$id_squadra,$slot_libero,$pokemon_cod,$sec_form,$m1,$m2,$m3,$m4,$abilita_id);
        $stmt->execute();
        unset(
                $_SESSION['scegliendo_pokemon'],
                $_SESSION['scegliendo_sec_form'],
                $_SESSION['abilita_scelta']
            );
        $slot_libero++;
        $step='scegli_pokemon';
    }
}

// DATI PER FORM
$ids_scelti=[];
$stmt=$conn->prepare("SELECT cod, sec_form FROM Squadra_Pokemon WHERE id_squadra=?");
$stmt->bind_param("i",$id_squadra);
$stmt->execute();
$res=$stmt->get_result(); while($r=$res->fetch_assoc()) $ids_scelti[]=$r['cod'];
$ids_scelti_str=$ids_scelti?implode(",",$ids_scelti):"0";
$poke_res=$conn->query("SELECT cod, nome, sec_form, tipo1, tipo2 FROM Pokemon WHERE cod NOT IN ($ids_scelti_str) ORDER BY cod ASC");

$abilita_disponibili=[];
if(isset($_SESSION['scegliendo_pokemon'])){
    $pid=$_SESSION['scegliendo_pokemon'];
    $stmt=$conn->prepare("SELECT pa.id_abilita,a.nome FROM Abilita_Pokemon pa JOIN Abilita a ON pa.id_abilita=a.id_Abilita WHERE pa.cod=?");
    $stmt->bind_param("i",$pid); $stmt->execute();
    $res=$stmt->get_result(); while($r=$res->fetch_assoc()) $abilita_disponibili[]=$r;
}

$mosse_disponibili=[];
if(isset($_SESSION['scegliendo_pokemon'])){
    $pid=$_SESSION['scegliendo_pokemon'];
    $stmt=$conn->prepare("SELECT pm.id_mossa,m.nome FROM Mossa_x_pokemon pm JOIN Mossa m ON pm.id_mossa=m.id_Mossa WHERE pm.cod=?");
    $stmt->bind_param("i",$pid); $stmt->execute();
    $res=$stmt->get_result(); while($r=$res->fetch_assoc()) $mosse_disponibili[]=$r;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Scegli Pokémon - PokéYZ</title>
<link rel="stylesheet" href="style.css?v=1">
<style>
.auth-card {width: 90%; max-width: 900px;}
.top-nav {display:flex; justify-content:space-between; margin-bottom:20px;}
.choice-btn {padding:5px 10px; margin:2px; cursor:pointer; background:#ffde00; border:2px solid #3b4cca; border-radius:5px; transition:.2s;}
.choice-btn:hover {background:#3b4cca; color:white;}
.pokedex-table th, .pokedex-table td{text-align:center;}
</style>
</head>
<body>
<header><div class="logo"><h1>PokéYZ</h1></div></header>
<div class="auth-wrapper">
<div class="auth-card">
<div class="top-nav">
<span>Scegli la tua squadra (<?= $slot_libero-1 ?>/6)</span>
<a href="index.php" class="main-btn">Torna alla homepage</a>
</div>

<?php if($errors){foreach($errors as $e) echo "<p class='error-msg'>$e</p>";} ?>

<?php if($step==='scegli_pokemon'): ?>
<table class="pokedex-table">
<tr><th>#</th><th>Immagine</th><th>Nome</th><th>Tipo</th><th>Seleziona</th></tr>
<?php while($p=$poke_res->fetch_assoc()): 
$imgName=strtolower($p['nome']); if($p['sec_form']!='BASE') $imgName.="_".strtolower($p['sec_form']); ?>
<tr>
<td><?= str_pad($p['cod'],4,"0",STR_PAD_LEFT) ?></td>
<td><img src='Img/<?= $imgName ?>.png' width='50'></td>
<td><?= ucfirst($p['nome']) ?> <?= $p['sec_form']!='BASE'?"({$p['sec_form']})":"" ?></td>
<td>
<span class="type <?= $p['tipo1'] ?>"><?= ucfirst($p['tipo1']) ?></span>
<?php if($p['tipo2']): ?><span class="type <?= $p['tipo2'] ?>"><?= ucfirst($p['tipo2']) ?></span><?php endif; ?>
</td>
<td>
<form method="POST" style="display:inline;">
<input type="hidden" name="step" value="scegli_pokemon">
<input type="hidden" name="pokemon_cod" value="<?= $p['cod'] ?>">
<input type="hidden" name="pokemon_sec_form" value="<?= $p['sec_form'] ?>">
<button type="submit" class="choice-btn">Seleziona</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>

<?php elseif($step==='scegli_abilita'): ?>
<table class="pokedex-table">
<tr><th>ID</th><th>Nome Abilità</th><th>Seleziona</th></tr>
<?php foreach($abilita_disponibili as $a): ?>
<tr>
<td><?= $a['id_abilita'] ?></td>
<td><?= $a['nome'] ?></td>
<td>
<form method="POST" style="display:inline;">
<input type="hidden" name="step" value="scegli_abilita">
<input type="hidden" name="abilita_id" value="<?= $a['id_abilita'] ?>">
<button type="submit" class="choice-btn">Seleziona</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php elseif($step==='scegli_mosse'): ?>
<table class="pokedex-table">
<tr><th>ID</th><th>Nome Mossa</th><th>Seleziona</th></tr>
<?php foreach($mosse_disponibili as $m): ?>
<tr>
<td><?= $m['id_mossa'] ?></td>
<td><?= $m['nome'] ?></td>
<td>
<form method="POST" style="display:inline;">
<input type="hidden" name="step" value="scegli_mosse">
<input type="hidden" name="mosse[]" value="<?= $m['id_mossa'] ?>">
<button type="submit" class="choice-btn">Aggiungi</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<hr>
<a href="squadra.php" class="main-btn">Visualizza la squadra</a>
</div></div>
</body>
</html>
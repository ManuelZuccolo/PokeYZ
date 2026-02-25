<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$conn = new mysqli("localhost","root","","pokeyz_db");
$user_id = $_SESSION['user_id'];

// 1. Recupero id_squadra
$stmt = $conn->prepare("SELECT id_squadra FROM Squadra WHERE codice_utente=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$id_squadra = $stmt->get_result()->fetch_assoc()['id_squadra'];

// 2. Recupero i codici dei Pokémon già in squadra per escluderli
$res_esclusi = $conn->query("SELECT cod FROM Squadra_Pokemon WHERE id_squadra=$id_squadra");
$esclusi = [];
while($row = $res_esclusi->fetch_assoc()) {
    $esclusi[] = $row['cod'];
}

// Parametri
$step = $_POST['step'] ?? (isset($_GET['modifica']) ? 'scegli_abilita' : 'scegli_pokemon');
$slot_corrente = $_REQUEST['slot'] ?? null;
$pokemon_cod = $_REQUEST['pokemon_cod'] ?? null;
$sec_form = $_REQUEST['sec_form'] ?? 'BASE';
$search = trim($_GET['search'] ?? '');

// Se stiamo modificando, permettiamo di vedere il Pokémon che è già in quello slot
if(isset($_GET['modifica']) && ($key = array_search($pokemon_cod, $esclusi)) !== false) {
    unset($esclusi[$key]);
}
$lista_esclusi = count($esclusi) > 0 ? implode(',', $esclusi) : '0';

// ... (Logica di salvataggio identica alla precedente) ...
if($step === 'salva_tutto'){
    $abilita_id = $_POST['abilita_id'] ?? null;
    $mosse = $_POST['mosse'] ?? [];
    $m = array_pad($mosse, 4, null);
    $conn->query("DELETE FROM Squadra_Pokemon WHERE id_squadra=$id_squadra AND slot=$slot_corrente");
    $ins = $conn->prepare("INSERT INTO Squadra_Pokemon (id_squadra, slot, cod, sec_form, mossa1, mossa2, mossa3, mossa4, abilita_scelta) VALUES (?,?,?,?,?,?,?,?,?)");
    $ins->bind_param("iiisiiiii", $id_squadra, $slot_corrente, $pokemon_cod, $sec_form, $m[0], $m[1], $m[2], $m[3], $abilita_id);
    if($ins->execute()){ header("Location: squadra.php"); exit(); }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css?v=1">
<style>
    .auth-card { width: 95%; max-width: 1100px; } /* Allargato anche qui */
    .header-squadra { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .search-mini { width: 180px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; } 
    .choice-btn { padding:5px 12px; cursor:pointer; background:#ffde00; border:2px solid #3b4cca; border-radius:5px; font-weight: bold; }
    .choice-btn:hover { background:#3b4cca; color:white; }
</style>
</head>
<body>
<div class="auth-wrapper">
<div class="auth-card">

    <div class="header-squadra">
        <div>
            <h2>Configurazione Slot #<?= $slot_corrente ?></h2>
        </div>
        <a href="squadra.php" class="main-btn">← Squadra</a>
    </div>

    <?php if($step === 'scegli_pokemon'): ?>
        <form method="GET" style="margin-bottom:20px;">
            <input type="hidden" name="slot" value="<?= $slot_corrente ?>">
            <input type="text" name="search" class="search-mini" placeholder="Cerca..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="choice-btn">Cerca</button>
        </form>

        <table class="pokedex-table">
            <tr><th>#</th><th>Immagine</th><th>Nome</th><th>Azione</th></tr>
            <?php
            // QUERY CON NOT IN PER EVITARE DOPPIONI
            $sql = "SELECT * FROM Pokemon 
                    WHERE cod NOT IN ($lista_esclusi) 
                    AND nome LIKE '%$search%' 
                    ORDER BY cod ASC";
            $res = $conn->query($sql);
            while($p = $res->fetch_assoc()):
                $img = strtolower($p['nome']) . ($p['sec_form'] != 'BASE' ? "_".strtolower($p['sec_form']) : "");
            ?>
            <tr>
                <td><?= str_pad($p['cod'],4,"0",STR_PAD_LEFT) ?></td>
                <td><img src="Img/<?= $img ?>.png" width="40"></td>
                <td><?= ucfirst($p['nome']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="step" value="scegli_abilita">
                        <input type="hidden" name="slot" value="<?= $slot_corrente ?>">
                        <input type="hidden" name="pokemon_cod" value="<?= $p['cod'] ?>">
                        <input type="hidden" name="sec_form" value="<?= $p['sec_form'] ?>">
                        <button type="submit" class="choice-btn">Seleziona</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

    <?php elseif($step === 'scegli_abilita'): ?>
        <h3>Seleziona l'Abilità:</h3>
        <table class="pokedex-table">
            <?php
            $stmt = $conn->prepare("SELECT a.id_Abilita, a.nome FROM Abilita_Pokemon ap JOIN Abilita a ON ap.id_abilita = a.id_Abilita WHERE ap.cod = ?");
            $stmt->bind_param("i", $pokemon_cod);
            $stmt->execute();
            $res = $stmt->get_result();
            while($a = $res->fetch_assoc()): ?>
            <tr>
                <td><strong><?= $a['nome'] ?></strong></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="step" value="scegli_mosse">
                        <input type="hidden" name="slot" value="<?= $slot_corrente ?>">
                        <input type="hidden" name="pokemon_cod" value="<?= $pokemon_cod ?>">
                        <input type="hidden" name="sec_form" value="<?= $sec_form ?>">
                        <input type="hidden" name="abilita_id" value="<?= $a['id_Abilita'] ?>">
                        <button type="submit" class="choice-btn">Scegli Abilità</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

    <?php elseif($step === 'scegli_mosse'): ?>
        <h3>Scegli le mosse (Max 4):</h3>
        <form method="POST">
            <input type="hidden" name="step" value="salva_tutto">
            <input type="hidden" name="slot" value="<?= $slot_corrente ?>">
            <input type="hidden" name="pokemon_cod" value="<?= $pokemon_cod ?>">
            <input type="hidden" name="sec_form" value="<?= $sec_form ?>">
            <input type="hidden" name="abilita_id" value="<?= $_POST['abilita_id'] ?>">
            <table class="pokedex-table">
                <?php
                $stmt = $conn->prepare("SELECT m.id_Mossa, m.nome FROM Mossa_x_pokemon mp JOIN Mossa m ON mp.id_mossa = m.id_Mossa WHERE mp.cod = ?");
                $stmt->bind_param("i", $pokemon_cod);
                $stmt->execute();
                $res = $stmt->get_result();
                while($m = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $m['nome'] ?></td>
                    <td><input type="checkbox" name="mosse[]" value="<?= $m['id_Mossa'] ?>"></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit" class="main-btn" style="margin-top:20px;">Conferma Squadra</button>
        </form>
    <?php endif; ?>

</div>
</div>
</body>
</html>
<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}



//////////////////////////////////////////////////////////////////////////////////////////////////
// Recupero filtri
$nomeFiltro = $_GET['nome'] ?? '';
$type1Filtro = $_GET['type1'] ?? '';
$type2Filtro = $_GET['type2'] ?? '';
$minCod = $_GET['min'] ?? '';
$maxCod = $_GET['max'] ?? '';

// Query base
$sql = "SELECT cod, sec_form, nome, tipo1, tipo2 FROM Pokemon WHERE 1=1";

// Filtro nome
if (!empty($nomeFiltro)) {
    $nomeFiltro = $conn->real_escape_string($nomeFiltro);
    $sql .= " AND nome LIKE '%$nomeFiltro%'";
}

// Filtro tipo 1
if (!empty($type1Filtro)) {
    $type1Filtro = $conn->real_escape_string($type1Filtro);
    $sql .= " AND (tipo1 = '$type1Filtro' OR tipo2 = '$type1Filtro')";
}

// Filtro tipo 2
if (!empty($type2Filtro)) {
    $type2Filtro = $conn->real_escape_string($type2Filtro);
    $sql .= " AND (tipo1 = '$type2Filtro' OR tipo2 = '$type2Filtro')";
}

// Range codice
if (!empty($minCod)) {
    $minCod = (int)$minCod;
    $sql .= " AND cod >= $minCod";
}

if (!empty($maxCod)) {
    $maxCod = (int)$maxCod;
    $sql .= " AND cod <= $maxCod";
}

$sql .= " ORDER BY cod ASC, sec_form ASC";


$result = $conn->query($sql);
//////////////////////////////////////////////////////////////////////////////////////////////////






?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pokédex Project</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Stili per i link di navigazione */
        .nav-links {
            display: flex;
            gap: 15px;
            margin-left: 30px;
        }
        .nav-link {
            padding: 8px 15px;
            background-color: #ffde00;
            border: 2px solid #3b4cca;
            border-radius: 20px;
            text-decoration: none;
            color: black;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: #3b4cca;
            color: white;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #ff0000;
            border-bottom: 6px solid black;
            flex-wrap: wrap;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        .hero-btn {
            padding: 12px 25px;
            background-color: #ffde00;
            border: 3px solid #3b4cca;
            border-radius: 30px;
            text-decoration: none;
            color: black;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .hero-btn:hover {
            background-color: #3b4cca;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="index-page">

<!-- HEADER CON MENU DI NAVIGAZIONE -->
<header>
    <div class="header-left">
        <div class="logo">
            <h1>PokéYZ</h1>
        </div>

        <!-- MENU DI NAVIGAZIONE AGGIUNTO -->
        <div class="nav-links">
            <a href="index.php" class="nav-link" style="background-color: #3b4cca; color: white;">Pokédex</a>
            <a href="mosse.php" class="nav-link">Mosse</a>
            <a href="abilita.php" class="nav-link">Abilità</a>
        </div>
    </div>

    <div class="login-container">
    <a href="login.php" class="login-bar">
        <img src="Login.png" alt="Login">

        <?php if(isset($_SESSION['user_id'])): ?>
            <span>Ciao <?= ucfirst($_SESSION['username']) ?></span>
        <?php else: ?>
            <span>Login / Registrati</span>
        <?php endif; ?>

    </a>
</div>
</header>

<!-- HERO SECTION CON BOTTONI AGGIUNTI -->
<section class="hero">
    <h2>Benvenuto Allenatore!</h2>
    <p>Esplora il mondo dei Pokémon e crea la tua squadra perfetta.</p>
    
    <!-- BOTTONI VELOCI AGGIUNTI -->
    <div class="hero-buttons">
        <a href="mosse.php" class="hero-btn">Esplora Mosse</a>
        <a href="abilita.php" class="hero-btn">Scopri Abilità</a>
    </div>
</section>

<!-- POKEDEX TABLE -->
<section class="pokedex-section">
    <h2>Elenco Pokémon</h2>


    <form method="GET" class="filter-box">

    <!-- Ricerca nome -->
    <input type="text" name="nome" 
           placeholder="Cerca per nome..."
           value="<?= htmlspecialchars($nomeFiltro) ?>">

    <!-- Tipo 1 -->
    <select name="type1">
        <option value="">Tipo 1</option>
        <option value="bug" <?= $type1Filtro == 'bug' ? 'selected' : '' ?>>Bug</option>
        <option value="dark" <?= $type1Filtro == 'dark' ? 'selected' : '' ?>>Dark</option>
        <option value="dragon" <?= $type1Filtro == 'dragon' ? 'selected' : '' ?>>Dragon</option>
        <option value="electric" <?= $type1Filtro == 'electric' ? 'selected' : '' ?>>Electric</option>
        <option value="fairy" <?= $type1Filtro == 'fairy' ? 'selected' : '' ?>>Fairy</option>
        <option value="fighting" <?= $type1Filtro == 'fighting' ? 'selected' : '' ?>>Fighting</option>
        <option value="fire" <?= $type1Filtro == 'fire' ? 'selected' : '' ?>>Fire</option>
        <option value="flying" <?= $type1Filtro == 'flying' ? 'selected' : '' ?>>Flying</option>
        <option value="ghost" <?= $type1Filtro == 'ghost' ? 'selected' : '' ?>>Ghost</option>
        <option value="grass" <?= $type1Filtro == 'grass' ? 'selected' : '' ?>>Grass</option>
        <option value="ground" <?= $type1Filtro == 'ground' ? 'selected' : '' ?>>Ground</option>
        <option value="ice" <?= $type1Filtro == 'ice' ? 'selected' : '' ?>>Ice</option>
        <option value="normal" <?= $type1Filtro == 'normal' ? 'selected' : '' ?>>Normal</option>
        <option value="poison" <?= $type1Filtro == 'poison' ? 'selected' : '' ?>>Poison</option>
        <option value="psychic" <?= $type1Filtro == 'psychic' ? 'selected' : '' ?>>Psychic</option>
        <option value="rock" <?= $type1Filtro == 'rock' ? 'selected' : '' ?>>Rock</option>
        <option value="steel" <?= $type1Filtro == 'steel' ? 'selected' : '' ?>>Steel</option>
        <option value="water" <?= $type1Filtro == 'water' ? 'selected' : '' ?>>Water</option>

    </select>

    <!-- Tipo 2 -->
    <select name="type2">
        <option value="">Tipo 2</option>
        <option value="bug" <?= $type2Filtro == 'bug' ? 'selected' : '' ?>>Bug</option>
        <option value="dark" <?= $type2Filtro == 'dark' ? 'selected' : '' ?>>Dark</option>
        <option value="dragon" <?= $type2Filtro == 'dragon' ? 'selected' : '' ?>>Dragon</option>
        <option value="electric" <?= $type2Filtro == 'electric' ? 'selected' : '' ?>>Electric</option>
        <option value="fairy" <?= $type2Filtro == 'fairy' ? 'selected' : '' ?>>Fairy</option>
        <option value="fighting" <?= $type2Filtro == 'fighting' ? 'selected' : '' ?>>Fighting</option>
        <option value="fire" <?= $type2Filtro == 'fire' ? 'selected' : '' ?>>Fire</option>
        <option value="flying" <?= $type2Filtro == 'flying' ? 'selected' : '' ?>>Flying</option>
        <option value="ghost" <?= $type2Filtro == 'ghost' ? 'selected' : '' ?>>Ghost</option>
        <option value="grass" <?= $type2Filtro == 'grass' ? 'selected' : '' ?>>Grass</option>
        <option value="ground" <?= $type2Filtro == 'ground' ? 'selected' : '' ?>>Ground</option>
        <option value="ice" <?= $type2Filtro == 'ice' ? 'selected' : '' ?>>Ice</option>
        <option value="normal" <?= $type2Filtro == 'normal' ? 'selected' : '' ?>>Normal</option>
        <option value="poison" <?= $type2Filtro == 'poison' ? 'selected' : '' ?>>Poison</option>
        <option value="psychic" <?= $type2Filtro == 'psychic' ? 'selected' : '' ?>>Psychic</option>
        <option value="rock" <?= $type2Filtro == 'rock' ? 'selected' : '' ?>>Rock</option>
        <option value="steel" <?= $type2Filtro == 'steel' ? 'selected' : '' ?>>Steel</option>
        <option value="water" <?= $type2Filtro == 'water' ? 'selected' : '' ?>>Water</option>

    </select>

    <!-- Range codice -->
    <input type="number" name="min" placeholder="Da #" min="1"
           value="<?= htmlspecialchars($minCod) ?>">

    <input type="number" name="max" placeholder="A #" min="1"
           value="<?= htmlspecialchars($maxCod) ?>">

    <button type="submit">Filtra</button>
    <a href="index.php" class="reset-btn">Reset</a>

</form>



    <table class="pokedex-table">
        <tr>
            <th>Immagine</th>
            <th>#</th>
            <th>Nome</th>
            <th>Tipo</th>
        </tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $cod = str_pad($row['cod'], 4, "0", STR_PAD_LEFT);
        $forma = $row['sec_form'];
        $nome = $row['nome'];

        echo "<tr>";

        // Nome immagine
        $imgName = strtolower($nome);
        if($forma != "BASE"){
            $imgName .= "_" . strtolower($forma);
        }


        //spero funzioni
        echo "<td>
                <img src='Img/$imgName.png' 
                     class='poke-img'
                     alt='$nome'>
              </td>";

        echo "<td>$cod</td>";

        if($forma != "BASE"){
            echo "<td>" . ucfirst($nome) . " (" . ucfirst($forma) . ")</td>";
        } else {
            echo "<td>" . ucfirst($nome) . "</td>";
        }

        echo "<td>";
        if(!empty($row['tipo1'])){
            echo "<span class='type {$row['tipo1']}'>" . ucfirst($row['tipo1']) . "</span> ";
        }
        if(!empty($row['tipo2'])){
            echo "<span class='type {$row['tipo2']}'>" . ucfirst($row['tipo2']) . "</span>";
        }
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Nessun Pokémon trovato.</td></tr>";
}
?>

    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
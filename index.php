<?php
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





/*$sql = "
SELECT cod, sec_form, nome, tipo1, tipo2
FROM Pokemon
ORDER BY cod ASC, sec_form ASC
";

$result = $conn->query($sql);*/
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pokédex Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo">
        <h1>PokéYZ</h1>
    </div>

    <div class="login-container">
        <div class="login-bar">
            <img src="Login.png" alt="Login">
            <span>Login / Registrati</span>
        </div>
    </div>
</header>

<!-- HERO SECTION -->
<section class="hero">
    <h2>Benvenuto Allenatore!</h2>
    <p>Esplora il mondo dei Pokémon e crea la tua squadra perfetta.</p>
    <button class="main-btn">Scopri i Pokémon</button>
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
        <option value="fire">Fire</option>
        <option value="water">Water</option>
        <option value="grass">Grass</option>
        <option value="electric">Electric</option>
        <option value="ghost">Ghost</option>
        <option value="dragon">Dragon</option>
        <!-- aggiungi gli altri -->
    </select>

    <!-- Tipo 2 -->
    <select name="type2">
        <option value="">Tipo 2</option>
        <option value="fire">Fire</option>
        <option value="water">Water</option>
        <option value="grass">Grass</option>
        <option value="electric">Electric</option>
        <option value="ghost">Ghost</option>
        <option value="dragon">Dragon</option>
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

        echo "<td>
                <img src='images/$imgName.png' 
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

<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$sql = "
SELECT cod, sec_form, nome, tipo1, tipo2
FROM Pokemon
ORDER BY cod ASC, sec_form ASC
";

$result = $conn->query($sql);
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

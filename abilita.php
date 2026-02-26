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

// Query base - ORDINATA PER ID
$sql = "SELECT * FROM abilita WHERE 1=1";

// Filtro nome
if (!empty($nomeFiltro)) {
    $nomeFiltro = $conn->real_escape_string($nomeFiltro);
    $sql .= " AND nome LIKE '%$nomeFiltro%'";
}

// ORDINAMENTO PER ID
$sql .= " ORDER BY id_abilita ASC";

$result = $conn->query($sql);
//////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>PokéYZ - Abilità</title>
    <link rel="stylesheet" href="style.css">
    <style>
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
        }
        .nav-link:hover {
            background-color: #3b4cca;
            color: white;
        }
        .nav-link.active {
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
        .abilita-table th {
            background-color: #3b4cca;
            color: white;
        }
        .abilita-id {
            font-weight: bold;
            color: #3b4cca;
        }
    </style>
</head>
<body>

<!-- HEADER CON MENU DI NAVIGAZIONE -->
<header>
    <div class="header-left">
        <div class="logo">
            <h1>PokéYZ</h1>
        </div>

        <div class="nav-links">
            <a href="index.php" class="nav-link">Pokédex</a>
            <a href="mosse.php" class="nav-link">Mosse</a>
            <a href="abilita.php" class="nav-link active">Abilità</a>
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

<!-- HERO SECTION -->
<section class="hero">
    <h2>Elenco Abilità</h2>
    <p>Scopri tutte le abilità dei Pokémon e i loro effetti in battaglia.</p>
</section>

<!-- FILTRI -->
<section class="pokedex-section">
    <form method="GET" class="filter-box">
        <input type="text" name="nome" placeholder="Cerca abilità..." value="<?= htmlspecialchars($nomeFiltro) ?>">
        <button type="submit">Filtra</button>
        <a href="abilita.php" class="reset-btn">Reset</a>
    </form>

    <!-- TABELLA ABILITÀ -->
    <table class="pokedex-table abilita-table">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Effetto</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='abilita-id'>#" . $row['id_abilita'] . "</td>";
                echo "<td><strong>" . ucfirst($row['nome']) . "</strong></td>";
                echo "<td>" . ($row['effetto'] ?? 'Nessun effetto descritto') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Nessuna abilità trovata.</td></tr>";
        }
        ?>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
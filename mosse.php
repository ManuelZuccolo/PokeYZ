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
$tipoFiltro = $_GET['tipo'] ?? '';
$categoriaFiltro = $_GET['categoria'] ?? '';
$minDanno = $_GET['min_danno'] ?? '';
$maxDanno = $_GET['max_danno'] ?? '';

// Query base - ORDINATA PER ID
$sql = "SELECT * FROM mossa WHERE 1=1";

// Filtro nome
if (!empty($nomeFiltro)) {
    $nomeFiltro = $conn->real_escape_string($nomeFiltro);
    $sql .= " AND nome LIKE '%$nomeFiltro%'";
}

// Filtro tipo
if (!empty($tipoFiltro)) {
    $tipoFiltro = $conn->real_escape_string($tipoFiltro);
    $sql .= " AND tipo = '$tipoFiltro'";
}

// Filtro categoria
if (!empty($categoriaFiltro)) {
    $categoriaFiltro = $conn->real_escape_string($categoriaFiltro);
    $sql .= " AND categoria = '$categoriaFiltro'";
}

// Range danno
if (!empty($minDanno)) {
    $minDanno = (int)$minDanno;
    $sql .= " AND danno >= $minDanno";
}

if (!empty($maxDanno)) {
    $maxDanno = (int)$maxDanno;
    $sql .= " AND danno <= $maxDanno";
}

// ORDINAMENTO PER ID
$sql .= " ORDER BY id_mossa ASC";

$result = $conn->query($sql);
//////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>PokéYZ - Mosse</title>
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
        .categoria-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .categoria-physical { background-color: #c92121; }
        .categoria-special { background-color: #5a5ad8; }
        .categoria-status { background-color: #a8a878; }
        .danno-value { font-weight: bold; color: #e65c5c; }
        .moves-table th { 
            background-color: #3b4cca; 
            color: white;
            text-align: center;
        }
        .moves-table td {
            vertical-align: middle;
            padding: 10px 5px;
        }
        .descrizione-cell {
            font-size: 13px;
            color: #555;
            max-width: 300px;
            line-height: 1.4;
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
            <a href="mosse.php" class="nav-link active">Mosse</a>
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

<!-- HERO SECTION -->
<section class="hero">
    <h2>Elenco Mosse</h2>
    <p>Scopri tutte le mosse disponibili e i loro effetti.</p>
</section>

<!-- FILTRI -->
<section class="pokedex-section">
    <form method="GET" class="filter-box">
        <input type="text" name="nome" placeholder="Cerca mossa..." value="<?= htmlspecialchars($nomeFiltro) ?>">
        
        <select name="tipo">
            <option value="">Tipo</option>
            <option value="normal" <?= $tipoFiltro == 'normal' ? 'selected' : '' ?>>Normale</option>
            <option value="fire" <?= $tipoFiltro == 'fire' ? 'selected' : '' ?>>Fuoco</option>
            <option value="water" <?= $tipoFiltro == 'water' ? 'selected' : '' ?>>Acqua</option>
            <option value="electric" <?= $tipoFiltro == 'electric' ? 'selected' : '' ?>>Elettro</option>
            <option value="grass" <?= $tipoFiltro == 'grass' ? 'selected' : '' ?>>Erba</option>
            <option value="ice" <?= $tipoFiltro == 'ice' ? 'selected' : '' ?>>Ghiaccio</option>
            <option value="fighting" <?= $tipoFiltro == 'fighting' ? 'selected' : '' ?>>Lotta</option>
            <option value="poison" <?= $tipoFiltro == 'poison' ? 'selected' : '' ?>>Veleno</option>
            <option value="ground" <?= $tipoFiltro == 'ground' ? 'selected' : '' ?>>Terra</option>
            <option value="flying" <?= $tipoFiltro == 'flying' ? 'selected' : '' ?>>Volante</option>
            <option value="psychic" <?= $tipoFiltro == 'psychic' ? 'selected' : '' ?>>Psico</option>
            <option value="bug" <?= $tipoFiltro == 'bug' ? 'selected' : '' ?>>Coleottero</option>
            <option value="rock" <?= $tipoFiltro == 'rock' ? 'selected' : '' ?>>Roccia</option>
            <option value="ghost" <?= $tipoFiltro == 'ghost' ? 'selected' : '' ?>>Spettro</option>
            <option value="dragon" <?= $tipoFiltro == 'dragon' ? 'selected' : '' ?>>Drago</option>
            <option value="dark" <?= $tipoFiltro == 'dark' ? 'selected' : '' ?>>Buio</option>
            <option value="steel" <?= $tipoFiltro == 'steel' ? 'selected' : '' ?>>Acciaio</option>
            <option value="fairy" <?= $tipoFiltro == 'fairy' ? 'selected' : '' ?>>Folletto</option>
        </select>

        <select name="categoria">
            <option value="">Categoria</option>
            <option value="physical" <?= $categoriaFiltro == 'physical' ? 'selected' : '' ?>>Fisico</option>
            <option value="special" <?= $categoriaFiltro == 'special' ? 'selected' : '' ?>>Speciale</option>
            <option value="status" <?= $categoriaFiltro == 'status' ? 'selected' : '' ?>>Stato</option>
        </select>

        <input type="number" name="min_danno" placeholder="Danno min" min="0" value="<?= htmlspecialchars($minDanno) ?>">
        <input type="number" name="max_danno" placeholder="Danno max" min="0" value="<?= htmlspecialchars($maxDanno) ?>">

        <button type="submit">Filtra</button>
        <a href="mosse.php" class="reset-btn">Reset</a>
    </form>

    <!-- TABELLA MOSSE CON DESCRIZIONE -->
    <table class="pokedex-table moves-table">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Categoria</th>
            <th>Danno</th>
            <th>Precisione</th>
            <th>PP</th>
            <th>Descrizione</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $categoriaClass = '';
                $categoriaTesto = '';
                
                switch($row['categoria']) {
                    case 'physical':
                        $categoriaClass = 'categoria-physical';
                        $categoriaTesto = 'Fisico';
                        break;
                    case 'special':
                        $categoriaClass = 'categoria-special';
                        $categoriaTesto = 'Speciale';
                        break;
                    case 'status':
                        $categoriaClass = 'categoria-status';
                        $categoriaTesto = 'Stato';
                        break;
                }

                echo "<tr>";
                echo "<td style='text-align: center; font-weight: bold;'>#" . $row['id_mossa'] . "</td>";
                echo "<td><strong>" . ucfirst($row['nome']) . "</strong></td>";
                echo "<td style='text-align: center;'><span class='type " . strtolower($row['tipo']) . "'>" . ucfirst($row['tipo']) . "</span></td>";
                echo "<td style='text-align: center;'><span class='categoria-badge $categoriaClass'>$categoriaTesto</span></td>";
                echo "<td style='text-align: center;' class='danno-value'>" . ($row['danno'] ?? '—') . "</td>";
                echo "<td style='text-align: center;'>" . ($row['accuratezza'] ?? '—') . "%</td>";
                echo "<td style='text-align: center;'>" . $row['pp'] . "</td>";
                
                // Descrizione
                $descrizione = $row['descrizione'] ?? 'Nessuna descrizione disponibile.';
                echo "<td class='descrizione-cell'>" . htmlspecialchars($descrizione) . "</td>";
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align: center;'>Nessuna mossa trovata.</td></tr>";
        }
        ?>
    </table>
    
    <!-- Statistiche -->
    <div style="margin-top: 20px; text-align: right; font-size: 14px; color: #666;">
        Totale mosse: <?= $result->num_rows ?>
    </div>
</section>

</body>
</html>

<?php $conn->close(); ?>
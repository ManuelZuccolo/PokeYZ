<?php
session_start();

// Configurazione database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connessione fallita"]));
}

$cod = isset($_GET['cod']) ? intval($_GET['cod']) : 0;
$sec_form = isset($_GET['sec_form']) ? $_GET['sec_form'] : '';
$slot = isset($_GET['slot']) ? intval($_GET['slot']) : 0;
$id_squadra = isset($_GET['id_squadra']) ? intval($_GET['id_squadra']) : 0;

if (!$cod || !$id_squadra) {
    echo json_encode([]);
    exit;
}

// Recupera gli ID delle mosse dalla squadra_pokemon
$sql_squadra = "SELECT mossa1, mossa2, mossa3, mossa4 
                FROM squadra_pokemon 
                WHERE id_squadra = $id_squadra
                AND cod = $cod
                AND sec_form = '$sec_form'
                AND slot = $slot";

$result = $conn->query($sql_squadra);

if (!$result || $result->num_rows == 0) {
    // Se non trova mosse nella squadra, cerca mosse casuali per il Pokémon
    $sql_mosse_random = "SELECT m.* FROM mossa m
                        JOIN mossa_x_pokemon mxp ON m.id_mossa = mxp.id_mossa
                        WHERE mxp.cod = $cod AND mxp.sec_form = '$sec_form'
                        ORDER BY RAND() LIMIT 4";
    
    $result_mosse = $conn->query($sql_mosse_random);
    $mosse = [];
    
    if ($result_mosse && $result_mosse->num_rows > 0) {
        while($row = $result_mosse->fetch_assoc()) {
            $mosse[] = $row;
        }
    }
    
    echo json_encode($mosse);
    exit;
}

$row = $result->fetch_assoc();
$mosse_ids = [];

for ($i = 1; $i <= 4; $i++) {
    if (!empty($row['mossa' . $i])) {
        $mosse_ids[] = $row['mossa' . $i];
    }
}

if (empty($mosse_ids)) {
    echo json_encode([]);
    exit;
}

$mosse_ids_str = implode(',', $mosse_ids);
$sql_mosse = "SELECT * FROM mossa WHERE id_mossa IN ($mosse_ids_str)";

$result_mosse = $conn->query($sql_mosse);
$mosse = [];

if ($result_mosse && $result_mosse->num_rows > 0) {
    while($row = $result_mosse->fetch_assoc()) {
        $mosse[] = $row;
    }
}

echo json_encode($mosse);
$conn->close();
?>
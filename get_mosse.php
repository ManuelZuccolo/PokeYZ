<?php
session_start();

// Verifica che l'utente sia loggato
if(!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorizzato']);
    exit;
}

$id_giocatore = $_SESSION['user_id'];

// CONFIGURAZIONE DATABASE
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connessione database fallita']);
    exit;
}

// Ottieni i parametri
$cod = isset($_GET['cod']) ? intval($_GET['cod']) : 0;
$sec_form = isset($_GET['sec_form']) ? $_GET['sec_form'] : 'BASE';
$slot = isset($_GET['slot']) ? intval($_GET['slot']) : 0;

if ($cod == 0 || $slot == 0) {
    echo json_encode(['error' => 'Parametri mancanti']);
    exit;
}

// Recupera l'id_squadra dell'utente
$sql_squadra = "SELECT id_squadra FROM squadra WHERE codice_utente = " . $id_giocatore;
$result_squadra = $conn->query($sql_squadra);

if (!$result_squadra || $result_squadra->num_rows == 0) {
    echo json_encode(['error' => 'Squadra non trovata']);
    exit;
}

$row_squadra = $result_squadra->fetch_assoc();
$id_squadra = $row_squadra['id_squadra'];

// Recupera le mosse dalla squadra_pokemon
$sql_mosse_ids = "SELECT mossa1, mossa2, mossa3, mossa4 
                  FROM squadra_pokemon 
                  WHERE id_squadra = " . $id_squadra . "
                  AND cod = " . $cod . "
                  AND sec_form = '" . $conn->real_escape_string($sec_form) . "'
                  AND slot = " . $slot;

$result_mosse_ids = $conn->query($sql_mosse_ids);

if (!$result_mosse_ids || $result_mosse_ids->num_rows == 0) {
    echo json_encode([]);
    exit;
}

$row = $result_mosse_ids->fetch_assoc();
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

// Recupera i dettagli delle mosse
$mosse_ids_str = implode(',', $mosse_ids);
$sql_mosse = "SELECT * FROM mossa WHERE id_mossa IN (" . $mosse_ids_str . ")";
$result_mosse = $conn->query($sql_mosse);

$mosse = [];
if ($result_mosse && $result_mosse->num_rows > 0) {
    while($row = $result_mosse->fetch_assoc()) {
        $mosse[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($mosse);

$conn->close();
?>
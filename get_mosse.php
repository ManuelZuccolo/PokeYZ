<?php
// get_mosse.php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokeyz_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connessione fallita']));
}

$cod = isset($_GET['cod']) ? intval($_GET['cod']) : 0;
$sec_form = isset($_GET['sec_form']) ? $_GET['sec_form'] : 'BASE';

if ($cod > 0) {
    $sql_mosse = "SELECT m.* FROM mossa m
                  JOIN mossa_x_pokemon mxp ON m.id_mossa = mxp.id_mossa
                  WHERE mxp.cod = " . $cod . " 
                  AND mxp.sec_form = '" . $conn->real_escape_string($sec_form) . "'";
    
    $result_mosse = $conn->query($sql_mosse);
    
    $mosse = [];
    if ($result_mosse && $result_mosse->num_rows > 0) {
        while($row = $result_mosse->fetch_assoc()) {
            $mosse[] = $row;
        }
    }
    
    echo json_encode($mosse);
} else {
    echo json_encode(['error' => 'Codice Pokémon non valido']);
}

$conn->close();
?>
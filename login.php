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

$error = "";
$success = "";

$mode = $_GET['mode'] ?? 'login';

/* ======================
   REGISTRAZIONE
====================== */
if(isset($_POST['register'])){

    $nome = strtolower(trim($_POST['nome']));
    $pass = $_POST['password'];

    if(!empty($nome) && !empty($pass)){

        // Controllo se esiste già
        $check = $conn->prepare("SELECT codice FROM Utente WHERE nome = ?");
        $check->bind_param("s", $nome);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $error = "Nome utente già esistente.";
        } else {

            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // NON inseriamo più il codice!
            $stmt = $conn->prepare("INSERT INTO Utente (nome, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome, $hash);

            if($stmt->execute()){
                $user_id = $stmt->insert_id; // codice utente appena creato
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $nome;

                // CREAZIONE SQUADRA AUTOMATICA
                $stmt_squadra = $conn->prepare("INSERT INTO Squadra (id_squadra, codice_utente) VALUES (?, ?)");
                $stmt_squadra->bind_param("ii", $user_id, $user_id);
                $stmt_squadra->execute();

                header("Location: login.php");
                exit();
            } else {
                $error = "Errore durante la registrazione.";
            }
        }

    } else {
        $error = "Compila tutti i campi.";
    }
}

/* ======================
   LOGIN
====================== */
if(isset($_POST['login'])){

    $nome = strtolower(trim($_POST['nome']));
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT codice, password FROM Utente WHERE nome = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $row = $result->fetch_assoc();

        if(password_verify($pass, $row['password'])){
            $_SESSION['user_id'] = $row['codice'];
            $_SESSION['username'] = $nome;

            header("Location: login.php");
            exit();
        } else {
            $error = "Password errata.";
        }

    } else {
        $error = "Utente non trovato.";
    }
}

/* ======================
   LOGOUT
====================== */
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - PokéYZ</title>
    <link rel="stylesheet" href="style.css?v=1">
</head>
<body>

<header>
    <div class="logo">
        <h1>PokéYZ</h1>
    </div>
</header>

<div class="auth-wrapper">

<?php if(!isset($_SESSION['user_id'])): ?>

    <div class="auth-card">

        <?php if($mode === 'login'): ?>

            <h2>Login</h2>

            <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>
            <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>

            <form method="POST">
                <input type="text" name="nome" placeholder="Nome utente" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="login" class="main-btn">Accedi</button>
                <a href="index.php" class="cancel-btn">Annulla</a>
            </form>

            <p class="switch-mode">
                Non hai un account?
                <a href="login.php?mode=register">Registrati</a>
            </p>

        <?php else: ?>

            <h2>Registrazione</h2>

            <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>
            <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>

            <form method="POST">
                <input type="text" name="nome" placeholder="Scegli nome utente" required>
                <input type="password" name="password" placeholder="Scegli password" required>

                <button type="submit" name="register" class="main-btn">Registrati</button>
                <a href="index.php" class="cancel-btn">Annulla</a>
            </form>

            <p class="switch-mode">
                Hai già un account?
                <a href="login.php">Login</a>
            </p>

        <?php endif; ?>

    </div>

<?php else: ?>

<div class="auth-card">
    <h2>Benvenuto <?= ucfirst($_SESSION['username']) ?>!</h2>

    <div class="btn-wrapper">
    <a href="squadra.php" class="main-btn">Vai alla mia squadra</a>
    <a href="index.php" class="cancel-btn">Torna alla Home</a>
    <a href="login.php?logout=true" class="cancel-btn">Logout</a>
</div>
</div>

<?php endif; ?>

</div>

</body>
</html>

<?php $conn->close(); ?>
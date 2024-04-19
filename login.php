<?php
// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "przesyl";



$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require 'php/notification.php';

// Funkcja do weryfikacji danych logowania
function verifyLogin($username, $password, $conn) {
    $sql = "SELECT * FROM uzytkownicy WHERE nazwa_uzytkownika = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["haslo"])) {
            return $row;
        }
    }
    return false;
}

// Sprawdzenie czy formularz logowania został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Weryfikacja danych logowania
    $user = verifyLogin($username, $password, $conn);

    if ($user) {
        // Ustawienie ciasteczka
        $session_data = array(
            'logged' => true,
            'username' => $user["nazwa_uzytkownika"]
        );
        setcookie('session', json_encode($session_data), time() + (86400 * 30), "/"); // Ciasteczko będzie ważne przez 30 dni

        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["nazwa_uzytkownika"];
        $_SESSION["role"] = $user["ranga"];
        header("Location: index.php"); // Przekierowanie po zalogowaniu
        exit();
    } else {
        setNotification("Niepoprawne hasło lub nazwa użytkownika.", 'error');
    }
}

// Sprawdzenie czy użytkownik jest już zalogowany
session_start();
if(isset($_SESSION["user_id"])) {
    // Użytkownik jest zalogowany
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWecC7oVrGKs5PrTV29r7vVz5O9+YRwCsj+N/+4nXf3zJp9U8oyn/9v7s8D7" crossorigin="anonymous">
    <title>Logowanie</title>
</head>
<body>

<h2>Logowanie</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Nazwa użytkownika: <input type="text" name="username"><br>
    Hasło: <input type="password" name="password"><br>
    <input type="submit" value="Zaloguj">
    <p><a href="index.php">Wejdź jako gość</a></p>
</form>

<?php
if (isset($login_error)) {
    echo "<p>$login_error</p>";
}
?>

</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const notification = document.querySelector('.notification');
    if (notification) {
        const timeout = notification.getAttribute('data-timeout');
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.addEventListener('transitionend', function(e) {
                notification.parentNode.removeChild(notification);
            });
        }, timeout);
    }
});
</script>

<?php displayNotification(); ?>
<?php
$conn->close();
?>

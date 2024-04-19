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

// Funkcja do szyfrowania hasła
function encryptPassword($password) {
    // Tutaj możesz użyć dowolnego algorytmu szyfrowania, na przykład:
    return password_hash($password, PASSWORD_DEFAULT);
}

// Sprawdzenie czy formularz rejestracji został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Szyfrowanie hasła
    $encrypted_password = encryptPassword($password);

    // Wstawianie nowego użytkownika do bazy danych
    $sql = "INSERT INTO uzytkownicy (nazwa_uzytkownika, haslo, ranga) VALUES ('$username', '$encrypted_password', 'zwykly_uzytkownik')";
    if ($conn->query($sql) === TRUE) {
        echo "Użytkownik został zarejestrowany pomyślnie.";
    } else {
        echo "Błąd podczas rejestracji użytkownika: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWecC7oVrGKs5PrTV29r7vVz5O9+YRwCsj+N/+4nXf3zJp9U8oyn/9v7s8D7" crossorigin="anonymous">
    <title>Rejstracja</title>
</head>
<body>
<h2>Rejestracja</h2>
<form method="post" action="rejestracja.php">
    Nazwa użytkownika: <input type="text" id="username" name="username" required><br>
    Hasło: <input type="password" id="password" name="password" required><br>
    <input type="submit" value="Zarejestruj">
</form>
</body>
</html>

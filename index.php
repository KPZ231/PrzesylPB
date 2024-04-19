<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="KPZ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Przesył Danych Pozdro 600</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Przesyłanie plików</h2>

    <?php
    // Katalog do przechowywania plików
    $uploadDir = 'uploads/';

    // Funkcja do obsługi przesyłania plików
    function uploadFile($file, $name, $uploadDir) {
        global $uploadDir;
        $uploadFile = $uploadDir . basename($file['name']);
        $name = htmlspecialchars($name);

        // Przesuń plik do katalogu uploads
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo "<p>Plik <strong>$name - {$file['name']}</strong> został przesłany.</p>";
        } else {
            echo "<p>Wystąpił problem podczas przesyłania pliku.</p>";
        }
    }

    // Funkcja do wyświetlania listy plików i ich pobierania
    function displayFiles($uploadDir) {
        global $uploadDir;
        $files = glob($uploadDir . '*');
        echo '<div class="container">';
        echo "<h3>Lista przesłanych plików:</h3>";
        echo '<br>';

        echo "<ul>";
        foreach ($files as $file) {
            $fileName = basename($file);
            echo "<li>$fileName - <a href='$file' download>Pobierz</a></li>";
        }
        echo "</ul>";
        echo "</div>";
    }

    // Obsługa przesłanych plików
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sprawdź czy plik został przesłany poprawnie
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Sprawdź czy zostało podane imię
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                // Wywołaj funkcję uploadFile() z przesłanym plikiem i imieniem
                uploadFile($_FILES['file'], $_POST['name'], $uploadDir);
            } else {
                echo "<p>Musisz podać imię przesyłającego.</p>";
            }
        } else {
            echo "<p>Wystąpił problem podczas przesyłania pliku.</p>";
        }
    }

    // Wyświetlanie listy przesłanych plików
    displayFiles($uploadDir);
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="name">Twoje imię:</label><br>
        <input type="text" id="name" name="name"><br><br>
        <input type="file" name="file"><br><br>
        <input type="submit" value="Prześlij plik">
    </form>

</body>
</html>

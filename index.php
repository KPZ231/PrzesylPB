<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="KPZ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Przesył Danych Pozdro 600</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWecC7oVrGKs5PrTV29r7vVz5O9+YRwCsj+N/+4nXf3zJp9U8oyn/9v7s8D7" crossorigin="anonymous">

</head>
<body>

<?php
    require 'php/notification.php';
    $uploadDir = 'uploads/';
    $validToken = '123'; // Hard-coded token for validation

    // Funkcja do obsługi przesyłania plików
    function uploadFile($file, $name, $uploadDir) {
        global $uploadDir;
        $uploadFile = $uploadDir . basename($file['name']);
        $name = htmlspecialchars($name);

        // Przesuń plik do katalogu uploads
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            setNotification("Plik <strong>$name - {$file['name']}</strong> został przesłany.", 'success');
        } else {
            setNotification("Wystąpił problem podczas przesyłania pliku.", 'error');
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
        // Sprawdź czy został podany token i czy jest prawidłowy
        if (isset($_POST['token']) && $_POST['token'] === $validToken) {
            // Sprawdź czy plik został przesłany poprawnie
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                // Sprawdź czy zostało podane imię
                if (isset($_POST['name']) && !empty($_POST['name'])) {
                    // Wywołaj funkcję uploadFile() z przesłanym plikiem i imieniem
                    uploadFile($_FILES['file'], $_POST['name'], $uploadDir);
                } else {
                    setNotification("Musisz podać imię przesyłającego.", 'error');

                }
            } else {
                setNotification("Wystąpił problem podczas przesyłania pliku.", 'error');
            }
        } else {
            setNotification("Nieprawidłowy Token.", 'error');
        }
    }

    // Wyświetlanie listy przesłanych plików
    displayFiles($uploadDir);
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <h2> Prześlij Plik </h2>
        <label for="name"><i class="fas fa-user"></i>Twoje imię:</label><br>
        <input type="text" id="name" name="name"><br><br>
        <label for="token"><i class="fas fa-user-shield"></i>Token:</label><br>
<input type="text" id="token" name="token" required><br><br>

        <label for="file-upload" class="custom-file-upload">
    <input id="file-upload" type="file" name="file" style="display:none;" onchange="document.getElementById('file-name').textContent = this.files[0].name"/>
    
    <span id="file-name">Wybierz plik</span>
</label>

        <input type="submit" value="Prześlij plik">
    </form>

</body>
<script>
  document.getElementById('file-upload').onchange = function () {
    document.getElementById('file-name').textContent = this.files[0].name;
  };
</script>
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

</html>

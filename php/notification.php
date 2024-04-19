<?php
session_start();

function setNotification($message, $type = 'error') {
    $_SESSION['notification'] = [
        'message' => $message,
        'type' => $type // 'error' or 'success'
    ];
}

function displayNotification() {
    if (isset($_SESSION['notification'])) {
        $message = $_SESSION['notification']['message'];
        $type = $_SESSION['notification']['type'];
        echo "<div class='notification {$type}' data-timeout='2000'>{$message}</div>"; // 2000 milliseconds
        unset($_SESSION['notification']); // Clear the message after displaying
    }
}

?>

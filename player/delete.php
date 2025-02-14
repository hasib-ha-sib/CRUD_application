<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "football";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $connection->prepare("DELETE FROM players WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: /player/index.php"); // Redirect to the player list page after deletion
        exit; // Ensure the script stops executing after redirection
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>Player ID not specified.</div>";
}

$connection->close();
?>

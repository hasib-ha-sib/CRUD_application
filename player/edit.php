<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "football";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$id = $name = $club = $position = $country = $dob = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET method: Show the data of the player
    if (!isset($_GET["id"])) {
        header("location: /player/index.php");
        exit;
    }

    $id = $_GET["id"];
    // Read the row of the selected player from the database table
    $sql = "SELECT * FROM players WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /player/index.php");
        exit;
    }

    $name = $row["name"];
    $club = $row["club"];
    $position = $row["position"];
    $country = $row["country"];
    // Check if dob column exists before accessing it
    $dob = isset($row["dob"]) ? $row["dob"] : "";
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST method: Update the data of the player
    $id = $_POST['id'];
    $name = $_POST["name"];
    $club = $_POST["club"];
    $position = $_POST["position"];
    $country = $_POST["country"];
    $dob = $_POST["dob"];

    if (empty($id) || empty($name) || empty($club) || empty($position) || empty($country) || empty($dob)) {
        $errorMessage = "All the fields are required";
    } else {
        // Update query, making sure dob is handled
        $stmt = $connection->prepare("UPDATE players SET name = ?, club = ?, position = ?, country = ?, dob = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $club, $position, $country, $dob, $id);

        if ($stmt->execute()) {
            $successMessage = "Player updated successfully";
            header("location: /player/index.php");
            exit;
        } else {
            $errorMessage = "Database error: " . $connection->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Football Player</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>Edit Football Player</h2>

    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?php echo $errorMessage; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Club</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="club" value="<?php echo htmlspecialchars($club); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Position</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($position); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Country</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($country); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Date of Birth</label>
            <div class="col-sm-6">
                <input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            </div>
        </div>

        <?php if (!empty($successMessage)): ?>
        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-6">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $successMessage; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="/player/index.php" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>

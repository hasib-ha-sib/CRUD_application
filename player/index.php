<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Players</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2>List of Football Players</h2>
    <a class="btn btn-primary" href="/player/create.php" role="button">New Player</a> <!-- Fixed path -->
    <br><br>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Club</th>
                <th>Position</th>
                <th>Country</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "football";
        $connection = new mysqli($servername, $username, $password, $database);
        if ($connection->connect_error) {
            die("<div class='alert alert-danger'>Connection failed: " . $connection->connect_error . "</div>");
        }

        // Fetch players with pagination
        $limit = 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $stmt = $connection->prepare("SELECT * FROM players LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['club']) . "</td>
                    <td>" . htmlspecialchars($row['position']) . "</td>
                    <td>" . htmlspecialchars($row['country']) . "</td>
                    <td>" . htmlspecialchars($row['dob']) . "</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='/player/edit.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a>
                        <a class='btn btn-danger btn-sm' href='/player/delete.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No players found.</td></tr>";
        }

        // Pagination
        $stmt = $connection->prepare("SELECT COUNT(*) AS total FROM players");
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $totalPages = ceil($total / $limit);
        
        // Display pagination
        echo "<tr><td colspan='7' class='text-center'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=$i' class='btn btn-outline-primary btn-sm'>$i</a> ";
        }
        echo "</td></tr>";

        $connection->close();
        ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: #2E2E2E;
            color: #FFFFFF;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 50px auto;
            background-color: #1C1C1C;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #FFFFFF;
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #333333;
            color: #FFFFFF;
        }
        tr:nth-child(even) {
            background-color: #3E3E3E;
        }
        tr:hover {
            background-color: #575757;
        }
        .edit-button {
            color: #000;
            background-color: #FFD700;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
$serverName = "localhost:3307";
$dbUserName = "root";
$dbPassword = "oliviamumbi2010";
$dbName = "recipedatabase";

// Handling the update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];

    try {
        $pdo = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE registration SET fullname=?, username=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fullname, $username, $id]);

        echo "<p>User information updated successfully.</p>";
    } catch (PDOException $e) {
        die("Error: Could not update the record. " . $e->getMessage());
    }

    $pdo = null;
}

// Pagination settings
$limit = 10;  // Number of entries to show in a page.
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page-1) * $limit;

try {
    $pdo = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT id, fullname, username FROM registration LIMIT $start_from, $limit");
    $stmt->execute();

    // Set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $results = $stmt->fetchAll();

    echo "<table>";
    echo "<tr><th>Id</th><th>Fullname</th><th>Username</th><th>Action</th></tr>";

    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<form method='POST' action=''>";
        echo "<td><input type='text' name='fullname' value='" . htmlspecialchars($row['fullname']) . "'></td>";
        echo "<td><input type='text' name='username' value='" . htmlspecialchars($row['username']) . "'></td>";
        echo "<td>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <input type='submit' name='update' value='Edit' class='edit-button'>
              </td>";
        echo "</form>";
        echo "</tr>";
    }
    echo "</table>";

    // Pagination
    $stmt = $pdo->prepare("SELECT COUNT(id) FROM registration");
    $stmt->execute();
    $row = $stmt->fetch();
    $total_records = $row[0];
    $total_pages = ceil($total_records / $limit);

    echo "<div style='text-align:center; margin-top:20px;'>";
    for ($i=1; $i<=$total_pages; $i++) {
        echo "<a href='index.php?page=" . $i . "' style='margin:0 10px;'>" . $i . "</a>";
    }
    echo "</div>";

} catch (PDOException $e) {
    die("Error: Could not connect to the database. " . $e->getMessage());
}

$pdo = null;
?>

</body>
</html>

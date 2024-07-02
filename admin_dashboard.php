<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Administrator') {
    header("Location: loginform.html");
    exit();
}


include_once "datab.php";

// Pagination settings
$limit = 10;  // Number of entries to show in a page.
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page-1) * $limit;

// Function to update user information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];

    try {
        $sql = "UPDATE registration SET fullname=?, username=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fullname, $username, $id]);

        echo "<p>User information updated successfully.</p>";
    } catch (PDOException $e) {
        die("Error: Could not update the record. " . $e->getMessage());
    }
}


$sql = "SELECT id, fullname, username, email, phone, user_type FROM registration LIMIT $start_from, $limit";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total number of records
$stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM registration");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_records = $row['total'];
$total_pages = ceil($total_records / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>Admin Dashboard</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>
    <div class="content">
      <h3>All Users</h3>
      <table>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>User Type</th>
          <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
          <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>"></td>
            <td><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td><?php echo htmlspecialchars($user['user_type']); ?></td>
            <td><button type="submit" name="update" class="edit-button">Update</button></td>
          </form>
        </tr>
        <?php endforeach; ?>
      </table>

      <!-- Pagination -->
      <div style="text-align:center; margin-top:20px;">
        <?php for ($i=1; $i<=$total_pages; $i++): ?>
          <a href="?page=<?php echo $i; ?>" style="margin:0 10px;"><?php echo $i; ?></a>
        <?php endfor; ?>
      </div>
      
    </div>
    <div class="footer">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>

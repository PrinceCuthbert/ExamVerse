<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT id, full_name, email FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f9fafb;
    margin: 0;
    padding: 2rem;
    color: #333;
  }

  h1 {
    color: #4c51bf;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    text-align: center;
  }

  .table-container {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    min-width: 600px;
  }

  th, td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    font-size: 0.95rem;
  }

  th {
    background: #667eea;
    color: white;
  }

  tr:last-child td {
    border-bottom: none;
  }

  a.back {
    display: inline-block;
    margin-top: 2rem;
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 1rem;
  }

  a.back:hover {
    color: #4c51bf;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    body {
      padding: 1rem;
    }

    h1 {
      font-size: 1.5rem;
    }

    table {
      font-size: 0.85rem;
      min-width: 100%;
    }

    th, td {
      padding: 0.75rem;
    }
  }

  @media (max-width: 480px) {
    h1 {
      font-size: 1.25rem;
    }

    th, td {
      padding: 0.5rem;
      font-size: 0.8rem;
    }

    a.back {
      font-size: 0.9rem;
    }
  }
</style>

</head>
<body>
  <h1>Admin Panel</h1>
<div class="table-container">
    <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Email</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['full_name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>


  <a href="dashboard.php" class="back">← Back to Dashboard</a>
</body>
</html>

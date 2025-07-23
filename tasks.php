<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $task = $_POST['task'];
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, description) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $task);
    $stmt->execute();
    header("Location: tasks.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, description, completed FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Tasks</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      margin: 0;
      padding: 2rem;
      color: #333;
    }

    body > b {
      display: none;
    }
    h1 {
      color: #4c51bf;
      margin-bottom: 1.5rem;
    }
    form {
      margin-bottom: 1.5rem;
    }
    input[type="text"] {
      padding: 0.6rem;
      width: 250px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      margin-right: 0.5rem;
      box-sizing: border-box;
    }
    button {
      padding: 0.6rem 1.25rem;
      border: none;
      border-radius: 8px;
      background: linear-gradient(90deg, #667eea, #764ba2);
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background: linear-gradient(90deg, #5a67d8, #6b46c1);
    }
    ul {
      list-style: none;
      padding: 0;
      max-width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    li {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
      font-weight: 600;
    }
    li:last-child {
      border-bottom: none;
    }
    .done {
      color: #38a169;
      font-weight: 700;
    }
    a.back {
      display: inline-block;
      margin-top: 2rem;
      color: #667eea;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a.back:hover {
      color: #4c51bf;
    }
  </style>
</head>
<body>
  <h1>My Tasks</h1>

  <form method="POST" action="tasks.php">
    <input type="text" name="task" placeholder="New task" required />
    <button type="submit">Add Task</button>
  </form>

  <ul>
    <?php foreach ($tasks as $task): ?>
      <li class="<?= $task['completed'] ? 'done' : '' ?>">
        <?= htmlspecialchars($task['description']) ?>
        <?= $task['completed'] ? ' (Done)' : '' ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <a href="dashboard.php" class="back">← Back to Dashboard</a>
</body>
</html>

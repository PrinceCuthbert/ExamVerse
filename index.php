<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome to MyPortal</title>
  <style>
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f9fafb;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .landing {
    background: white;
    padding: 3rem 4rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 400px;
    width: 100%;
  }

  h1 {
    margin-bottom: 1rem;
    font-size: 2rem;
    color: #4c51bf; /* violet */
  }

  p {
    margin-bottom: 2rem;
    font-weight: 500;
    color: #555;
  }

  .btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    margin: 0 0.5rem;
    transition: background 0.3s ease;
    border: 2px solid transparent;
  }

  .btn.primary {
    background: linear-gradient(90deg, #667eea, #764ba2);
    color: white;
    border-color: #667eea;
  }

  .btn.primary:hover {
    background: linear-gradient(90deg, #5a67d8, #6b46c1);
  }

  .btn.secondary {
    background: #eee;
    color: #4c51bf;
    border-color: #ccc;
  }

  .btn.secondary:hover {
    background: #ddd;
  }
</style>

</head>
<body>
  <div class="landing">
    <h1>Welcome to MyPortal</h1>
    <p>Your gateway to exams, resources, and more.</p>
    <a href="login.php" class="btn primary">Login</a>
    <a href="signup.php" class="btn secondary">Sign Up</a>
  </div>
</body>
</html>

<?php
// Fix: Check if session is already started before calling session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];

    $updateStmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $name, $email, $userId);
    $updateStmt->execute();

    $_SESSION['user_name'] = $name;

    header("Location: profile.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Profile</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .profile-container {
      background: white;
      padding: 40px;
      max-width: 500px;
      width: 100%;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .profile-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }

    h1 {
      color: #333;
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .profile-subtitle {
      color: #666;
      font-size: 16px;
    }

    .success-message {
      background: #e6ffe6;
      color: #00b894;
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 25px;
      border-left: 4px solid #00b894;
      font-weight: 600;
      text-align: center;
      animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
      font-size: 14px;
    }

    input[type="text"], 
    input[type="email"] {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #fafbfc;
    }

    input[type="text"]:focus, 
    input[type="email"]:focus {
      outline: none;
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .button-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 30px;
    }

    .btn-primary {
      padding: 14px 20px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 12px 20px;
      text-decoration: none;
      color: #667eea;
      font-weight: 600;
      border: 2px solid #667eea;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .back-link:hover {
      background: #667eea;
      color: white;
      transform: translateY(-1px);
    }

    .back-arrow {
      margin-right: 8px;
      font-size: 16px;
    }

    .user-info {
      background: #f8f9ff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 25px;
      border-left: 4px solid #667eea;
    }

    .user-info-title {
      font-size: 14px;
      color: #666;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .user-info-value {
      font-size: 16px;
      color: #333;
      font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
      body {
        padding: 15px;
      }

      .profile-container {
        padding: 30px 25px;
      }

      h1 {
        font-size: 24px;
      }

      .button-group {
        gap: 12px;
      }

      .btn-primary,
      .back-link {
        padding: 12px 16px;
        font-size: 14px;
      }
    }

    @media (max-width: 400px) {
      .profile-container {
        padding: 25px 20px;
      }

      h1 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>
  <div class="profile-container">
    <div class="profile-header">
      <h1>Your Profile</h1>
      <p class="profile-subtitle">Update your personal information</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
      <div class="success-message">
        ✅ Profile updated successfully!
      </div>
    <?php endif; ?>

    <div class="user-info">
      <div class="user-info-title">Current User ID</div>
      <div class="user-info-value">#<?= $userId ?></div>
    </div>

    <form method="POST" action="profile.php">
      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
      </div>

      <div class="button-group">
        <button type="submit" class="btn-primary">Update Profile</button>
        <a href="dashboard.php" class="back-link">
          <span class="back-arrow">←</span>
          Back to Dashboard
        </a>
      </div>
    </form>
  </div>
</body>
</html>
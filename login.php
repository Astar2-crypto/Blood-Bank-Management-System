<?php
// login.php
require_once 'config.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT id, full_name, role, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password == $user['password']) {
    
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];

        // Route based on role (Objective 9)
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_profile.php");
        }
        exit;
    } else {
        $message = "<div class='alert alert-error'>Invalid email or password.</div>";
    }
}

require_once 'header.php';
?>
    <title>System Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="container">
    <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 10px;">&larr; Back to Home</a>
    <h2 style="margin-top: 0;">Blood Bank Login</h2>
    <?php echo $message; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        <p style="text-align: center; margin-top: 15px;">
            <a href="reset_password.php">Forgot Password?</a> | 
            <a href="register.php">Register as Donor</a>
        </p>
    </form>
</div>
<?php
require_once 'footer.php';
?>
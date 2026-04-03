<?php
require_once 'config.php';
$step = 1;
$message = '';

if (isset($_POST['verify_email'])) {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT security_question FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $step = 2;
        $_SESSION['reset_email'] = $email;
        $question = $user['security_question'];
    } else {
        $message = "<div class='alert alert-error'>Email not found.</div>";
    }
}

if (isset($_POST['reset_pwd'])) {
    $answer = strtolower(trim($_POST['security_answer']));
    $new_password =$_POST['new_password'];
    $email = $_SESSION['reset_email'];

    $stmt = $pdo->prepare("SELECT security_answer FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (strtolower(trim($answer)) === $user['security_answer']) {
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->execute([$new_password, $email]);
        $message = "<div class='alert alert-success'>Password reset successful. You can now login.</div>";
        $step = 1;
        session_destroy();
    } else {
        $message = "<div class='alert alert-error'>Incorrect security answer.</div>";
        $step = 1;
    }
}

require_once 'header.php'; 
?>
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>
    <?php echo $message; ?>

    <?php if ($step == 1): ?>
    <form method="POST">
        <div class="form-group">
            <label>Enter your registered email address</label>
            <input type="email" name="email" required>
        </div>
        <button type="submit" name="verify_email">Next</button>
    </form>
    <?php endif; ?>

    <?php if ($step == 2): ?>
    <form method="POST">
        <div class="form-group">
            <label>Security Question:</label>
            <p><strong><?php echo htmlspecialchars($question); ?></strong></p>
        </div>
        <div class="form-group">
            <label>Your Answer</label>
            <input type="text" name="security_answer" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <button type="submit" name="reset_pwd">Reset Password</button>
    </form>
    <?php endif; ?>
</div>
<?php
require_once 'footer.php';
?>

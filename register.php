<?php
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $bg = $_POST['blood_group'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $sec_q = $_POST['security_question'];
    
    $sec_a = (strtolower(trim($_POST['security_answer'])));

    $is_eligible = ($age >= 18 && $age <= 65 && $weight > 50) ? 1 : 0;

    if ($is_eligible == 0) {
        $message = "<div class='alert alert-error'>Notice: You do not meet the minimum medical criteria (Age 18-65, Weight > 50kg) to donate, but your profile has been created.</div>";
    } else {
        $message = "<div class='alert alert-success'>Registration successful! You are eligible to donate,login to proceed to your profile . Thank you for registering!</div>";
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, blood_group, age, weight, is_eligible, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $bg, $age, $weight, $is_eligible, $sec_q, $sec_a]);
    } catch(PDOException $e) {
        $message = "<div class='alert alert-error'>Email already exists or database error.</div>";
    }
}
require_once 'header.php';
?>
    <title>Donor Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="container">
    <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 10px;">&larr; Back to Home</a>
    <h2 style="margin-top: 0;">Donor Registration</h2>
    <?php echo $message; ?>
    <form method="POST" action="">
        <div class="form-group"><label>Full Name</label><input type="text" name="full_name" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <div class="form-group">
            <label>Blood Group</label>
            <select name="blood_group" required>
                <option value="A+">A+</option><option value="A-">A-</option>
                <option value="B+">B+</option><option value="B-">B-</option>
                <option value="O+">O+</option><option value="O-">O-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option>
            </select>
        </div>
    <div class="form-group"><label>Age</label><input type="number" name="age" min="1" required></div>
        <div class="form-group"><label>Weight (kg)</label><input type="number" name="weight" min="0" step="0.1" required></div>
        
        <div class="form-group">
            <label>Security Question</label>
            <select name="security_question" required>
                <option value="What is your Nickname?">What is your Nickname?</option>
                <option value="What is the name of your pet?">What is the name of your pet?</option>
                <option value="What is your favorite food?">What is your favorite food?</option>
                <option value="What is the name of your best friend?">What is the name of your best friend?</option>
                <option value="Who is your Favorite musician?">Who is your Favorite musician?</option>
            </select>
        </div>
        <div class="form-group"><label>Security Answer</label><input type="text" name="security_answer" required></div>
        <button type="submit">Register</button>
    </form>
</div>
<?php
require_once 'footer.php';
?>

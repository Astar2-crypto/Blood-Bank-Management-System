<?php
require_once 'config.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital = $_POST['hospital_name'];
    $bg = $_POST['blood_group'];
    $units = $_POST['units'];

    $stmt = $pdo->prepare("INSERT INTO hospital_requests (hospital_name, blood_group, units_requested) VALUES (?, ?, ?)");
    if ($stmt->execute([$hospital, $bg, $units])) {
        $message = "<div class='alert alert-success'>Request submitted successfully. Pending admin approval.</div>";
    } else {
        $message = "<div class='alert alert-error'>Failed to submit request.</div>";
    }
}

require_once 'header.php'; 
?>
    <title>Hospital Blood Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="container">
    <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 10px;">&larr; Back to Home</a>
    <h2 style="margin-top: 0;">Hospital Blood Request Portal</h2>
    <?php echo $message; ?>
    <form method="POST">
        <div class="form-group"><label>Hospital Name</label><input type="text" name="hospital_name" required></div>
        <div class="form-group">
            <label>Requested Blood Group</label>
            <select name="blood_group" required>
                <option value="A+">A+</option><option value="A-">A-</option>
                <option value="B+">B+</option><option value="B-">B-</option>
                <option value="O+">O+</option><option value="O-">O-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option>
            </select>
        </div>
        <div class="form-group"><label>Units Required (ml/bags)</label><input type="number" name="units" required></div>
        <button type="submit">Submit Request</button>
    </form>
</div>
<?php
require_once 'footer.php';
?>

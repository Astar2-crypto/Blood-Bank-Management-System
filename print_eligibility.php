<?php
// print_eligibility.php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['is_eligible'] != 1) {
    die("You are not currently eligible to print this form.");
}

$current_date = date("F j, Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pre-Donation Eligibility Report</title>
    <style>
        /* This CSS ensures it looks perfect on an A4 piece of paper or PDF */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        h2 { color: #d9534f; text-align: center; margin-bottom: 5px; }
        h3 { text-align: center; text-decoration: underline; margin-top: 0; color: #555; }
        .date { text-align: right; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f9f9f9; width: 40%; }
        .instructions { background-color: #fdf2f2; border-left: 4px solid #d9534f; padding: 15px; margin-top: 30px; }
        .signature-block { margin-top: 50px; display: flex; justify-content: space-between; }
        
        /* Hides the print button on the actual printed paper/PDF */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #5cb85c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            🖨️ Print or Save as PDF
        </button>
        <a href="user_profile.php" style="margin-left: 15px; color: #666; text-decoration: none;">&larr; Back to Profile</a>
    </div>

    <h2>BloodBank Management System</h2>
    <h3>Pre-Donation Eligibility Report</h3>
    
    <div class="date">Date: <?php echo $current_date; ?></div>
    
    <h4>1. Personal Details</h4>
    <table>
        <tr><th>Full Name:</th><td><?php echo htmlspecialchars($user['full_name']); ?></td></tr>
        <tr><th>Blood Group:</th><td><strong><?php echo htmlspecialchars($user['blood_group']); ?></strong></td></tr>
        <tr><th>Age:</th><td><?php echo htmlspecialchars($user['age']); ?> years</td></tr>
        <tr><th>Weight:</th><td><?php echo htmlspecialchars($user['weight']); ?> kg</td></tr>
    </table>

    <h4>2. Medical Questionnaire Answers</h4>
    <table>
        <tr><th>Under long-term medication?</th><td><?php echo htmlspecialchars($user['long_term_meds']); ?></td></tr>
        <tr><th>Blood infection in the last 6 months?</th><td><?php echo htmlspecialchars($user['blood_infection']); ?></td></tr>
        <tr><th>Have any tattoos?</th><td><?php echo htmlspecialchars($user['tattoos']); ?></td></tr>
        <tr><th>Major surgery in the past 3 months?</th><td><?php echo htmlspecialchars($user['major_surgery']); ?></td></tr>
    </table>

    <div class="instructions">
        <h4 style="margin-top: 0; color: #d9534f;">Next Steps & Final Verification</h4>
        <p>Congratulations, your online pre-screening shows you are eligible to donate! Please save this document as a PDF or print it, and visit your nearest donation centre within <strong>5 working days</strong>.</p>
        <p>For your safety and the safety of the recipient, a final physical test will be conducted at the centre before donation. This will check your:</p>
        <ul style="margin-bottom: 0;">
            <li>Haemoglobin levels</li>
            <li>Blood pressure & Pulse rate</li>
            <li>Current weight confirmation</li>
            <li>HIV / Infectious disease status</li>
        </ul>
    </div>
    
    <div class="signature-block">
        <div><strong>Donor Signature:</strong> ___________________________</div>
        <div><strong>Date:</strong> ___________________</div>
    </div>

</body>
</html>
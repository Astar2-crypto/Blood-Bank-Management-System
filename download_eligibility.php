<?php
// download_eligibility.php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Security: Only allow download if they are actually eligible
if ($user['is_eligible'] != 1) {
    die("You are not currently eligible to download this form.");
}

// Generate the current date
$current_date = date("F j, Y");

// --- SET HEADERS TO FORCE WORD DOCUMENT DOWNLOAD ---
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Donor_Eligibility_Form.doc");
header("Pragma: no-cache");
header("Expires: 0");

// --- BEGIN DOCUMENT CONTENT (HTML structured for Word) ---
echo "
<html>
<meta charset='UTF-8'>
<body style='font-family: Arial, sans-serif;'>
    <h2 style='text-align: center; color: #d9534f;'>BloodBank Management System</h2>
    <h3 style='text-align: center; text-decoration: underline;'>Pre-Donation Eligibility Report</h3>
    
    <p style='text-align: right;'><strong>Date:</strong> $current_date</p>
    
    <h4>1. Personal Details</h4>
    <table border='1' cellpadding='5' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
        <tr><td width='30%'><strong>Full Name:</strong></td><td>{$user['full_name']}</td></tr>
        <tr><td><strong>Blood Group:</strong></td><td>{$user['blood_group']}</td></tr>
        <tr><td><strong>Age:</strong></td><td>{$user['age']} years</td></tr>
        <tr><td><strong>Weight:</strong></td><td>{$user['weight']} kg</td></tr>
    </table>

    <h4>2. Medical Questionnaire Answers</h4>
    <table border='1' cellpadding='5' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
        <tr><td width='70%'>Under long-term medication?</td><td><strong>{$user['long_term_meds']}</strong></td></tr>
        <tr><td>Blood infection in the last 6 months?</td><td><strong>{$user['blood_infection']}</strong></td></tr>
        <tr><td>Have any tattoos?</td><td><strong>{$user['tattoos']}</strong></td></tr>
        <tr><td>Major surgery in the past 3 months?</td><td><strong>{$user['major_surgery']}</strong></td></tr>
    </table>

    <hr style='margin-top: 30px;'>

    <h4 style='color: #d9534f;'>Next Steps & Final Verification</h4>
    <p>Congratulations, your online pre-screening shows you are eligible to donate! Please print this document or save it on your phone and visit your nearest donation centre within <strong>5 working days</strong>.</p>
    
    <p><strong>Please Note:</strong> For your safety and the safety of the recipient, a final physical test will be conducted at the donation centre before the actual donation process begins. This quick screening will check your:</p>
    <ul>
        <li>Haemoglobin levels</li>
        <li>Blood pressure</li>
        <li>Current weight</li>
        <li>Pulse rate</li>
        <li>HIV / Infectious disease status</li>
    </ul>
    
    <p style='margin-top: 40px;'><strong>Donor Signature:</strong> ___________________________ &nbsp;&nbsp;&nbsp; <strong>Date:</strong> ___________________</p>
</body>
</html>
";
?>
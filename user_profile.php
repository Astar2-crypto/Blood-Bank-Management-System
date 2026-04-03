<?php
// user_profile.php
require_once 'config.php';

// Security Check: Must be logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    
    // Capture the new medical questions
    $meds = $_POST['long_term_meds'];
    $infection = $_POST['blood_infection'];
    $tattoos = $_POST['tattoos'];
    $surgery = $_POST['major_surgery'];
    
    // Recalculate eligibility: Must meet Age/Weight AND answer 'No' to all medical risks
    if ($age >= 18 && $age <= 65 && $weight > 50 && $meds === 'No' && $infection === 'No' && $tattoos === 'No' && $surgery === 'No') {
        $is_eligible = 1;
    } else {
        $is_eligible = 0;
    }

    $stmt = $pdo->prepare("UPDATE users SET age = ?, weight = ?, long_term_meds = ?, blood_infection = ?, tattoos = ?, major_surgery = ?, is_eligible = ? WHERE id = ?");
    if ($stmt->execute([$age, $weight, $meds, $infection, $tattoos, $surgery, $is_eligible, $user_id])) {
        $message = "<div class='alert alert-success'>Medical profile updated successfully. Your eligibility status has been recalculated.</div>";
    }
}

// Fetch current user details from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Helper function to easily select the right dropdown option
function isSelected($db_value, $option_value) {
    return ($db_value === $option_value) ? 'selected' : '';
}

require_once 'header.php';
?>

<div class="container">
    <h2>My Donor Profile</h2>
    <p>Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>!</p>
    
    <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3>Health Eligibility Report</h3>
        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($user['blood_group']); ?></p>
    <p><strong>Status:</strong>
            <?php if ($user['is_eligible']): ?>
                <span style="color: green; font-weight: bold;">Eligible to Donate</span>
                <br><br><em>Thank you for being a hero! Your willingness to donate saves lives.</em>
                
                <div style="margin-top: 15px;">
                    <a href="print_eligibility.php" target="_blank" style="background-color: #5cb85c; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
    &#128196; Generate PDF / Print Form
                    </a>
                </div>

            <?php else: ?>
                <span style="color: red; font-weight: bold;">Not Eligible Currently</span>
                <br><small>(Requires Age 18-65, Weight > 50kg, and no recent medical risk factors.)</small>
            <?php endif; ?>
        </p>
    </div>

    <?php echo $message; ?>

    <form method="POST">
        <h3>Update Health Metrics & History</h3>
        
        <div class="form-group">
            <label>Current Age</label>
            <input type="number" name="age" min="1" value="<?php echo htmlspecialchars($user['age']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Current Weight (kg)</label>
            <input type="number" name="weight" step="0.1" min="1" value="<?php echo htmlspecialchars($user['weight']); ?>" required>
        </div>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">
        <h4>Medical Questionnaire</h4>

        <div class="form-group">
            <label>Are you under any long-term medication?</label>
            <select name="long_term_meds" required>
                <option value="No" <?php echo isSelected($user['long_term_meds'], 'No'); ?>>No</option>
                <option value="Yes" <?php echo isSelected($user['long_term_meds'], 'Yes'); ?>>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label>Have you had any blood infection in the last 6 months?</label>
            <select name="blood_infection" required>
                <option value="No" <?php echo isSelected($user['blood_infection'], 'No'); ?>>No</option>
                <option value="Yes" <?php echo isSelected($user['blood_infection'], 'Yes'); ?>>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label>Do you have any tattoos?</label>
            <select name="tattoos" required>
                <option value="No" <?php echo isSelected($user['tattoos'], 'No'); ?>>No</option>
                <option value="Yes" <?php echo isSelected($user['tattoos'], 'Yes'); ?>>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label>Have you had any major surgery in the past 3 months?</label>
            <select name="major_surgery" required>
                <option value="No" <?php echo isSelected($user['major_surgery'], 'No'); ?>>No</option>
                <option value="Yes" <?php echo isSelected($user['major_surgery'], 'Yes'); ?>>Yes</option>
            </select>
        </div>

        <button type="submit" name="update_profile" style="margin-top: 10px;">Update Profile</button>
    </form>
</div>

<?php
require_once 'footer.php';
?>
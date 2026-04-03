<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'reject') {
        
        $stmt = $pdo->prepare("UPDATE hospital_requests SET status = 'rejected' WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = "<div class='alert alert-success'>Request rejected successfully.</div>";
        }
    } elseif ($action == 'approve') {
        
        try {
            
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT blood_group, units_requested FROM hospital_requests WHERE id = ? AND status = 'pending'");
            $stmt->execute([$id]);
            $req = $stmt->fetch();

            if ($req) {
                $bg = $req['blood_group'];
                $units_needed = $req['units_requested'];
                $check_stmt = $pdo->prepare("SELECT SUM(quantity) FROM inventory WHERE blood_group = ? AND expiry_date >= CURDATE()");
                $check_stmt->execute([$bg]);
                $total_available = $check_stmt->fetchColumn();

                if ($total_available >= $units_needed) {
                    $batch_stmt = $pdo->prepare("SELECT id, quantity FROM inventory WHERE blood_group = ? AND expiry_date >= CURDATE() AND quantity > 0 ORDER BY expiry_date ASC");
                    $batch_stmt->execute([$bg]);
                    $batches = $batch_stmt->fetchAll();

                    foreach ($batches as $batch) {
                        if ($units_needed <= 0) break; // Stop if we've fulfilled the request

                        if ($batch['quantity'] >= $units_needed) 
                            $new_qty = $batch['quantity'] - $units_needed;
                            $update_stmt = $pdo->prepare("UPDATE inventory SET quantity = ? WHERE id = ?");
                            $update_stmt->execute([$new_qty, $batch['id']]);
                            $units_needed = 0; 
                        } else {
                           
                            $units_needed -= $batch['quantity'];
                            $update_stmt = $pdo->prepare("UPDATE inventory SET quantity = 0 WHERE id = ?");
                            $update_stmt->execute([$batch['id']]);
                        }
                    }

                    $approve_stmt = $pdo->prepare("UPDATE hospital_requests SET status = 'approved' WHERE id = ?");
                    $approve_stmt->execute([$id]);
                    $pdo->commit();
                    $message = "<div class='alert alert-success'>Request approved! The required units have been deducted from the oldest safe inventory batches.</div>";
                } else {
                    $pdo->rollBack();
                    $message = "<div class='alert alert-error'>Approval Failed: Not enough safe stock available to fulfill this request.</div>";
                }
            } else {
                $pdo->rollBack();
                $message = "<div class='alert alert-error'>Invalid request or already processed.</div>";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "<div class='alert alert-error'>System Error: Could not process the approval.</div>";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM hospital_requests ORDER BY request_date DESC");
$requests = $stmt->fetchAll();

require_once 'header.php';
?>

<div class="container" style="max-width: 900px;">
    <h2>Admin Dashboard - Hospital Requests</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
    <?php echo $message; ?>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Hospital Name</th>
            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Blood Group</th>
            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Units Needed</th>
            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Status</th>
            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Actions</th>
        </tr>
        <?php foreach ($requests as $req): ?>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['hospital_name']); ?></td>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong><?php echo htmlspecialchars($req['blood_group']); ?></strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['units_requested']); ?></td>
            <td style="padding: 10px; border: 1px solid #ddd;">
                <?php 
                    if ($req['status'] == 'approved') {
                        echo "<span style='color: green; font-weight: bold;'>Approved</span>";
                    } elseif ($req['status'] == 'rejected') {
                        echo "<span style='color: red; font-weight: bold;'>Rejected</span>";
                    } else {
                        echo "<span style='color: orange; font-weight: bold;'>Pending</span>";
                    }
                ?>
            </td>
            <td style="padding: 10px; border: 1px solid #ddd;">
                <?php if ($req['status'] == 'pending'): ?>
                    <a href="?action=approve&id=<?php echo $req['id']; ?>" style="background-color: #5cb85c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em;">Approve</a>
                    <a href="?action=reject&id=<?php echo $req['id']; ?>" style="background-color: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em; margin-left: 5px;">Reject</a>
                <?php else: ?>
                    <em>Processed</em>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
require_once 'footer.php';
?>

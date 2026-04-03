<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle adding new blood units to inventory
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_inventory'])) {
    $bg = $_POST['blood_group'];
    $qty = $_POST['quantity'];
    $expiry = $_POST['expiry_date'];

    $stmt = $pdo->prepare("INSERT INTO inventory (blood_group, quantity, expiry_date) VALUES (?, ?, ?)");
    if ($stmt->execute([$bg, $qty, $expiry])) {
        $message = "<div class='alert alert-success'>$qty units of $bg added to inventory successfully.</div>";
    } else {
        $message = "<div class='alert alert-error'>Failed to add inventory.</div>";
    }
}

$summary_stmt = $pdo->query("
    SELECT blood_group, SUM(quantity) as total_units 
    FROM inventory 
    WHERE expiry_date >= CURDATE() 
    GROUP BY blood_group
");
$summary = $summary_stmt->fetchAll();

$details_stmt = $pdo->query("SELECT * FROM inventory ORDER BY expiry_date ASC");
$details = $details_stmt->fetchAll();

require_once 'header.php';
?>
    <title>Inventory Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .grid { display: flex; gap: 20px; }
        .col { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .expired { background-color: #ffcccc; color: #a94442; } /* Highlights expired rows in red */
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #d9534f; font-weight: bold; }
    </style>
</head>
<body style="background-color: #f4f7f6;">
<div class="dashboard-container">
    <h2>Blood Inventory Management</h2>
    
    <div class="nav-links">
        <a href="admin_dashboard.php">&larr; Back to Admin Dashboard</a>
        <a href="logout.php" style="float:right;">Logout</a>
    </div>

    <?php echo $message; ?>

    <div class="grid">
        <div class="col">
            <h3>Add New Blood Units</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" required>
                        <option value="A+">A+</option><option value="A-">A-</option>
                        <option value="B+">B+</option><option value="B-">B-</option>
                        <option value="O+">O+</option><option value="O-">O-</option>
                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity (Units/Bags)</label>
                    <input type="number" name="quantity" min="1" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" required>
                </div>
                <button type="submit" name="add_inventory">Add to Stock</button>
            </form>
        </div>

        <div class="col">
            <h3>Current Safe Stock (Unexpired)</h3>
            <table>
                <tr>
                    <th>Blood Group</th>
                    <th>Total Units Available</th>
                </tr>
                <?php if(count($summary) > 0): ?>
                    <?php foreach ($summary as $row): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['blood_group']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['total_units']); ?> Units</td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">No safe stock available.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="col" style="margin-top: 20px;">
        <h3>Detailed Inventory Log & Expiry Tracking</h3>
        <table>
            <tr>
                <th>Batch ID</th>
                <th>Blood Group</th>
                <th>Quantity</th>
                <th>Expiry Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($details as $item): 
                $is_expired = (strtotime($item['expiry_date']) < strtotime('today'));
            ?>
            <tr class="<?php echo $is_expired ? 'expired' : ''; ?>">
                <td>#<?php echo htmlspecialchars($item['id']); ?></td>
                <td><strong><?php echo htmlspecialchars($item['blood_group']); ?></strong></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars($item['expiry_date']); ?></td>
                <td>
                    <?php echo $is_expired ? '<strong>EXPIRED</strong>' : 'Valid'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php
require_once 'footer.php';
?>

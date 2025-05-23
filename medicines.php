<?php
require_once 'db.php';

// Handle form submission for adding new medicine
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_medicine'])) {
        try {
            $data = [
                'name' => sanitize_input($_POST['name']),
                'manufacturer' => sanitize_input($_POST['manufacturer']),
                'dosage' => sanitize_input($_POST['dosage']),
                'price' => sanitize_input($_POST['price']),
                'expiry_date' => sanitize_input($_POST['expiry_date']),
                'stock_quantity' => sanitize_input($_POST['stock_quantity'])
            ];
            
            insert_record($conn, 'MEDICINE', $data);
            $success_message = "Medicine added successfully!";
        } catch (Exception $e) {
            $error_message = "Error adding medicine: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['restock_medicine'])) {
        try {
            $medicine_id = sanitize_input($_POST['medicine_id']);
            $quantity = sanitize_input($_POST['quantity']);
            $expiry_date = !empty($_POST['new_expiry_date']) ? sanitize_input($_POST['new_expiry_date']) : null;
            
            // Get current stock
            $medicine = get_record_by_id($conn, 'MEDICINE', 'medicine_id', $medicine_id);
            $new_stock = $medicine['stock_quantity'] + $quantity;
            
            // Update stock and expiry if provided
            $data = ['stock_quantity' => $new_stock];
            if ($expiry_date) {
                $data['expiry_date'] = $expiry_date;
            }
            
            update_record($conn, 'MEDICINE', $data, 'medicine_id', $medicine_id);
            $success_message = "Stock updated successfully!";
        } catch (Exception $e) {
            $error_message = "Error updating stock: " . $e->getMessage();
        }
    }
}

// Get all medicines
$medicines = get_multiple_records($conn, "SELECT * FROM MEDICINE ORDER BY name");

// Get low stock medicines (threshold of 50)
$low_stock = get_multiple_records($conn, "SELECT * FROM MEDICINE WHERE stock_quantity <= 50 ORDER BY stock_quantity ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hemas Hospital - Medicine Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
        }

        .logo {
            text-align: center;
            padding: 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin: 5px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.8;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title i {
            margin-right: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table th {
            background: rgba(255, 255, 255, 0.1);
            font-weight: bold;
        }

        .table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-ok {
            background: #4CAF50;
            color: white;
        }

        .status-warning {
            background: #FF9800;
            color: white;
        }

        .status-danger {
            background: #F44336;
            color: white;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #45a049, #4CAF50);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-info {
            background: #2196F3;
            color: white;
        }

        .btn-info:hover {
            background: #0b7dda;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.5);
            color: #4CAF50;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid rgba(244, 67, 54, 0.5);
            color: #f44336;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 80%;
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            color: white;
            margin: 0;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .close:hover {
            opacity: 0.7;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control option {
            background: #667eea;
            color: white;
        }

        .amount-input {
            display: flex;
            align-items: center;
        }

        .amount-input span {
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .amount-input input {
            border-radius: 0 8px 8px 0;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-hospital"></i>
                Hemas Hospital
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="patients.php" class="nav-link">
                        <i class="fas fa-user-injured"></i>
                        Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a href="doctors.php" class="nav-link">
                        <i class="fas fa-user-md"></i>
                        Doctors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="staff.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a href="treatments.php" class="nav-link">
                        <i class="fas fa-stethoscope"></i>
                        Treatments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="medicines.php" class="nav-link active">
                        <i class="fas fa-pills"></i>
                        Medicines
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rooms.php" class="nav-link">
                        <i class="fas fa-bed"></i>
                        Rooms
                    </a>
                </li>
                <li class="nav-item">
                    <a href="bills.php" class="nav-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Billing
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div>
                    <h1><i class="fas fa-pills"></i> Medicine Management</h1>
                    <p>Manage hospital medicine inventory and stock</p>
                </div>
                <button class="btn btn-primary" onclick="openAddMedicineModal()">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($success_message): ?>
                <div class="success-message" style="display: block;">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="error-message" style="display: block;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Low Stock Alert -->
            <?php if (!empty($low_stock)): ?>
                <div class="content-card" style="border-left: 5px solid #FF9800;">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Low Stock Alert
                    </h3>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Manufacturer</th>
                                    <th>Current Stock</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($low_stock as $medicine): 
                                    $statusClass = '';
                                    if ($medicine['stock_quantity'] <= 10) {
                                        $statusClass = 'status-danger';
                                    } elseif ($medicine['stock_quantity'] <= 30) {
                                        $statusClass = 'status-warning';
                                    } else {
                                        $statusClass = 'status-ok';
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $medicine['name']; ?></td>
                                        <td><?php echo $medicine['manufacturer']; ?></td>
                                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $medicine['stock_quantity']; ?></span></td>
                                        <td>Rs. <?php echo number_format($medicine['price'], 2); ?></td>
                                        <td>
                                            <button class="btn btn-info" style="padding: 5px 10px; font-size: 12px;" 
                                                    onclick="openRestockModal(<?php echo $medicine['medicine_id']; ?>, '<?php echo $medicine['name']; ?>')">
                                                <i class="fas fa-boxes"></i> Restock
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- All Medicines Table -->
            <div class="content-card">
                <h3 class="card-title">
                    <i class="fas fa-list"></i>
                    Medicine Inventory
                </h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Manufacturer</th>
                                <th>Dosage</th>
                                <th>Price</th>
                                <th>Expiry Date</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medicines as $medicine): 
                                $statusClass = '';
                                if ($medicine['stock_quantity'] <= 10) {
                                    $statusClass = 'status-danger';
                                } elseif ($medicine['stock_quantity'] <= 30) {
                                    $statusClass = 'status-warning';
                                } else {
                                    $statusClass = 'status-ok';
                                }
                                
                                // Check if medicine is expired
                                $expired = false;
                                if (!empty($medicine['expiry_date'])) {
                                    $expiryDate = new DateTime($medicine['expiry_date']);
                                    $today = new DateTime();
                                    $expired = $expiryDate < $today;
                                }
                            ?>
                                <tr <?php if ($expired) echo 'style="background: rgba(244, 67, 54, 0.1);"'; ?>>
                                    <td><?php echo $medicine['medicine_id']; ?></td>
                                    <td><?php echo $medicine['name']; ?></td>
                                    <td><?php echo $medicine['manufacturer']; ?></td>
                                    <td><?php echo $medicine['dosage']; ?></td>
                                    <td>Rs. <?php echo number_format($medicine['price'], 2); ?></td>
                                    <td <?php if ($expired) echo 'style="color: #f44336; font-weight: bold;"'; ?>>
                                        <?php 
                                        if (!empty($medicine['expiry_date'])) {
                                            echo $medicine['expiry_date']->format('Y-m-d');
                                            if ($expired) echo ' (Expired)';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $medicine['stock_quantity']; ?></span></td>
                                    <td>
                                        <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;"
                                                onclick="openEditModal(<?php echo $medicine['medicine_id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-info" style="padding: 5px 10px; font-size: 12px;"
                                                onclick="openRestockModal(<?php echo $medicine['medicine_id']; ?>, '<?php echo $medicine['name']; ?>')">
                                            <i class="fas fa-boxes"></i> Restock
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medicine Modal -->
    <div id="addMedicineModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-pills"></i> Add New Medicine</h2>
                <span class="close" onclick="closeAddMedicineModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Medicine Name</label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="Enter medicine name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Manufacturer</label>
                        <input type="text" name="manufacturer" class="form-control" 
                               placeholder="Enter manufacturer name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dosage</label>
                        <input type="text" name="dosage" class="form-control" 
                               placeholder="Enter dosage (e.g., 500mg, 5ml)" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (Rs.)</label>
                        <div class="amount-input">
                            <span>Rs.</span>
                            <input type="number" name="price" class="form-control" 
                                   placeholder="Enter price" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Initial Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" 
                               placeholder="Enter initial stock quantity" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="closeAddMedicineModal()">
                            Cancel
                        </button>
                        <button type="submit" name="add_medicine" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Medicine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Restock Modal -->
    <div id="restockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-boxes"></i> Restock Medicine</h2>
                <span class="close" onclick="closeRestockModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="restockForm" method="POST" action="">
                    <input type="hidden" id="restock_medicine_id" name="medicine_id">
                    <div class="form-group">
                        <label class="form-label">Medicine</label>
                        <input type="text" id="restock_medicine_name" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity to Add</label>
                        <input type="number" name="quantity" class="form-control" 
                               placeholder="Enter quantity to add" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Expiry Date (if applicable)</label>
                        <input type="date" name="new_expiry_date" class="form-control">
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="closeRestockModal()">
                            Cancel
                        </button>
                        <button type="submit" name="restock_medicine" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'block';
        }

        function closeAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'none';
        }

        function openRestockModal(medicineId, medicineName) {
            document.getElementById('restock_medicine_id').value = medicineId;
            document.getElementById('restock_medicine_name').value = medicineName;
            document.getElementById('restockModal').style.display = 'block';
        }

        function closeRestockModal() {
            document.getElementById('restockModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addMedicineModal');
            const restockModal = document.getElementById('restockModal');
            
            if (event.target == addModal) {
                addModal.style.display = 'none';
            }
            if (event.target == restockModal) {
                restockModal.style.display = 'none';
            }
        }

        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const successMsg = document.querySelector('.success-message');
            const errorMsg = document.querySelector('.error-message');
            if (successMsg) successMsg.style.display = 'none';
            if (errorMsg) errorMsg.style.display = 'none';
        }, 5000);
    </script>
</body>
</html>
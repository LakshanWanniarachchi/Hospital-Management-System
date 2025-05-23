<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hemas Hospital - Dashboard</title>
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
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.8;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            opacity: 0.8;
            font-size: 14px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
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

        .status-paid {
            background: #4CAF50;
            color: white;
        }

        .status-pending {
            background: #FF9800;
            color: white;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .action-btn i {
            margin-right: 8px;
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

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            padding: 12px 24px;
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

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }
    </style>
</head>
<body>
    <?php
    // Include the updated database functions
    $serverName = "LAKSHAN\MSSQLSERVER01";
    $connectionOptions = array(
        "Database" => "HemasHospitalDB",
        "Uid" => "",
        "PWD" => ""
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Function to sanitize input data
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Function to execute query safely
    function execute_query($conn, $query, $params = array()) {
        $stmt = sqlsrv_query($conn, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        return $stmt;
    }

    // Function to assign treatment using stored procedure
    function assign_treatment($conn, $doctorId, $patientId, $diagnosis, $notes) {
        $query = "{CALL AssignTreatment(?, ?, ?, ?)}";
        $params = array($doctorId, $patientId, $diagnosis, $notes);
        return execute_query($conn, $query, $params);
    }

    // Function to get single record
    function get_single_record($conn, $query, $params = array()) {
        $stmt = execute_query($conn, $query, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);
        return $row;
    }

    // Function to get multiple records
    function get_multiple_records($conn, $query, $params = array()) {
        $stmt = execute_query($conn, $query, $params);
        $records = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $records[] = $row;
        }
        sqlsrv_free_stmt($stmt);
        return $records;
    }

    // Handle treatment assignment form submission
    $success_message = '';
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_treatment'])) {
        try {
            $doctor_id = sanitize_input($_POST['doctor_id']);
            $patient_id = sanitize_input($_POST['patient_id']);
            $diagnosis = sanitize_input($_POST['diagnosis']);
            $notes = sanitize_input($_POST['notes']);

            // Assign treatment using stored procedure
            assign_treatment($conn, $doctor_id, $patient_id, $diagnosis, $notes);
            $success_message = "Treatment assigned successfully!";
        } catch (Exception $e) {
            $error_message = "Error assigning treatment: " . $e->getMessage();
        }
    }

    // Get statistics
    $stats = [];
    
    // Total Patients
    $stats['patients'] = get_single_record($conn, "SELECT COUNT(*) as count FROM PATIENT")['count'];
    
    // Total Doctors  
    $stats['doctors'] = get_single_record($conn, "SELECT COUNT(*) as count FROM DOCTOR")['count'];
    
    // Total Staff
    $stats['staff'] = get_single_record($conn, "SELECT COUNT(*) as count FROM STAFF")['count'];
    
    // Occupied Rooms
    $stats['occupied_rooms'] = get_single_record($conn, "SELECT COUNT(*) as count FROM ROOM_OCCUPANCY WHERE check_out_date IS NULL")['count'];
    
    // Pending Bills
    $stats['pending_bills'] = get_single_record($conn, "SELECT COUNT(*) as count FROM BILL WHERE payment_status = 'Pending'")['count'];
    
    // Today's Treatments
    $stats['today_treatments'] = get_single_record($conn, "SELECT COUNT(*) as count FROM TREATMENT WHERE CAST(treatment_date AS DATE) = CAST(GETDATE() AS DATE)")['count'];

    // Get doctors for dropdown
    $doctors = get_multiple_records($conn, "SELECT e.employee_id, e.name, d.specialization FROM EMPLOYEE e JOIN DOCTOR d ON e.employee_id = d.employee_id ORDER BY e.name");
    
    // Get patients for dropdown
    $patients = get_multiple_records($conn, "SELECT patient_id, name FROM PATIENT ORDER BY name");
    ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-hospital"></i>
                Hemas Hospital
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
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
                    <a href="medicines.php" class="nav-link">
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
                <h1>Hospital Dashboard</h1>
                <p>Welcome to Hemas Hospital Management System</p>
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

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['patients']; ?></div>
                            <div class="stat-label">Total Patients</div>
                        </div>
                        <div class="stat-icon" style="background: #4CAF50;">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['doctors']; ?></div>
                            <div class="stat-label">Doctors</div>
                        </div>
                        <div class="stat-icon" style="background: #2196F3;">
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['staff']; ?></div>
                            <div class="stat-label">Staff Members</div>
                        </div>
                        <div class="stat-icon" style="background: #FF9800;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['occupied_rooms']; ?></div>
                            <div class="stat-label">Occupied Rooms</div>
                        </div>
                        <div class="stat-icon" style="background: #9C27B0;">
                            <i class="fas fa-bed"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['pending_bills']; ?></div>
                            <div class="stat-label">Pending Bills</div>
                        </div>
                        <div class="stat-icon" style="background: #F44336;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number"><?php echo $stats['today_treatments']; ?></div>
                            <div class="stat-label">Today's Treatments</div>
                        </div>
                        <div class="stat-icon" style="background: #00BCD4;">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Treatments -->
                <div class="content-card">
                    <h3 class="card-title">
                        <i class="fas fa-stethoscope"></i>
                        Recent Treatments
                    </h3>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_treatments = get_multiple_records($conn, 
                                    "SELECT TOP 5 
                                        t.treatment_date,
                                        p.name as patient_name,
                                        e.name as doctor_name,
                                        t.diagnosis
                                     FROM TREATMENT t
                                     JOIN PATIENT p ON t.patient_id = p.patient_id
                                     JOIN EMPLOYEE e ON t.doctor_id = e.employee_id
                                     ORDER BY t.treatment_date DESC"
                                );
                                
                                foreach ($recent_treatments as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row['treatment_date']->format('Y-m-d') . "</td>";
                                    echo "<td>" . $row['patient_name'] . "</td>";
                                    echo "<td>" . $row['doctor_name'] . "</td>";
                                    echo "<td>" . $row['diagnosis'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                    <div class="quick-actions">
                        <a href="add_patient.php" class="action-btn">
                            <i class="fas fa-user-plus"></i>
                            Add Patient
                        </a>
                        <button class="action-btn" onclick="openTreatmentModal()">
                            <i class="fas fa-plus-circle"></i>
                            Assign Treatment
                        </button>
                        <a href="room_occupancy.php" class="action-btn">
                            <i class="fas fa-bed"></i>
                            Room Status
                        </a>
                        <a href="prescriptions.php" class="action-btn">
                            <i class="fas fa-prescription-bottle"></i>
                            Prescriptions
                        </a>
                        <a href="generate_bill.php" class="action-btn">
                            <i class="fas fa-file-invoice"></i>
                            Generate Bill
                        </a>
                        <a href="medicine_stock.php" class="action-btn">
                            <i class="fas fa-pills"></i>
                            Medicine Stock
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Bills -->
            <div class="content-card">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Recent Bills
                </h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bill ID</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_bills = get_multiple_records($conn,
                                "SELECT TOP 10 
                                    b.bill_id,
                                    p.name as patient_name,
                                    b.bill_date,
                                    b.amount,
                                    b.payment_status,
                                    b.payment_method
                                 FROM BILL b
                                 JOIN PATIENT p ON b.patient_id = p.patient_id
                                 ORDER BY b.bill_date DESC"
                            );
                            
                            foreach ($recent_bills as $row) {
                                $statusClass = $row['payment_status'] == 'Paid' ? 'status-paid' : 'status-pending';
                                echo "<tr>";
                                echo "<td>#" . $row['bill_id'] . "</td>";
                                echo "<td>" . $row['patient_name'] . "</td>";
                                echo "<td>" . $row['bill_date']->format('Y-m-d') . "</td>";
                                echo "<td>Rs. " . number_format($row['amount'], 2) . "</td>";
                                echo "<td><span class='status-badge $statusClass'>" . $row['payment_status'] . "</span></td>";
                                echo "<td>" . $row['payment_method'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Assignment Modal -->
    <div id="treatmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-stethoscope"></i> Assign Treatment</h2>
                <span class="close" onclick="closeTreatmentModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Select Doctor</label>
                        <select name="doctor_id" class="form-control" required>
                            <option value="">Choose Doctor...</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo $doctor['employee_id']; ?>">
                                    <?php echo $doctor['name'] . ' - ' . $doctor['specialization']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Select Patient</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">Choose Patient...</option>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['patient_id']; ?>">
                                    <?php echo $patient['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis</label>
                        <input type="text" name="diagnosis" class="form-control" 
                               placeholder="Enter diagnosis..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Treatment Notes</label>
                        <textarea name="notes" class="form-control" 
                                  placeholder="Enter treatment notes and instructions..."></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="closeTreatmentModal()">
                            Cancel
                        </button>
                        <button type="submit" name="assign_treatment" class="btn btn-primary">
                            <i class="fas fa-save"></i> Assign Treatment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openTreatmentModal() {
            document.getElementById('treatmentModal').style.display = 'block';
        }

        function closeTreatmentModal() {
            document.getElementById('treatmentModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('treatmentModal');
            if (event.target == modal) {
                modal.style.display = 'none';
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
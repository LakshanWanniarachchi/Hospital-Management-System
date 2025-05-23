<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Hemas Hospital</title>
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
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 8px;
        }

        .search-bar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .patient-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .patient-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .patient-id {
            font-size: 14px;
            opacity: 0.7;
        }

        .patient-info {
            margin: 15px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }

        .info-label {
            opacity: 0.8;
        }

        .info-value {
            font-weight: bold;
        }

        .patient-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-small {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-info {
            background: #2196F3;
            color: white;
        }

        .btn-warning {
            background: #FF9800;
            color: white;
        }

        .btn-success {
            background: #4CAF50;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            color: #333;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .blood-type {
            background: #F44336;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .patients-grid {
                grid-template-columns: 1fr;
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
                    <a href="patients.php" class="nav-link active">
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
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Patient Management</h1>
                <button class="btn btn-primary" onclick="openAddPatientModal()">
                    <i class="fas fa-user-plus"></i>
                    Add New Patient
                </button>
            </div>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" class="search-input" id="searchInput" placeholder="Search patients by name, ID, or contact..." onkeyup="searchPatients()">
                <button class="btn btn-primary" onclick="searchPatients()">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </div>

            <?php
            include 'db.php';
            
            // Handle form submissions
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case 'add_patient':
                            $patient_id = get_next_id($conn, 'PATIENT', 'patient_id');
                            $data = array(
                                'patient_id' => $patient_id,
                                'name' => sanitize_input($_POST['name']),
                                'date_of_birth' => $_POST['date_of_birth'],
                                'contact' => sanitize_input($_POST['contact']),
                                'blood_type' => sanitize_input($_POST['blood_type']),
                                'allergies' => sanitize_input($_POST['allergies'])
                            );
                            
                            if (insert_record($conn, 'PATIENT', $data)) {
                                echo "<script>alert('Patient added successfully!');</script>";
                            }
                            break;
                            
                        case 'update_patient':
                            $patient_id = $_POST['patient_id'];
                            $data = array(
                                'name' => sanitize_input($_POST['name']),
                                'date_of_birth' => $_POST['date_of_birth'],
                                'contact' => sanitize_input($_POST['contact']),
                                'blood_type' => sanitize_input($_POST['blood_type']),
                                'allergies' => sanitize_input($_POST['allergies'])
                            );
                            
                            if (update_record($conn, 'PATIENT', $data, 'patient_id = ?', array($patient_id))) {
                                echo "<script>alert('Patient updated successfully!');</script>";
                            }
                            break;
                    }
                }
            }
            
            // Get all patients
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $query = "SELECT * FROM PATIENT";
            $params = array();
            
            if (!empty($search)) {
                $query .= " WHERE name LIKE ? OR CAST(patient_id AS VARCHAR) LIKE ? OR contact LIKE ?";
                $searchParam = "%$search%";
                $params = array($searchParam, $searchParam, $searchParam);
            }
            
            $query .= " ORDER BY patient_id DESC";
            $patients = get_multiple_records($conn, $query, $params);
            ?>

            <!-- Patients Grid -->
            <div class="patients-grid" id="patientsGrid">
                <?php foreach ($patients as $patient): ?>
                <div class="patient-card">
                    <div class="patient-header">
                        <div>
                            <div class="patient-name"><?php echo htmlspecialchars($patient['name']); ?></div>
                            <div class="patient-id">ID: #<?php echo $patient['patient_id']; ?></div>
                        </div>
                        <div class="blood-type"><?php echo $patient['blood_type']; ?></div>
                    </div>
                    
                    <div class="patient-info">
                        <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value"><?php echo format_date($patient['date_of_birth']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Contact:</span>
                            <span class="info-value"><?php echo $patient['contact']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Allergies:</span>
                            <span class="info-value"><?php echo $patient['allergies'] ?: 'None'; ?></span>
                        </div>
                    </div>
                    
                    <div class="patient-actions">
                        <button class="btn btn-info btn-small" onclick="viewPatientDetails(<?php echo $patient['patient_id']; ?>)">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-warning btn-small" onclick="editPatient(<?php echo htmlspecialchars(json_encode($patient)); ?>)">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-success btn-small" onclick="addTreatment(<?php echo $patient['patient_id']; ?>)">
                            <i class="fas fa-stethoscope"></i>
                            Treatment
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addPatientModal')">&times;</span>
            <h2>Add New Patient</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_patient">
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Blood Type</label>
                    <select name="blood_type" class="form-control" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Allergies</label>
                    <textarea name="allergies" class="form-control" rows="3" placeholder="Enter any known allergies..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Add Patient
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div id="editPatientModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editPatientModal')">&times;</span>
            <h2>Edit Patient</h2>
            <form method="POST" id="editPatientForm">
                <input type="hidden" name="action" value="update_patient">
                <input type="hidden" name="patient_id" id="editPatientId">
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="editPatientName" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="editPatientDob" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact" id="editPatientContact" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Blood Type</label>
                    <select name="blood_type" id="editPatientBloodType" class="form-control" required>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Allergies</label>
                    <textarea name="allergies" id="editPatientAllergies" class="form-control" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Patient
                </button>
            </form>
        </div>
    </div>

    <script>
        function openAddPatientModal() {
            document.getElementById('addPatientModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editPatient(patient) {
            document.getElementById('editPatientId').value = patient.patient_id;
            document.getElementById('editPatientName').value = patient.name;
            document.getElementById('editPatientDob').value = patient.date_of_birth;
            document.getElementById('editPatientContact').value = patient.contact;
            document.getElementById('editPatientBloodType').value = patient.blood_type;
            document.getElementById('editPatientAllergies').value = patient.allergies || '';
            document.getElementById('editPatientModal').style.display = 'block';
        }

        function viewPatientDetails(patientId) {
            window.location.href = 'patient_details.php?id=' + patientId;
        }

        function addTreatment(patientId) {
            window.location.href = 'add_treatment.php?patient_id=' + patientId;
        }

        function searchPatients() {
            const searchTerm = document.getElementById('searchInput').value;
            window.location.href = 'patients.php?search=' + encodeURIComponent(searchTerm);
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
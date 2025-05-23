<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Hospital Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Hospital Management System</h2>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employee" type="button">Employees</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="patient-tab" data-bs-toggle="tab" data-bs-target="#patient" type="button">Patients</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="doctor-tab" data-bs-toggle="tab" data-bs-target="#doctor" type="button">Doctors</button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="myTabContent">
        <!-- Employees -->
        <div class="tab-pane fade show active" id="employee" role="tabpanel">
            <h4>Employee List</h4>
            <table class="table table-bordered">
                <tr><th>ID</th><th>Name</th><th>Role</th></tr>
                <?php
                $stmt = sqlsrv_query($conn, "SELECT * FROM Employees");
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr><td>{$row['EmployeeID']}</td><td>{$row['Name']}</td><td>{$row['Role']}</td></tr>";
                }
                sqlsrv_free_stmt($stmt);
                ?>
            </table>
        </div>

        <!-- Patients -->
        <div class="tab-pane fade" id="patient" role="tabpanel">
            <h4>Patient List</h4>
            <table class="table table-bordered">
                <tr><th>ID</th><th>Name</th><th>Admit Date</th></tr>
                <?php
                $stmt = sqlsrv_query($conn, "SELECT * FROM Patients");
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr><td>{$row['PatientID']}</td><td>{$row['Name']}</td><td>{$row['AdmitDate']->format('Y-m-d')}</td></tr>";
                }
                sqlsrv_free_stmt($stmt);
                ?>
            </table>
        </div>

        <!-- Doctors -->
        <div class="tab-pane fade" id="doctor" role="tabpanel">
            <h4>Doctor List</h4>
            <table class="table table-bordered">
                <tr><th>ID</th><th>Name</th><th>Specialization</th></tr>
                <?php
                $stmt = sqlsrv_query($conn, "SELECT * FROM Doctors");
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr><td>{$row['DoctorID']}</td><td>{$row['Name']}</td><td>{$row['Specialization']}</td></tr>";
                }
                sqlsrv_free_stmt($stmt);
                ?>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

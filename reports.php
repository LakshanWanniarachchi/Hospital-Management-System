<?php
include 'db.php'; // Ensure db.php is in the same folder and has necessary functions
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Hemas Hospital</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 40px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #667eea;
            color: white;
        }

        .form-box {
            margin-bottom: 30px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
        }

        button {
            padding: 10px 20px;
            background-color: #764ba2;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #5a3e91;
        }
    </style>
</head>
<body>

<?php include 'side_bar.php'; ?>

<div class="content">
    <h2>Doctor Efficiency Report</h2>
    <table>
        <tr>
            <th>Doctor ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Total Treatments</th>
        </tr>
        <?php
        $efficiency_data = get_doctor_efficiency($conn);
        if ($efficiency_data) {
            foreach ($efficiency_data as $row) {
                echo "<tr>
                        <td>{$row['employee_id']}</td>
                        <td>{$row['doctor_name']}</td>
                        <td>{$row['specialization']}</td>
                        <td>{$row['total_treatments']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data available.</td></tr>";
        }
        ?>
    </table>

    <h2>Patient Billing Summary</h2>
    <form method="POST" class="form-box">
        <input type="text" name="patient_id" placeholder="Enter Patient ID" required />
        <button type="submit">Get Summary</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['patient_id'])) {
        $patient_id = sanitize_input($_POST['patient_id']);
        $summary = get_patient_billing_summary($conn, $patient_id);

        if ($summary) {
            echo "<table>
                    <tr><th>Patient Name</th><td>{$summary['name']}</td></tr>
                    <tr><th>Total Treatments</th><td>{$summary['total_treatments']}</td></tr>
                    <tr><th>Total Prescriptions</th><td>{$summary['total_prescriptions']} </td></tr>
                    <tr><th>Total Paid</th><td>" . format_currency($summary['total_billed']) . "</td></tr>
                  </table>";
        } else {
            echo "<p>No billing summary found for Patient ID: <strong>$patient_id</strong></p>";
        }
    }
    ?>
</div>

</body>
</html>

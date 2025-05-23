<?php
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

// Function to insert record
function insert_record($conn, $table, $data) {
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $params = array_values($data);
    return execute_query($conn, $query, $params);
}

// Function to update record
function update_record($conn, $table, $data, $where_clause, $where_params = array()) {
    $set_clause = implode(', ', array_map(function($key) { return "$key = ?"; }, array_keys($data)));
    $query = "UPDATE $table SET $set_clause WHERE $where_clause";
    $params = array_merge(array_values($data), $where_params);
    return execute_query($conn, $query, $params);
}

// Function to delete record
function delete_record($conn, $table, $where_clause, $where_params = array()) {
    $query = "DELETE FROM $table WHERE $where_clause";
    return execute_query($conn, $query, $where_params);
}

// Function to get next ID
function get_next_id($conn, $table, $id_column) {
    $query = "SELECT ISNULL(MAX($id_column), 0) + 1 as next_id FROM $table";
    $result = get_single_record($conn, $query);
    return $result['next_id'];
}

// Function to count records
function count_records($conn, $table, $where_clause = '', $where_params = array()) {
    $query = "SELECT COUNT(*) as count FROM $table";
    if (!empty($where_clause)) {
        $query .= " WHERE $where_clause";
    }
    $result = get_single_record($conn, $query, $where_params);
    return $result['count'];
}

// Function to check if record exists
function record_exists($conn, $table, $where_clause, $where_params = array()) {
    $count = count_records($conn, $table, $where_clause, $where_params);
    return $count > 0;
}

// Function to format date for display
function format_date($date, $format = 'Y-m-d') {
    if ($date instanceof DateTime) {
        return $date->format($format);
    }
    return date($format, strtotime($date));
}

// Function to format currency
function format_currency($amount) {
    return 'Rs. ' . number_format($amount, 2);
}

// Function to get doctor specializations
function get_doctor_specializations($conn) {
    $query = "SELECT * FROM dbo.GetAllDoctorSpecializations()
ORDER BY specialization;
";
    return get_multiple_records($conn, $query);
}

// Function to get available rooms
function get_available_rooms($conn) {
    $query = "SELECT r.* FROM ROOM r 
              LEFT JOIN ROOM_OCCUPANCY ro ON r.room_id = ro.room_id AND ro.check_out_date IS NULL
              WHERE ro.room_id IS NULL
              ORDER BY r.room_id";
    return get_multiple_records($conn, $query);
}

// Function to get medicine stock alerts (low stock)
function get_low_stock_medicines($conn, $threshold = 50) {
    $query = "SELECT * FROM MEDICINE WHERE stock_quantity <= ? ORDER BY stock_quantity ASC";
    return get_multiple_records($conn, $query, array($threshold));
}

// Function to get patient treatment history
function get_patient_treatments($conn, $patient_id) {
    $query = "SELECT t.*, e.name as doctor_name, d.specialization
              FROM TREATMENT t
              JOIN EMPLOYEE e ON t.doctor_id = e.employee_id
              JOIN DOCTOR d ON t.doctor_id = d.employee_id
              WHERE t.patient_id = ?
              ORDER BY t.treatment_date DESC";
    return get_multiple_records($conn, $query, array($patient_id));
}

// Function to get doctor's patients
function get_doctor_patients($conn, $doctor_id) {
    $query = "SELECT DISTINCT p.*, t.treatment_date as last_visit
              FROM PATIENT p
              JOIN TREATMENT t ON p.patient_id = t.patient_id
              WHERE t.doctor_id = ?
              ORDER BY t.treatment_date DESC";
    return get_multiple_records($conn, $query, array($doctor_id));
}

// Function to calculate bill total
function calculate_bill_total($amount, $discount = 0) {
    return $amount - $discount;
}

// Function to create bill using stored procedure
function create_bill($conn, $patientId, $amount, $discount, $method) {
    $query = "{CALL CreateBill(?, ?, ?, ?)}";
    $params = array($patientId, $amount, $discount, $method);
    return execute_query($conn, $query, $params);
}


// Function to get doctor efficiency statistics
function get_doctor_efficiency($conn) {
    $query = "SELECT * FROM DoctorEfficiency ORDER BY total_treatments DESC";
    return get_multiple_records($conn, $query);
}

// Function to get patient billing summary
function get_patient_billing_summary($conn, $patient_id) {
    $query = "{CALL GetPatientBillingSummary(?)}";
    $params = array($patient_id);
    $result = get_single_record($conn, $query, $params);
    return $result;
}



// Set timezone
date_default_timezone_set('Asia/Colombo');
?>
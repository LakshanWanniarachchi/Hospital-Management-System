<?php
require_once 'db.php';

// Handle form submission for creating a new bill
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_bill'])) {
    try {
        $patient_id = sanitize_input($_POST['patient_id']);
        $amount = sanitize_input($_POST['amount']);
        $discount = sanitize_input($_POST['discount']);
        $method = sanitize_input($_POST['payment_method']);
        
        // Create bill using stored procedure
        create_bill($conn, $patient_id, $amount, $discount, $method);
        $success_message = "Bill created successfully!";
    } catch (Exception $e) {
        $error_message = "Error creating bill: " . $e->getMessage();
    }
}

// Get all bills
$bills = get_multiple_records($conn, 
    "SELECT b.*, p.name as patient_name 
     FROM BILL b
     JOIN PATIENT p ON b.patient_id = p.patient_id
     ORDER BY b.bill_date DESC"
);

// Get patients for dropdown
$patients = get_multiple_records($conn, "SELECT patient_id, name FROM PATIENT ORDER BY name");
?>


<?php include 'side_bar.php'; ?>


        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div>
                    <h1><i class="fas fa-file-invoice-dollar"></i> Billing Management</h1>
                    <p>View and manage patient bills</p>
                </div>
                <button class="btn btn-primary" onclick="openCreateBillModal()">
                    <i class="fas fa-plus"></i> Create New Bill
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

            <!-- Bills Table -->
            <div class="content-card">
                <h3 class="card-title">
                    <i class="fas fa-list"></i>
                    All Bills
                </h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bill ID</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bills as $bill): 
                                $total = calculate_bill_total($bill['amount'], $bill['discount']);
                                $statusClass = $bill['payment_status'] == 'Paid' ? 'status-paid' : 'status-pending';
                            ?>
                                <tr>
                                    <td>#<?php echo $bill['bill_id']; ?></td>
                                    <td><?php echo $bill['patient_name']; ?></td>
                                    <td><?php echo $bill['bill_date']->format('Y-m-d'); ?></td>
                                    <td>Rs. <?php echo number_format($bill['amount'], 2); ?></td>
                                    <td>Rs. <?php echo number_format($bill['discount'], 2); ?></td>
                                    <td>Rs. <?php echo number_format($total, 2); ?></td>
                                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $bill['payment_status']; ?></span></td>
                                    <td><?php echo $bill['payment_method']; ?></td>
                                    <td>
                                        <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="print_bill.php?id=<?php echo $bill['bill_id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Bill Modal -->
    <div id="createBillModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-file-invoice-dollar"></i> Create New Bill</h2>
                <span class="close" onclick="closeCreateBillModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
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
                        <label class="form-label">Amount (Rs.)</label>
                        <div class="amount-input">
                            <span>Rs.</span>
                            <input type="number" name="amount" class="form-control" 
                                   placeholder="Enter amount" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Discount (Rs.)</label>
                        <div class="amount-input">
                            <span>Rs.</span>
                            <input type="number" name="discount" class="form-control" 
                                   placeholder="Enter discount" step="0.01" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Select Method...</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Insurance">Insurance</option>
                        </select>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="closeCreateBillModal()">
                            Cancel
                        </button>
                        <button type="submit" name="create_bill" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openCreateBillModal() {
            document.getElementById('createBillModal').style.display = 'block';
        }

        function closeCreateBillModal() {
            document.getElementById('createBillModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('createBillModal');
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
</body>
</html>

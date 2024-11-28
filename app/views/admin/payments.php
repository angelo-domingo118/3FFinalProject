<?php
$pageTitle = "Payments";
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Payments</h1>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Payment Transactions
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                <button type="button" class="btn btn-outline-success" data-filter="paid">Paid</button>
                <button type="button" class="btn btn-outline-warning" data-filter="unpaid">Unpaid</button>
                <button type="button" class="btn btn-outline-danger" data-filter="refunded">Refunded</button>
            </div>
        </div>
        <div class="card-body">
            <table id="paymentsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr class="payment-row" data-status="<?= strtolower($payment['status']) ?>">
                        <td><?= $payment['transaction_id'] ?></td>
                        <td><?= $payment['booking_id'] ?></td>
                        <td><?= htmlspecialchars($payment['customer_name']) ?></td>
                        <td><?= htmlspecialchars($payment['service_name']) ?></td>
                        <td>₱<?= number_format($payment['amount'], 2) ?></td>
                        <td>
                            <span class="badge <?= getStatusBadgeClass($payment['status']) ?>">
                                <?= $payment['status'] ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary view-payment" data-id="<?= $payment['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if ($payment['status'] === 'UNPAID'): ?>
                                <button class="btn btn-sm btn-success mark-paid" data-id="<?= $payment['id'] ?>">
                                    <i class="fas fa-check"></i>
                                </button>
                                <?php endif; ?>
                                <?php if ($payment['status'] === 'PAID'): ?>
                                <button class="btn btn-sm btn-danger mark-refunded" data-id="<?= $payment['id'] ?>">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Payment details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const table = new DataTable('#paymentsTable', {
        order: [[6, 'desc']], // Sort by payment date by default
        pageLength: 10,
        responsive: true
    });

    // Filter buttons
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button state
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            // Apply filter
            if (filter === 'all') {
                table.search('').draw();
            } else {
                table.search(filter).draw();
            }
        });
    });

    // Handle status updates
    document.querySelectorAll('.mark-paid, .mark-refunded').forEach(button => {
        button.addEventListener('click', async function() {
            const paymentId = this.dataset.id;
            const action = this.classList.contains('mark-paid') ? 'paid' : 'refunded';
            
            if (confirm(`Are you sure you want to mark this payment as ${action}?`)) {
                try {
                    const response = await fetch(`/admin/payments/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_id: paymentId,
                            status: action.toUpperCase()
                        })
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to update payment status');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating the payment status');
                }
            }
        });
    });
});

function getStatusBadgeClass(status) {
    switch(status.toLowerCase()) {
        case 'paid':
            return 'bg-success';
        case 'unpaid':
            return 'bg-warning';
        case 'refunded':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
</script>

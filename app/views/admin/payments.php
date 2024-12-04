<?php
// Debug information
echo "<!-- Debug: Start of view file -->\n";
echo "<!-- Debug: Payments variable exists: " . (isset($payments) ? 'Yes' : 'No') . " -->\n";
echo "<!-- Debug: Payments is array: " . (is_array($payments) ? 'Yes' : 'No') . " -->\n";
echo "<!-- Debug: Number of payments: " . (isset($payments) ? count($payments) : 'N/A') . " -->\n";
?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Payment Transactions</h2>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
            <button type="button" class="btn btn-outline-success" data-filter="paid">Paid</button>
            <button type="button" class="btn btn-outline-warning" data-filter="unpaid">Unpaid</button>
            <button type="button" class="btn btn-outline-danger" data-filter="refunded">Refunded</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Appointment ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($payments) && is_array($payments) && !empty($payments)): 
                            foreach ($payments as $payment): 
                                // Debug each payment
                                echo "<!-- Debug: Processing payment: " . print_r($payment, true) . " -->\n";
                        ?>
                                <tr class="payment-row" data-status="<?= htmlspecialchars($payment['payment_status']) ?>">
                                    <td><?= htmlspecialchars($payment['transaction_id']) ?></td>
                                    <td><?= htmlspecialchars($payment['appointment_id']) ?></td>
                                    <td>₱<?= number_format($payment['amount'], 2) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($payment['payment_method'])) ?></td>
                                    <td>
                                        <span class="badge <?php
                                            echo match($payment['payment_status']) {
                                                'paid' => 'bg-success',
                                                'unpaid' => 'bg-warning',
                                                'refunded' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        ?>">
                                            <?= ucfirst(htmlspecialchars($payment['payment_status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y H:i', strtotime($payment['payment_date'])) ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if ($payment['payment_status'] === 'unpaid'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-success mark-as-paid"
                                                        data-payment-id="<?= $payment['payment_id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Mark as Paid">
                                                    <i class="bi bi-check2-circle"></i> Mark as Paid
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-warning mark-as-refunded"
                                                        data-payment-id="<?= $payment['payment_id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Mark as Refunded">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Mark as Refunded
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($payment['payment_status'] === 'paid'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger refund-payment"
                                                        data-payment-id="<?= $payment['payment_id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Refund Payment">
                                                    <i class="bi bi-arrow-return-left"></i> Refund
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <?php 
                                    if (!isset($payments)) {
                                        echo "Error: Payments variable is not set";
                                    } elseif (!is_array($payments)) {
                                        echo "Error: Payments is not an array";
                                    } else {
                                        echo "No payment records found";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for action buttons */
    .btn i {
        margin-right: 5px;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .mark-as-paid:hover {
        background-color: #198754;
        color: white;
        transform: translateY(-1px);
    }

    .mark-as-refunded:hover {
        background-color: #ffc107;
        color: black;
        transform: translateY(-1px);
    }

    .refund-payment:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-1px);
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .d-flex.gap-2 {
        gap: 0.5rem !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter buttons
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.querySelectorAll('.payment-row').forEach(row => {
                if (filter === 'all' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update active button state
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Mark as Paid functionality
    document.querySelectorAll('.mark-as-paid').forEach(button => {
        button.addEventListener('click', async function() {
            const paymentId = this.dataset.paymentId;
            console.log('Payment ID:', paymentId); // Debug log
            
            if (confirm('Are you sure you want to mark this payment as paid?')) {
                try {
                    console.log('Sending request to update payment status...'); // Debug log
                    const response = await fetch('/cit17-final-project/public/admin/payments/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId,
                            status: 'paid'
                        })
                    });

                    console.log('Response status:', response.status); // Debug log
                    console.log('Response headers:', Object.fromEntries(response.headers)); // Debug log

                    if (response.ok) {
                        const result = await response.json();
                        console.log('Success response:', result); // Debug log
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        console.error('Error response:', data); // Debug log
                        alert('Error: ' + (data.error || 'Failed to update payment status'));
                    }
                } catch (error) {
                    console.error('Fetch error:', error); // Debug log
                    alert('Error: Failed to communicate with the server');
                }
            }
        });
    });

    // Mark as Refunded functionality
    document.querySelectorAll('.mark-as-refunded').forEach(button => {
        button.addEventListener('click', async function() {
            const paymentId = this.dataset.paymentId;
            if (confirm('Are you sure you want to mark this payment as refunded? This action cannot be undone.')) {
                try {
                    const response = await fetch('/cit17-final-project/public/admin/payments/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId,
                            status: 'refunded'
                        })
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        alert('Error: ' + (data.error || 'Failed to mark as refunded'));
                    }
                } catch (error) {
                    alert('Error: Failed to communicate with the server');
                }
            }
        });
    });

    // Refund Payment functionality
    document.querySelectorAll('.refund-payment').forEach(button => {
        button.addEventListener('click', async function() {
            const paymentId = this.dataset.paymentId;
            if (confirm('Are you sure you want to refund this payment? This action cannot be undone.')) {
                try {
                    const response = await fetch('/cit17-final-project/public/admin/payments/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId,
                            status: 'refunded'
                        })
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        alert('Error: ' + (data.error || 'Failed to process refund'));
                    }
                } catch (error) {
                    alert('Error: Failed to communicate with the server');
                }
            }
        });
    });
});
</script>

<?php
echo "<!-- Debug: End of view file -->\n";
?>

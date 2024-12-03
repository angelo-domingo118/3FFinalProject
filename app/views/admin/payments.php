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
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info view-details" 
                                                    data-payment-id="<?= $payment['payment_id'] ?>"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="View Payment Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <?php if ($payment['payment_status'] === 'unpaid'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success mark-as-paid"
                                                        data-payment-id="<?= $payment['payment_id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Mark as Paid">
                                                    <i class="bi bi-check2-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($payment['payment_status'] === 'paid'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger refund-payment"
                                                        data-payment-id="<?= $payment['payment_id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Refund Payment">
                                                    <i class="bi bi-arrow-return-left"></i>
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

<style>
    /* Custom styles for action buttons */
    .btn-outline-info:hover i,
    .btn-outline-success:hover i,
    .btn-outline-danger:hover i {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    .btn-outline-info:focus,
    .btn-outline-success:focus,
    .btn-outline-danger:focus {
        box-shadow: none;
    }

    .btn-outline-info i,
    .btn-outline-success i,
    .btn-outline-danger i {
        font-size: 1rem;
    }

    .d-flex.gap-2 {
        align-items: center;
    }

    /* Hover animations */
    .btn-outline-info:hover {
        background-color: rgba(13, 202, 240, 0.1);
    }

    .btn-outline-success:hover {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .btn-outline-danger:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }

    /* Add subtle transition for all button states */
    .btn {
        transition: all 0.2s ease-in-out;
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

    // View Details button
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.dataset.paymentId;
            // Fetch and show payment details in modal
            fetch(`/admin/payments/details/${paymentId}`)
                .then(response => response.json())
                .then(data => {
                    const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
                    document.querySelector('#paymentDetailsModal .modal-body').innerHTML = `
                        <dl class="row">
                            <dt class="col-sm-4">Transaction ID</dt>
                            <dd class="col-sm-8">${data.transaction_id}</dd>
                            
                            <dt class="col-sm-4">Appointment ID</dt>
                            <dd class="col-sm-8">${data.appointment_id}</dd>
                            
                            <dt class="col-sm-4">Amount</dt>
                            <dd class="col-sm-8">₱${parseFloat(data.amount).toFixed(2)}</dd>
                            
                            <dt class="col-sm-4">Original Amount</dt>
                            <dd class="col-sm-8">₱${parseFloat(data.original_amount).toFixed(2)}</dd>
                            
                            <dt class="col-sm-4">Discount</dt>
                            <dd class="col-sm-8">₱${parseFloat(data.discount_amount).toFixed(2)}</dd>
                            
                            <dt class="col-sm-4">Final Amount</dt>
                            <dd class="col-sm-8">₱${parseFloat(data.final_amount).toFixed(2)}</dd>
                            
                            <dt class="col-sm-4">Payment Method</dt>
                            <dd class="col-sm-8">${data.payment_method.toUpperCase()}</dd>
                            
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">${data.payment_status.toUpperCase()}</dd>
                            
                            <dt class="col-sm-4">Payment Date</dt>
                            <dd class="col-sm-8">${new Date(data.payment_date).toLocaleString()}</dd>
                        </dl>
                    `;
                    modal.show();
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Mark as Paid button
    document.querySelectorAll('.mark-as-paid').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.dataset.paymentId;
            if (confirm('Are you sure you want to mark this payment as paid?')) {
                fetch('/admin/payments/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        status: 'paid'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to update payment status');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    // Refund Payment button
    document.querySelectorAll('.refund-payment').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.dataset.paymentId;
            if (confirm('Are you sure you want to refund this payment?')) {
                fetch('/admin/payments/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        status: 'refunded'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to update payment status');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});</script>

<?php
echo "<!-- Debug: End of view file -->\n";
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Promotions & Rewards</h4>
            </div>
        </div>
    </div>

    <!-- Active Promotions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Active Promotions</h5>
                        <?php if (!empty($promotions)): ?>
                            <span class="badge bg-primary"><?php echo count($promotions); ?> Active</span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($promotions)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-gift display-4 text-muted mb-3"></i>
                            <p class="text-muted">No active promotions at the moment</p>
                            <small class="text-muted">Check back soon for special offers!</small>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($promotions as $promo): ?>
                                <div class="col-lg-6">
                                    <div class="promo-card bg-light rounded p-4">
                                        <div class="ribbon-wrapper">
                                            <div class="ribbon bg-primary">
                                                <?php echo htmlspecialchars($promo['discount_percent']); ?>% OFF
                                            </div>
                                        </div>
                                        <h5 class="mb-3"><?php echo htmlspecialchars($promo['description']); ?></h5>
                                        <div class="mb-4">
                                            <small class="text-muted">Valid until</small>
                                            <div class="fw-bold">
                                                <?php echo date('F j, Y', strtotime($promo['end_date'])); ?>
                                            </div>
                                        </div>
                                        <div class="promo-code-box bg-white rounded p-3">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <small class="text-muted d-block mb-1">Promo Code</small>
                                                    <span class="h5 mb-0 text-primary">
                                                        <?php echo htmlspecialchars($promo['promo_code']); ?>
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <button class="btn btn-outline-primary btn-sm copy-code" 
                                                            data-code="<?php echo htmlspecialchars($promo['promo_code']); ?>">
                                                        <i class="bi bi-clipboard me-2"></i>Copy
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.copy-code').forEach(button => {
    button.addEventListener('click', function() {
        const code = this.dataset.code;
        navigator.clipboard.writeText(code);
        
        // Change button text temporarily
        const originalHTML = this.innerHTML;
        this.innerHTML = '<i class="bi bi-check2 me-2"></i>Copied!';
        setTimeout(() => {
            this.innerHTML = originalHTML;
        }, 2000);
    });
});
</script> 
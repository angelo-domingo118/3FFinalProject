<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-0">My Reviews</h4>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($reviews)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-star display-1 text-muted mb-3"></i>
                    <h5>No reviews yet</h5>
                    <p class="text-muted">Your reviews will appear here after you complete appointments</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($review['service_name']); ?></h5>
                                <small class="text-muted">
                                    <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                </small>
                            </div>
                            <div class="mb-3">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="card-text"><?php echo htmlspecialchars($review['comment']); ?></p>
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-person-circle me-2"></i>
                                Therapist: <?php echo htmlspecialchars($review['therapist_name']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $current_page === $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div> 
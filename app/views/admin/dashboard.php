<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard Overview</h1>
        <span class="text-muted">Welcome back, <?php echo $_SESSION['full_name']; ?>!</span>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Bookings</h6>
                            <h2 class="card-title mb-0"><?php echo array_sum($booking_counts ?? []); ?></h2>
                            <small class="text-success">
                                <i class="bi bi-graph-up"></i> Recent Activity
                            </small>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ... other stat cards ... -->
    </div>
</div> 
</script> 
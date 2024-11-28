<div class="container-fluid px-4">
    <h1 class="mt-4">Reports & Analytics</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/cit17-final-project/public/admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>

    <!-- Booking Trends -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>
            Booking Trends (Last 6 Months)
        </div>
        <div class="card-body">
            <canvas id="bookingTrendsChart" width="100%" height="30"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Earnings -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Monthly Earnings
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Service Ratings & Popularity
                </div>
                <div class="card-body">
                    <canvas id="satisfactionChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Prepare data from PHP
const bookingStats = <?php echo json_encode($bookingStats); ?>;
const earningsStats = <?php echo json_encode($earningsStats); ?>;
const customerStats = <?php echo json_encode($customerStats); ?>;

// Booking Trends Chart
const bookingCtx = document.getElementById('bookingTrendsChart');
new Chart(bookingCtx, {
    type: 'line',
    data: {
        labels: bookingStats.map(stat => stat.month),
        datasets: [
            {
                label: 'Total Bookings',
                data: bookingStats.map(stat => stat.total_bookings),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            },
            {
                label: 'Completed Bookings',
                data: bookingStats.map(stat => stat.completed_bookings),
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Booking Trends Over Time'
            }
        }
    }
});

// Earnings Chart
const earningsCtx = document.getElementById('earningsChart');
new Chart(earningsCtx, {
    type: 'bar',
    data: {
        labels: earningsStats.map(stat => stat.month),
        datasets: [{
            label: 'Monthly Earnings',
            data: earningsStats.map(stat => stat.total_earnings),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Monthly Revenue'
            }
        }
    }
});

// Customer Satisfaction Chart
const satisfactionCtx = document.getElementById('satisfactionChart');
new Chart(satisfactionCtx, {
    type: 'radar',
    data: {
        labels: customerStats.map(stat => `Service ${stat.service_id}`),
        datasets: [{
            label: 'Booking Count',
            data: customerStats.map(stat => stat.booking_count),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
        },
        {
            label: 'Average Rating',
            data: customerStats.map(stat => stat.avg_rating),
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Service Performance Matrix'
            }
        }
    }
});
</script>

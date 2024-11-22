<?php include 'templates/header.php'; ?>

<div class="jumbotron">
    <h1 class="display-4">Welcome to BookingApp!</h1>
    <p class="lead">Your Wellness Journey Starts Here.</p>
    <hr class="my-4">
    <a class="btn btn-primary btn-lg" href="/booking" role="button">Book Now</a>
    <a class="btn btn-secondary btn-lg" href="/services" role="button">View Services</a>
</div>

<!-- Services Overview -->
<h2>Our Services</h2>
<div class="row">
    <!-- Repeat this card for each service -->
    <?php foreach ($services as $service): ?>
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="/assets/images/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service['name']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                <a href="/booking" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- End of service card -->
</div>

<?php include 'templates/footer.php'; ?> 
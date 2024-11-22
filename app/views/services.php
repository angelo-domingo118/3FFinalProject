<?php include 'templates/header.php'; ?>

<h2>Our Services</h2>
<div class="row">
    <?php foreach ($services as $service): ?>
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="/assets/images/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service['name']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                <p class="card-text"><strong>Price:</strong> $<?php echo number_format($service['price'], 2); ?></p>
                <a href="/booking" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'templates/footer.php'; ?> 
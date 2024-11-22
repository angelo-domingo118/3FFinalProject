<?php include '../templates/header.php'; ?>

<h2>User Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here you can view your appointments.</p>

<!-- Add user-specific functionalities here -->

<?php include '../templates/footer.php'; ?> 
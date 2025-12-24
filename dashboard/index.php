<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}
include '../includes/header.php';
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        <h3>Dashboard</h3>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="students.php">Manage Students</a></li>
            <li><a href="teachers.php">Manage Teachers</a></li>
            <li><a href="results.php">Manage Results</a></li>
            <li><a href="notices.php">Manage Notices</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Content -->
    <div class="dashboard-content">
        <h2>Welcome, <?php echo $_SESSION['admin']; ?></h2>
        <p>Select an option from the sidebar to manage the school system.</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>


<?php

require_once "../includes/auth.php";
requireRole("admin");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="dashboard">

    <h1>EduMentor AI Admin Panel</h1>

    <h2>
        Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>
    </h2>

    <div class="dashboard-grid">

        <div class="module-card">
            <h3>Manage Students</h3>
            <p>View and manage students.</p>
        </div>

        <div class="module-card">
            <h3>Manage Teachers</h3>
            <p>Manage teacher accounts.</p>
        </div>

        <div class="module-card">
            <h3>Manage Courses</h3>
            <p>Manage all courses.</p>
        </div>

        <div class="module-card">
            <h3>Manage Scholarships</h3>
            <p>Add scholarship information.</p>
        </div>

        <div class="module-card">
            <h3>Notifications</h3>
            <p>Manage application notifications.</p>
        </div>

        <div class="module-card">
            <h3>System Reports</h3>
            <p>View application reports.</p>
        </div>

    </div>

    <a class="logout" href="../logout.php">Logout</a>

</div>

</body>
</html>

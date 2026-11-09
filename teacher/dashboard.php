
<?php

require_once "../includes/auth.php";
requireRole("teacher");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="dashboard">

    <h1>Teacher Dashboard</h1>

    <h2>
        Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>
    </h2>

    <div class="dashboard-grid">

        <div class="module-card">
            <h3>Manage Courses</h3>
            <p>Create and manage courses.</p>
        </div>

        <div class="module-card">
            <h3>Upload Materials</h3>
            <p>Upload learning materials.</p>
        </div>

        <div class="module-card">
            <h3>Manage Quiz</h3>
            <p>Create quiz questions.</p>
        </div>

        <div class="module-card">
            <h3>Student Feedback</h3>
            <p>Provide student feedback.</p>
        </div>

    </div>

    <a class="logout" href="../logout.php">Logout</a>

</div>

</body>
</html>

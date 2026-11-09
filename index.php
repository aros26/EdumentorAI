
<?php

session_start();

if (isset($_SESSION["user_id"])) {

    if ($_SESSION["role"] == "student") {
        header("Location: student/dashboard.php");
    }
    elseif ($_SESSION["role"] == "teacher") {
        header("Location: teacher/dashboard.php");
    }
    elseif ($_SESSION["role"] == "admin") {
        header("Location: admin/dashboard.php");
    }

    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>EduMentor AI</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="home">

    <h1>EduMentor AI</h1>

    <h2>Smart Learning, Smarter Future</h2>

    <p>
        Your AI-powered education and career guidance platform.
    </p>

    <a href="login.php" class="btn">Login</a>

    <a href="register.php" class="btn">Get Started</a>

</div>

</body>
</html>

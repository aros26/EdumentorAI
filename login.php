
<?php

session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify(
        $password,
        $user["password"]
    )) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] == "student") {

            header("Location: student/dashboard.php");

        } elseif ($user["role"] == "teacher") {

            header("Location: teacher/dashboard.php");

        } elseif ($user["role"] == "admin") {

            header("Location: admin/dashboard.php");

        }

        exit;

    } else {

        $message = "Invalid email or password.";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>EduMentor AI - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="auth-container">

    <h1>EduMentor AI</h1>
    <h2>Welcome Back!</h2>

    <?php if (isset($_GET["registered"])): ?>
        <p>Registration successful! Please login.</p>
    <?php endif; ?>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="email"
               name="email"
               placeholder="Email Address"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit">
            Login
        </button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>

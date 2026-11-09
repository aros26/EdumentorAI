<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

requireRole('student');

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

// Total enrolled courses
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM enrollments WHERE student_id = ?"
);
$stmt->execute([$student_id]);
$total_courses = $stmt->fetchColumn();

// Total quiz attempts
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM quiz_attempts WHERE student_id = ?"
);
$stmt->execute([$student_id]);
$total_quizzes = $stmt->fetchColumn();

// Average quiz percentage
$stmt = $conn->prepare(
    "SELECT
        CASE
            WHEN SUM(total_marks) > 0
            THEN ROUND(SUM(score) * 100 / SUM(total_marks), 0)
            ELSE 0
        END
     FROM quiz_attempts
     WHERE student_id = ?"
);
$stmt->execute([$student_id]);
$average_score = $stmt->fetchColumn() ?: 0;

// Syllabus progress
$stmt = $conn->prepare(
    "SELECT
        COUNT(st.id) AS total_topics,
        COALESCE(SUM(sp.is_completed), 0) AS completed_topics
     FROM enrollments e
     JOIN syllabus_topics st ON st.course_id = e.course_id
     LEFT JOIN syllabus_progress sp
        ON sp.topic_id = st.id AND sp.student_id = e.student_id
     WHERE e.student_id = ?"
);
$stmt->execute([$student_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

$total_topics = (int)$progress['total_topics'];
$completed_topics = (int)$progress['completed_topics'];

$syllabus_percentage = $total_topics > 0
    ? round(($completed_topics / $total_topics) * 100)
    : 0;

// Enrolled courses
$stmt = $conn->prepare(
    "SELECT c.title, c.category, u.name AS teacher_name
     FROM enrollments e
     JOIN courses c ON e.course_id = c.id
     LEFT JOIN users u ON c.teacher_id = u.id
     WHERE e.student_id = ?
     ORDER BY c.id DESC"
);
$stmt->execute([$student_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Upcoming reminders
$stmt = $conn->prepare(
    "SELECT title, reminder_time
     FROM reminders
     WHERE user_id = ? AND is_completed = 0
     ORDER BY reminder_time ASC
     LIMIT 5"
);
$stmt->execute([$student_id]);
$reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Notifications
$stmt = $conn->prepare(
    "SELECT message, created_at
     FROM notifications
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 5"
);
$stmt->execute([$student_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// AI Recommendations
$stmt = $conn->prepare(
    "SELECT recommendation_type, recommendation_text
     FROM recommendations
     WHERE student_id = ?
     ORDER BY created_at DESC
     LIMIT 5"
);
$stmt->execute([$student_id]);
$recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMentor AI - Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            margin: 0;
            background: #f3f6fc;
            font-family: Arial, sans-serif;
            color: #172554;
        }

        .navbar {
            background: #172554;
            color: white;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 { margin: 0; }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #2563eb;
            padding: 10px 18px;
            border-radius: 8px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h1 {
            margin-bottom: 8px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card, .section {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }

        .stat-card h3 {
            color: #64748b;
            font-size: 14px;
        }

        .stat-card h2 {
            font-size: 30px;
            color: #2563eb;
            margin: 10px 0 0;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 20px;
            margin-top: 0;
        }

        .item {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item strong {
            display: block;
            margin-bottom: 5px;
        }

        .muted {
            color: #64748b;
            font-size: 13px;
        }

        .empty {
            color: #64748b;
            padding: 12px 0;
        }

        @media(max-width: 600px) {
            .navbar {
                padding: 16px;
            }

            .container {
                margin: 20px auto;
                padding: 0 14px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <h2>EduMentor AI</h2>
    <a href="../logout.php">Logout</a>
</header>

<main class="container">

    <div class="welcome">
        <h1>Welcome, <?= e($student_name) ?>! 👋</h1>
        <p>Here is your learning progress and activities.</p>
    </div>

    <div class="stats">

        <div class="stat-card">
            <h3>Enrolled Courses</h3>
            <h2><?= (int)$total_courses ?></h2>
        </div>

        <div class="stat-card">
            <h3>Quiz Attempts</h3>
            <h2><?= (int)$total_quizzes ?></h2>
        </div>

        <div class="stat-card">
            <h3>Average Quiz Score</h3>
            <h2><?= (int)$average_score ?>%</h2>
        </div>

        <div class="stat-card">
            <h3>Syllabus Progress</h3>
            <h2><?= (int)$syllabus_percentage ?>%</h2>
        </div>

    </div>

    <div class="content-grid">

        <section class="section">
            <h2>📚 My Courses</h2>

            <?php if ($courses): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="item">
                        <strong><?= e($course['title']) ?></strong>
                        <div class="muted">
                            <?= e($course['category']) ?>
                        </div>
                        <div class="muted">
                            Teacher: <?= e($course['teacher_name'] ?? 'Not assigned') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No courses enrolled yet.</p>
            <?php endif; ?>
        </section>

        <section class="section">
            <h2>⏰ My Reminders</h2>

            <?php if ($reminders): ?>
                <?php foreach ($reminders as $reminder): ?>
                    <div class="item">
                        <strong><?= e($reminder['title']) ?></strong>
                        <div class="muted">
                            <?= e($reminder['reminder_time']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No upcoming reminders.</p>
            <?php endif; ?>
        </section>

        <section class="section">
            <h2>🔔 Notifications</h2>

            <?php if ($notifications): ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="item">
                        <?= e($notification['message']) ?>
                        <div class="muted">
                            <?= e($notification['created_at']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No notifications.</p>
            <?php endif; ?>
        </section>

        <section class="section">
            <h2>🤖 AI Recommendations</h2>

            <?php if ($recommendations): ?>
                <?php foreach ($recommendations as $recommendation): ?>
                    <div class="item">
                        <strong>
                            <?= e($recommendation['recommendation_type']) ?>
                        </strong>
                        <?= e($recommendation['recommendation_text']) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No recommendations available.</p>
            <?php endif; ?>
        </section>

    </div>

</main>

</body>
</html>
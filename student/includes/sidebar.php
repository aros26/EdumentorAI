
<aside class="sidebar">

    <div class="brand">

        <div class="brand-icon">🎓</div>

        <div>
            <h2>EduMentor</h2>
            <small>AI Learning Platform</small>
        </div>

    </div>


    <div class="sidebar-label">
        MAIN MENU
    </div>


    <nav class="sidebar-nav">

        <a href="dashboard.php" class="nav-link active">
            <span>🏠</span>
            Dashboard
        </a>

        <div class="sidebar-label">LEARNING</div>

        <span class="nav-link">
            <span>🤖</span>
            AI Learning Assistant
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>📚</span>
            My Courses
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>📝</span>
            Quiz & Practice
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>📊</span>
            Syllabus Progress
            <small>Coming soon</small>
        </span>


        <div class="sidebar-label">CAREER</div>

        <span class="nav-link">
            <span>🎯</span>
            Career Guidance
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>💡</span>
            Skill Development
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>🎓</span>
            Scholarships
            <small>Coming soon</small>
        </span>


        <div class="sidebar-label">PERSONAL</div>

        <span class="nav-link">
            <span>⏰</span>
            Reminders
            <small>Coming soon</small>
        </span>

        <span class="nav-link">
            <span>🔔</span>
            Notifications
            <small>Coming soon</small>
        </span>

    </nav>


    <div class="sidebar-bottom">

        <div class="user-mini">

            <div class="user-avatar">👤</div>

            <div>
                <strong>
                    <?php echo htmlspecialchars(
                        $_SESSION["name"],
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </strong>

                <small>Student Account</small>
            </div>

        </div>

        <a href="../logout.php" class="sidebar-logout">
            🚪 Logout
        </a>

    </div>

</aside>

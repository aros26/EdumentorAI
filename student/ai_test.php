<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../config/ai_config.php";

requireRole('student');

if (!isset($_SESSION['ai_test_token'])) {
    $_SESSION['ai_test_token'] = bin2hex(random_bytes(32));
}

$result = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['ai_test_token'], $_POST['csrf_token'])
    ) {
        $error = "Invalid request. Please refresh the page.";
    } elseif (!function_exists('curl_init')) {
        $error = "PHP cURL extension is not enabled.";
    } else {

        $payload = [
            "model" => "gpt-4.1-mini",
            "instructions" => "You are EduMentor AI, a friendly educational assistant. Reply in simple English.",
            "input" => "Introduce yourself as EduMentor AI in 3 short sentences.",
            "max_output_tokens" => 200,
            "store" => false
        ];

        $ch = curl_init("https://api.openai.com/v1/responses");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer " . OPENAI_API_KEY
            ],
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            $error = "Connection failed: " . $curlError;
        } else {

            $data = json_decode($response, true);

            if ($httpCode >= 200 && $httpCode < 300) {

                foreach (($data['output'] ?? []) as $output) {
                    foreach (($output['content'] ?? []) as $content) {
                        if ($content['type'] === 'output_text') {
                            $result .= $content['text'] . "\n";
                        }
                    }
                }

                if ($result === "") {
                    $error = "AI response received, but no text was found.";
                }

            } else {
                $error = $data['error']['message']
                    ?? "OpenAI API request failed. HTTP Code: " . $httpCode;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMentor AI - API Test</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 8px;
            cursor: pointer;
        }

        .success {
            background: #dcfce7;
            padding: 15px;
            border-radius: 8px;
            white-space: pre-wrap;
        }

        .error {
            background: #fee2e2;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🤖 EduMentor AI</h1>
    <h3>OpenAI API Connection Test</h3>

    <p>Click the button to test your AI connection.</p>

    <form method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['ai_test_token']) ?>"
        >

        <button type="submit">Test OpenAI Connection</button>
    </form>

    <?php if ($result): ?>
        <h3>AI Response:</h3>
        <div class="success">
            <?= htmlspecialchars($result) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <h3>Connection Error:</h3>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <p>
        <a href="dashboard.php">Back to Dashboard</a>
    </p>

</div>

</body>
</html>
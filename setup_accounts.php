
<?php

require_once "config/database.php";

$accounts = [
    [
        "Admin",
        "admin@edumentor.com",
        "Admin@12345",
        "admin"
    ],
    [
        "Teacher",
        "teacher@edumentor.com",
        "Teacher@12345",
        "teacher"
    ]
];

foreach ($accounts as $account) {

    [$name, $email, $password, $role] = $account;

    $check = $conn->prepare(
        "SELECT id FROM users WHERE email = ?"
    );

    $check->execute([$email]);

    if (!$check->fetch()) {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $insert = $conn->prepare(
            "INSERT INTO users
            (name, email, password, role)
            VALUES (?, ?, ?, ?)"
        );

        $insert->execute([
            $name,
            $email,
            $hash,
            $role
        ]);
    }
}

echo "Admin and Teacher accounts created.";

?>

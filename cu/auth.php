<?php
function login($email, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Invalid email or password.";
    }

    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password'])) {
        return "Invalid email or password.";
    }

    session_start();
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
    ];
    return null;
}

function signup($name, $email, $password, $conn) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['user'] = [
            'id' => $stmt->insert_id,
            'name' => $name,
            'email' => $email,
        ];
        return null;
    } else {
        return "Sign up failed. Try again later.";
    }
}
?>
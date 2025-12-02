<?php
require 'inc/config.php';


// jika sudah login -> dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];


    $sql = "SELECT id, username, password, role, fullname FROM users WHERE username = '$username' LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        // coba verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['fullname'] = $row['fullname'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Username atau password salah.';
    }
}
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Puskesmas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="center">
    <div class="card">
        <h2>Login Puskesmas</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>
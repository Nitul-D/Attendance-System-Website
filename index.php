<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ??  '',
    'register' => $_SESSION['register_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';


session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content = "width=device-width , initial-scale=1.0">
    <title>IOCL Attendance System</title>
    <link rel="icon" href="static/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <!--===== HEADER =====-->
    <header class="l-header">
        <nav class="nav">
            <div>
                <a href="#home" class="nav__logo">INDIAN OIL CORPORATION LIMITED (IOCL)</a>
            </div>
        </nav>
    </header>

    <div class="wrapper">
        <div class="main-content">
            <div class="container">
                <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
                    <form action="login_register.php" method="POST">
                        <h4>Login</h4>

                        <?= showError($errors['login']); ?>

                        <p style="text-align: center; color: #333;">Please enter your email and password</p>

                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>

                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id = "login-password" name="password" placeholder="Password" required>
                            <i class = "fas fa-eye toggle-password" onclick = "togglePassword('login-password',this)"></i>
                        </div>

                        <button type="submit" name="login">Login</button>

                        <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>

                    </form>
                </div>
                <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
                    <form action="login_register.php" method="POST">
                        <h4>Register</h4>

                        <?= showError($errors['register']); ?>

                        <p style="text-align: center; color: #333;">Please enter your details</p>

                        <div class="input-icon">
                           <i class="fas fa-solid fa-user"></i>
                           <input type="text" name="name" placeholder="Name" required>
                        </div>

                        <div class="input-icon">
                           <i class="fas fa-envelope"></i>
                           <input type="email" name="email" placeholder="Email" required>

                        </div>

                        <div class="input-icon">
                           <i class="fas fa-lock"></i>
                           <input type="password" id = "register-password" name="password" placeholder="Password" required >
                           <i class = "fas fa-eye toggle-password" onclick = "togglePassword('register-password',this)"></i>
                        </div>

                        <div class="input-icon">
                            <i class="fas fa-id-badge"></i>
                            <input type="text" name="internid" placeholder="Intern ID" required>
                        </div>

                        <select name="role" required>
                            <option value="">--Select Role---</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <button type="submit" name="register">Register</button>

                        <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
        <!--===== FOOTER =====-->
        <footer class="footer">
            <p class="footer__copy">&#169; COPYRIGHT 2026 BY IOCL | DESIGN: Nitul C. Dutta</p>
        </footer>
    </div>

    <!--===== JAVASCRIPT =====-->
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="script.js"></script>
</body>
</html>
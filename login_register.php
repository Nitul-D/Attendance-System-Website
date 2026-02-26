<?php

session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $internid = $_POST['internid'];
    $role = $_POST['role'];

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!!';
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO users (name, email, password, internid, role, approved) VALUES ('$name', '$email', '$password', '$internid', '$role', 0)");
    }

    header("Location: index.php");
    exit();
}


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND approved = 1");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['approved'] == 0) {
            $_SESSION['login_error'] = 'Your account is pending approval!';
            $_SESSION['active_form'] = 'login';
            header("Location: index.php");
            exit();
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === 'admin') {
                header("Location: dashboard.php");
            }
            else {
                header("Location: intern.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect credentials or your account is not yet approved';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

?>
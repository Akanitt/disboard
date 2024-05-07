<?php
require '../config/database.php';

if(isset($_POST['submit'])){
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirm_password = filter_var($_POST['confirm-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($email) || empty($password) || empty($confirm_password) || $password !== $confirm_password || strlen($password) < 8 && strlen($confirm_password) < 8){
        if (empty($email)) {
            $_SESSION['email-error'] = "Email is required";
        }
        if (empty($password)) {
            $_SESSION['password-error'] = "Password is required";
        }
        if (empty($confirm_password)) {
            $_SESSION['confirm-password-error'] = "Confirm Password is required";
        }
        if ($password !== $confirm_password) {
            $_SESSION['confirm-password-error'] = "Passwords do not match";
        }
        if(strlen($password) < 8 && strlen($confirm_password) < 8){
            $_SESSION['password-error'] = "Password should be at least 8 characters";
        }
        $_SESSION['settings'] = $_POST;
        header('Location: ../pages/settings.php');
        exit();
    }

    $error = false;
    $query = "SELECT * FROM users WHERE email = :email AND id != :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $_SESSION['user']['id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['email-error'] = "Email already exists";
        $error = true;
    }

    if ($error) {
        $_SESSION['settings'] = $_POST;
        header('Location: ../pages/settings.php');
        exit();
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET email = :email, password = :password WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $_SESSION['user']['id']);
    $stmt->execute();

    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['password'] = $password;

    header('Location: ../pages/index.php');
    exit();
}
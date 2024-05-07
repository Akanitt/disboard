<?php
require '../config/database.php';

if(isset($_POST['submit'])){
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($username) || empty($password)) {
        if (empty($username)) {
            $_SESSION['username-error'] = "Username or Email is required";
        }
        if (empty($password)) {
            $_SESSION['password-error'] = "Password is required";
        }
        $_SESSION['signin'] = $_POST;
        header('Location: ../pages/login.php');
        exit();
    }
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $username);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if(count($result) == 1) {
        $user = $result[0];
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user;
            header('Location: ../pages/index.php');
            exit();
        }else{
            $_SESSION['password-error'] = "Password incorrect";
            $_SESSION['signin'] = $_POST;
            header('Location: ../pages/login.php');
            exit();
        }
    }else{
        $_SESSION['username-error'] = "Username or Email not found";
        $_SESSION['signin'] = $_POST;
        header('Location: ../pages/login.php');
        exit();
    }
}
header('Location: ../pages/login.php');
exit();
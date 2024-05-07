<?php
require '../config/database.php';

if(isset($_POST['submit'])){
    $title = isset($_POST['title']) ? filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role = filter_var($_POST['submit'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || strlen($password) < 8) {
        if(empty($firstname)){
            $_SESSION['firstname-error'] = "Firstname is required";
        }if(empty($lastname)){
            $_SESSION['lastname-error'] = "Lastname is required";
        }if(empty($username)){
            $_SESSION['username-error'] = "Username is required";
        }if(empty($email)){
            $_SESSION['email-error'] = "Email is required";
        }if(strlen($password) < 8){
            $_SESSION['password-error'] = "Password should be at least 8 characters";
        }
        $_SESSION['signup'] = $_POST;
        header('Location: ../pages/signup.php');
        exit();
        
    }elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $username)){
        $_SESSION['username-error'] = "Username should contain only letters, numbers and underscores";
        $_SESSION['signup'] = $_POST;
        header('location: ../pages/signup.php');
        exit();
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetchAll();    
    foreach ($result as $row) {
        if ($row['username'] === $username) {
            $_SESSION['username-error'] = 'Username already exists';
            $_SESSION['signup'] = $_POST;
        }
        if ($row['email'] === $email) {
            $_SESSION['email-error'] = 'Email already exists';
            $_SESSION['signup'] = $_POST;
        }
        header('location: ../pages/signup.php');
        exit();
    }
    if (!empty($_FILES['avatar']['name'])) {
        $avatar = $_FILES['avatar']['name'];
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $directory =  '../uploads/' . $username . '/avatar/';
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($avatar, PATHINFO_EXTENSION));
        $avatar_destination_path = $directory . $avatar;
        if(!in_array($extension, $allowed_extensions)){
            $_SESSION['image-error'] = 'Image format not supported';
            $_SESSION['signup'] = $_POST;
            header('location: ../pages/signup.php');
            exit();
        }elseif($_FILES['avatar']['size'] > 10485760){
            $_SESSION['image-error'] = 'Image size should not exceed 10MB';
            $_SESSION['signup'] = $_POST;
            header('location: ../pages/signup.php');
            exit();
        }else{
            if (!is_dir($directory)) {
                if (!mkdir($directory, 0755, true)) {
                    exit("Failed to create directory: {$directory}");
                }
            }
            if (!file_exists($avatar_destination_path)) {
                if(!move_uploaded_file($tmp_name, $avatar_destination_path)){
                    $_SESSION['image-error'] = 'Error uploading image';
                    $_SESSION['signup'] = $_POST;
                    header('location: ../pages/signup.php');
                    exit();
                }
            }
            $avatar = $username . '/avatar/' . basename($_FILES["avatar"]["name"]);
        }
        
    } else {
        $default = "../assets/images/default.png";
        $directory =  '../uploads/' . $username . '/avatar/';
        $avatar_destination_path = $directory . 'default.png';
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                exit("Failed to create directory: {$directory}");
            }
        }
        if (!file_exists($avatar_destination_path)) {
            if (!copy($default, $avatar_destination_path)) {
                $_SESSION['image-error'] = 'Error uploading image';
                header('location: ../pages/signup.php');
                exit();
            }
        }
        $avatar = $username . '/avatar/' . 'default.png';
    }
    $query = "INSERT INTO users (title, firstname, lastname, username, email, password, role, avatar) VALUES (:title, :firstname, :lastname, :username, :email, :password, :role, :avatar)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->execute();
    if (!$stmt->rowCount()) {
        $_SESSION['signup-failed'] = "Registration Failed. Please try again";
        $_SESSION['signup'] = $_POST;
        header('Location: ../pages/signup.php');
        exit();
    } else {
        $_SESSION['signup-success'] = "Registration Successful. Please login";
        header('Location: ../pages/login.php');
        exit();
    }
    
}
header('Location: ../pages/signup.php');
exit();
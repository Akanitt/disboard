<?php
require '../config/database.php';
if (isset($_POST['submit'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $department = filter_var($_POST['department'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $institution = filter_var($_POST['institution'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $education = filter_var($_POST['education'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $interests = filter_var($_POST['interests'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $url = filter_var($_POST['url'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($firstname) || empty($lastname)) {
        
        if (empty($firstname)) {
            $_SESSION['firstname-error'] = "Firstname is required";
        }
        if (empty($lastname)) {
            $_SESSION['lastname-error'] = "Lastname is required";
        }
        header('Location: ../pages/edit_profile.php?id=' . $_SESSION['user']['id']);
        exit();
    }
    
    if (!empty($_FILES['avatar']['name'])) {
        $new_avatar = $_FILES['avatar']['name'];
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $directory =  '../uploads/';
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($new_avatar, PATHINFO_EXTENSION));
        $avatar_destination_path = $directory . $_SESSION['user']['avatar'];
        $new_avatar_destination_path = $directory . $_SESSION['user']['username'] . '/avatar/' . $new_avatar;
        if(!in_array($extension, $allowed_extensions)){
            $_SESSION['image-error'] = 'Image format not supported';
        
            header('Location: ../pages/edit_profile.php?id=' . $_SESSION['user']['id']);    
            exit();
        }elseif($_FILES['avatar']['size'] > 10485760){
            $_SESSION['image-error'] = 'Image size should not exceed 10MB';
            header('Location: ../pages/edit_profile.php?id=' . $_SESSION['user']['id']);    
            exit();
        }else{
            if (file_exists($avatar_destination_path)) {
                unlink($avatar_destination_path);
            }
            if(!move_uploaded_file($tmp_name, $new_avatar_destination_path)){
                $_SESSION['image-error'] = 'Error uploading image';
                header('Location: ../pages/edit_profile.php?id=' . $_SESSION['user']['id']);    
                exit();
            }
        
            $avatar = $_SESSION['user']['username'] . '/avatar/' . basename($_FILES["avatar"]["name"]);
            $query = "UPDATE users SET title = :title, firstname = :firstname, lastname = :lastname, department = :department, institution = :institution, education = :education, interests = :interests, avatar = :avatar, url = :url WHERE id = :id";
        }
        
    } else{
        $query = "UPDATE users SET title = :title, firstname = :firstname, lastname = :lastname, department = :department, institution = :institution, education = :education, interests = :interests, url = :url WHERE id = :id";
    }
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':education', $education);
    $stmt->bindParam(':interests', $interests);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':id', $_SESSION['user']['id']);
    if (!empty($_FILES['avatar']['name'])) {
        $stmt->bindParam(':avatar', $avatar);
    }
    $stmt->execute();
    if (!$stmt) {
        $_SESSION['edit-profile'] = "Failed to edit profile";
        header('Location: ../pages/edit_profile.php?id=' . $_SESSION['user']['id']);
        exit();
    }
    $_SESSION['user'] = [
        'title' => $title,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'department' => $department,
        'institution' => $institution,
        'education' => $education,
        'interests' => $interests,
        'url' => $url,
    ] + $_SESSION['user'];
    if (!empty($_FILES['avatar']['name'])) {
        $_SESSION['user']['avatar'] = $avatar;
    }
    
    header('Location: ../pages/profile.php?id=' . $_SESSION['user']['id']);






}
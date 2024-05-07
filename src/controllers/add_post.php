<?php
require "../config/database.php";

if(isset($_POST['submit'])){
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $files = $_FILES['files'];
    $category = filter_var($_POST['submit'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($title) || empty($description)) {
        if(empty($title)){
            $_SESSION['title-error'] = "Enter post Title";
        }if(empty($description)){
            $_SESSION['description-error'] = "Enter post Description";
        }
        $_SESSION['add-post'] = $_POST;
        header('Location: ../pages/add_post.php');
        exit();
    }
    
    $query = "INSERT INTO posts (title, description, user_id, category) VALUES (:title, :description, :user_id, :category)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $_SESSION['user']['id']); 
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    $post_id = $connection->lastInsertId();
    $filesUploaded = false;
    foreach ($_FILES['files']['tmp_name'] as $file) {
        if (!empty($file)) {
            $filesUploaded = true;
            break;
        }
    }
    
    if ($filesUploaded) {
        $directory = '../uploads/' . $_SESSION['user']['username'] . '/files/';
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                exit("Failed to create directory:  {$directory}");
            }
        }
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['files']['name'][$key];    
            $file_destination_path = $directory . $file_name;
            if ($_FILES['files']['size'][$key] > 10485760) {
                $_SESSION['file-error'] = "File size too big. Should be less than 10MB";
                header('Location: ../pages/add_post.php');
                break;
            }
            if(!move_uploaded_file($tmp_name, $file_destination_path)){
                $_SESSION['file-error'] = "Failed to add file. Error: " . $_FILES['files']['error'][$key];
                header('Location: ../pages/add_post.php');
                exit();
            }
    
            if (!isset($_SESSION['file-error'])) {
                $path = $_SESSION['user']['username'] . '/files/'. basename($_FILES['files']['name'][$key]);
                $query = "INSERT INTO files (post_id, user_id, name, path) VALUES (:post_id, :user_id, :name, :path)";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':post_id', $post_id);
                $stmt->bindParam(':user_id', $_SESSION['user']['id']);
                $stmt->bindParam(':name', $file_name);
                $stmt->bindParam(':path', $path);
                $stmt->execute();
                if (!$stmt->rowCount()) {
                    $_SESSION['file-error'] = "Failed to add file";
                    header('Location: ../pages/add_post.php');
                    break;
                }
                
            }
            
        }
    }
    header('Location: ../pages/index.php');
    exit();

}
unset($_SESSION['add-post']);
header('Location: ../pages/add_post.php');
exit();

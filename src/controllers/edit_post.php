<?php
require '../config/database.php';

if(isset($_POST['submit'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $files = $_FILES['files'];
    
    if (empty($title) || empty($description)) {
        if(empty($title)){
            $_SESSION['title-error'] = "Enter post Title";
        }if(empty($description)){
            $_SESSION['description-error'] = "Enter post Description";
        }
        $_SESSION['post_id'] = $_POST['id'];
        header("Location: ../pages/edit_post.php?post_id=" . $_SESSION['post_id']);
        exit();
    }
    $datetime = date('Y-m-d H:i:s');
    $query = "UPDATE posts SET title = :title, description = :description, updated = :updated WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':updated', $datetime);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->execute();
    if (!$stmt) {
        $_SESSION['edit-post'] = "Failed to edit post";
        header('Location: ../pages/edit_post.php?post_id=' . $_POST['id']);
        exit();
    }
    $query = "SELECT path FROM files WHERE post_id = :post_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':post_id', $_POST['id']);
    $stmt->execute();
    $old_files = $stmt->fetchAll();
    $directory = '../uploads/';
    if (empty($_FILES['files']['tmp_name'][0])) {
        foreach ($old_files as $old_file) {
            $path = $directory . $old_file['path'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        
        $query = "DELETE FROM files WHERE post_id = :post_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':post_id', $_POST['id']);
        $stmt->execute();
        header('Location: ../pages/post.php?id=' . $_POST['id']);
        exit();
    }
    if (!empty($_FILES['files']['name'][0])) {

        $query = "SELECT * FROM files WHERE post_id = :post_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':post_id', $_POST['id']);
        $stmt->execute();
        $old_files = $stmt->fetchAll();
        $directory = '../uploads/';
        foreach ($old_files as $old_file) {
            $path = $directory . $old_file['path'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $query = "DELETE FROM files WHERE post_id = :post_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':post_id', $_POST['id']);
        $stmt->execute();
    
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
                header('Location: ../pages/edit_post.php?post_id=' . $_POST['id']);
                break;
            }
            if(!move_uploaded_file($tmp_name, $file_destination_path)){
                $_SESSION['file-error'] = "Failed to add file. Error: " . $_FILES['files']['error'][$key];
                header('Location: ../pages/edit_post.php?post_id=' . $_POST['id']);
                exit();
            }
    
            if (!isset($_SESSION['file-error'])) {
                $path = $_SESSION['user']['username'] . '/files/'. basename($_FILES['files']['name'][$key]);
                $query = "INSERT INTO files (post_id, user_id, name, path) VALUES (:post_id, :user_id, :name, :path)";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':post_id', $_POST['id']);
                $stmt->bindParam(':user_id', $_SESSION['user']['id']);
                $stmt->bindParam(':name', $file_name);
                $stmt->bindParam(':path', $path);
                $stmt->execute();
                if (!$stmt->rowCount()) {
                    $_SESSION['file-error'] = "Failed to add file";
                    header('Location: ../pages/edit_post.php?post_id=' . $_POST['id']);
                    break;
                }
                
            }
            
        }
    }
    header('Location: ../pages/post.php?id=' . $_POST['id']);
    exit();



}
header('Location: ../pages/edit_post.php?post_id=' . $_POST['id']);
exit();
?>
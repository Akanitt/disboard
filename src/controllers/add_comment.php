<?php
require '../config/database.php';

if (isset($_POST['submit'])) {
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($comment)) {
        $_SESSION['comment-error'] = "Enter a comment";
        header('Location: ../pages/post.php?id=' . $_SESSION['post_id']);
        exit();
    }
    

    $datetime = date('Y-m-d H:i:s');


    $query = "INSERT INTO comments (comment, user_id, post_id) VALUES (:comment, :user_id, :post_id)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->bindParam(':post_id', $_SESSION['post_id']);
    $stmt->execute();
    if (!$stmt) {
        $_SESSION['comment-error'] = "Failed to add comment";
        header('Location: ../pages/post.php?id=' . $_SESSION['post_id']);
        exit();
    }

    $query = "UPDATE posts SET updated = :updated WHERE id = :post_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':updated', $datetime);

    $stmt->bindParam(':post_id', $_SESSION['post_id']);
    $stmt->execute();
    if (!$stmt) {
        $_SESSION['comment-error'] = "Failed to update post";
        header('Location: pages/post.php?id=' . $_SESSION['post_id']);
        exit();
    }
    header('Location: ../pages/post.php?id=' . $_SESSION['post_id']);
    exit();
} 
header('Location: ../pages/index.php');
exit();
?>

<?php
    include 'header.php';
    $postId = isset($_SESSION['post_id']) ? $_SESSION['post_id'] : $_GET['post_id'];
    if (!isset($_SESSION['user']) || !isset($postId)) {
        header('Location: index.php');
        exit();
    }
    
    $query = "SELECT * FROM posts WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    $post = $stmt->fetch();

    if ($_SESSION['user']['id'] != $post['user_id']) {
        header('Location: index.php');
        exit();
    }
    $file_query = "SELECT * FROM files WHERE post_id = :post_id";
    $stmt = $connection->prepare($file_query);
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $files = $stmt->fetchAll();

    unset($_SESSION['post_id']);

    
?>
<title>Edit Post</title>

<main class="container">
    <h2>Edit Post</h2>
    <?php if(isset($_SESSION['edit-post'])) : ?>
    <div class="alert alert-danger" role="alert">
        <p>
            <?=
            $_SESSION['edit-post'];
            unset($_SESSION['edit-post']);
            ?>
        </p>
    </div>
    <?php endif ?>
    <form class="mt-5" action="../controllers/edit_post.php" enctype="multipart/form-data" method="POST">
        <div class="form-group">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value ="<?=$post['title']?>" placeholder="Title">
            <?php if(isset($_SESSION['title-error'])) : ?>
            <div class="invalid-feedback d-block" role="alert">
                <?=$_SESSION['title-error'];
                unset($_SESSION['title-error']);
                ?>
            </div>
            <?php endif ?>
        </div>
        <div class="form-group mt-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" rows="10" name="description" placeholder="Description"><?=$post['description']?></textarea>
            <?php if(isset($_SESSION['description-error'])) : ?>
            <div class="invalid-feedback d-block" role="alert">
                <?=$_SESSION['description-error'];
                unset($_SESSION['description-error']);
                ?>
            </div>
            <?php endif ?>
        </div>
        <div class="form-group mt-3">
            <label for="files" class="form-label">Add File</label>
            <input type="file" class="form-control" id="files" name="files[]" multiple><?$files?>
            <?php if(isset($_SESSION['file-error'])) : ?>
            <div class="invalid-feedback d-block" role="alert">
                <?=$_SESSION['file-error'];
                unset($_SESSION['file-error']);
                ?>
            </div>
            <?php endif ?>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit" >Update</button>
            </div>
            
        </div>
    </form>
</main>
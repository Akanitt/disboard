<?php
    include 'header.php';
    $title= $_SESSION['add-post']['title'] ?? null;
    $description= $_SESSION['add-post']['description'] ?? null;
    unset($_SESSION['add-post']);
?>
<title>New Post</title>

<main class="container">
    <h2>Add Post</h2>
    <form class="mt-5" action="../controllers/add_post.php" enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value ="<?= $title ?>" placeholder="Title">
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
            <textarea class="form-control" id="description" rows="10" name="description" placeholder="Description"><?=$description?></textarea>
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
            <?php if($_SESSION['user']['role'] === 'doctor') : ?>
                <div class="col-12">
                    <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit" value="problem" >Post Problem</button>
                </div>
            <?php else: ?>
                <div class="col-6">
                    <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit" value="problem">Post Problem</button>
                </div>
                <div class="col-6">
                    <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit" value="announcement">Post Announcements</button>
                </div>
            <?php endif ?>
        </div>
    </form>
</main>

<?php 
    include 'header.php';
    $query = "SELECT posts.*, users.username, users.avatar, users.firstname, users.lastname FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :post_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':post_id', $_GET['id']);
    $stmt->execute();
    $post = $stmt->fetch();
    $_SESSION['post'] = $post;



    if (!$post) {
        header("Location: index.php");
        exit();
    }
    if ($_SESSION['user']['role'] == 'doctor' && $post['user_id'] != $_SESSION['user']['id']) {
        header("Location: index.php");
        exit();
    }
    $_SESSION['post_id'] = $post['id'];
    $file_query = "SELECT * FROM files WHERE post_id = :post_id";
    $stmt = $connection->prepare($file_query);
    $stmt->bindParam(':post_id', $_SESSION['post_id']);
    $stmt->execute();
    $files = $stmt->fetchAll();
    
    $comment_query = "SELECT comments.*, users.avatar, users.firstname, users.lastname, users.role FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = :post_id";
    $stmt = $connection->prepare($comment_query);
    $stmt->bindParam(':post_id', $_SESSION['post_id']);
    $stmt->execute();
    $comments = $stmt->fetchAll();

?>
<title>
    <?= $post['title'] . ' - AIIH' ?>
</title>

<main class="container">
    <div class="col-md-12">
        <article class="blog-post">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="mb-3">
                <?= $post['title'] ?>
            </h1>
            <?php if ($_SESSION['user']['id'] == $post['user_id']): ?>
                <form action="edit_post.php?post_id=<?=$post['id']?>" method="POST">
                    <input type="hidden" name="id" value="<?= $post['id']?>">
                    <input type="hidden" name="category" value="<?= $post['user_id'] ?>">
                    <input type="hidden" name="title" value="<?= $post['title'] ?>">
                    <input type="hidden" name="description" value="<?= $post['description'] ?>">
                    <input type="hidden" name="files">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="blog-post-meta mt-3 d-flex align-items-center">
            <p class="mb-0">Post at: <?= date('F j, Y, g:i a', strtotime($post['created'])) ?></p>
            <p class="mb-0 mx-5">Updated at: <?= date('F j, Y, g:i a', strtotime($post['updated'])) ?></p>
            <p class="mb-0 mx-5">
                <a href="#" class="d-block link-body-emphasis text-decoration-none">
                    Post By:
                    <img src="../uploads/<?= $post['avatar'] ?>"
                        alt="Post Owner Avatar" width="32" height="32" class="rounded-circle">
                    <?= $post['firstname'] . ' ' . $post['lastname'] ?>
                </a>
            </p>
        </div>
            <p class="mt-3">
                <?= nl2br($post['description']) ?>
            </p>
            <div class="row">
            <?php
            foreach ($files as $file) {
                $filename = $file['name'];
                $file_path = "../uploads/" . $file['path'];
                $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
            ?>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= $filename ?></h5>
                            <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?= $file_path ?>" alt="<?= $filename ?>" class="img-fluid">
                            <?php else: ?>
                                <a href="<?= $file_path ?>" download class="btn btn-primary">Download</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
            <div class="mt-5">
                <hr>
                <?php if ($post['category'] != 'announcement') : ?>
                    <h3>Comments</h3>
                    <?php if (empty($comments)): ?>
                    <p>No comment Yet</p>
                    <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="../uploads/<?= $comment['avatar'] ?>"
                                    alt="Avatar" class="rounded-circle" width="50" height="50">
                                <div class="mx-3">
                                    <h5 class="mb-0"><?= $comment['firstname'] . ' ' . $comment['lastname'] ?></h5>
                                    <small
                                        class="text-muted"><?= date('F j, Y, g:i a', strtotime($comment['created'])) ?>
                                    </small>
                                    <div class="align-items-right ">
                                        <?php if (($comment['user_id']) === $post['user_id']) :?><b>Post Owner</b>
                                        
                                        <?php elseif (($comment['role']) === 'professor') :?><b>Professor</b>
                                        <?php else: ?><b>Admin</b>
                                        <?php endif ?>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            <p class="mt-2"><?= nl2br($comment['comment']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                
                <form class="mt-3" id="commentForm" action="../controllers/add_comment.php" method="post">
                    <div class="form-group">
                        <label for="comment">Add a comment:</label>
                        <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                        <?php if (isset($_SESSION['comment-error'])) : ?>
                        <div class="invalid-feedback d-block" role="alert">
                            <?= $_SESSION['comment-error'];
                            unset($_SESSION['comment-error']);
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary mt-2">Comment</button>
                </form>
                <?php endif; ?>

                
            </div>


        </article>
    </div>
</main>
<?php include 'header.php';
	if ($_SESSION['user']['role'] == 'doctor') {
		$query = "SELECT p.*, 
                c.created AS latest_comment_time,
                lc.username AS latest_comment_username, 
                lc.avatar AS latest_comment_avatar, 
                COUNT(c_all.post_id) AS comment_count, 
                u.username AS post_owner_username, 
                u.avatar AS post_owner_avatar
                FROM posts p
                LEFT JOIN (
                    SELECT post_id, MAX(created) AS latest_created
                    FROM comments
                    GROUP BY post_id
                ) c_latest ON p.id = c_latest.post_id
                LEFT JOIN comments c_all ON p.id = c_all.post_id
                LEFT JOIN comments c ON c.post_id = p.id AND c.created = c_latest.latest_created
                LEFT JOIN users lc ON c.user_id = lc.id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE (p.category = 'problem' AND u.username = :username)
                GROUP BY p.id
                ORDER BY p.updated DESC";
		$stmt = $connection->prepare($query);
        $stmt->bindParam(':username', $_SESSION['user']['username']);
    } else {
		$query = "SELECT p.*,
                c.created AS latest_comment_time, 
                lc.username AS latest_comment_username, 
                lc.avatar AS latest_comment_avatar, 
                COUNT(c_all.post_id) AS comment_count, 
                u.username AS post_owner_username, 
                u.avatar AS post_owner_avatar
                FROM posts p
                LEFT JOIN (
                    SELECT post_id, MAX(created) AS latest_created
                    FROM comments
                    GROUP BY post_id
                ) c_latest ON p.id = c_latest.post_id
                LEFT JOIN comments c_all ON p.id = c_all.post_id
                LEFT JOIN comments c ON c.post_id = p.id AND c.created = c_latest.latest_created
                LEFT JOIN users lc ON c.user_id = lc.id
                LEFT JOIN users u ON p.user_id = u.id
                GROUP BY p.id
                ORDER BY p.updated DESC";
        $stmt = $connection->prepare($query);
    
    }
    $stmt->execute();
    $posts = $stmt->fetchAll();
    $announcements = [];
    $problems = [];
	
    foreach ($posts as $post) {
		if ($post['category'] == 'announcement') {
			$announcements[] = $post;
		} else {
			$problems[] = $post;
        }
    }
?>

<title>AIIH - Forum</title>

<style>
	.bd-placeholder-img {
		font-size: 1.125rem;
		text-anchor: middle;
		-webkit-user-select: none;
		-moz-user-select: none;
		user-select: none;
	}

	.bi {
		vertical-align: -.125em;
		fill: currentColor;
	}
</style>
	
<main class="container">
    <?php if ($_SESSION['user']['role'] != 'doctor') : ?>
    <div class="row mb-2">
        <div class="row mb-2">
            <div class="col-md-12">
                <h2>Announcements</h2>
            </div>
        </div>
        <?php if (count($announcements) > 0) : 
            foreach ($announcements as $post) : ?>
                <div class="col-md-12">
                    <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                        <div class="col p-4 d-flex flex-column position-static">
                            <strong class="d-inline-block mb-2 text-primary-emphasis">Announcements</strong>
                            <h3 class="mb-2"><a href="post.php?id=<?=$post['id']?>"><?=$post['title']?></a></h3>
                            <div class="d-flex justify-content-between">
                                <div class="text-body-secondary">
                                    
                                </div>
                                <div>
                                    
                                    <a href="#" class="d-block link-body-emphasis text-decoration-none">
                                        Post By:
                                        <img src="../uploads/<?= $post['post_owner_avatar'] ?>" alt="Post Owner Avatar" width="32" height="32" class="rounded-circle">
                                        <?= $post['post_owner_username'] ?>
                                        <div>
                                            Created at <?= date('F j, Y, g:i a', strtotime($post['created']) )?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-md-12">
                <p>No announcements yet.</p>
            </div>
        <?php endif; ?>
    </div>
    <hr>
    <?php endif; ?>
    <div class="row mb-2">
            <div class="col-md-12">
                <h2>Problems</h2>
            </div>
        </div>
        <div class="row mb-2">
            <?php if (count($problems) > 0) : 
                foreach ($problems as $post) : ?>
                    <div class="col-md-12">
                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 d-flex flex-column position-static">
                                <strong class="d-inline-block mb-2 text-primary-emphasis">Problems</strong>
                                <h3 class="mb-2"><a href="post.php?id=<?= $post['id'] ?>"><?=$post['title']?></a></h3>
                                <div class="d-flex justify-content-between">
                                    <div class="text-body-secondary">
                                        <?= $post['comment_count'] > 0 ? $post['comment_count'].' Comments' : 'No Comments Yet' ?>
                                        <?php if ($post['latest_comment_username']) : ?>
                                            Latest Comment By: 
                                            <img src="../uploads/<?= $post['latest_comment_avatar'] ?>" alt="Latest Commenter Avatar" width="32" height="32" class="rounded-circle">
                                            <?= $post['latest_comment_username'] ?>
                                            at <?=date('F j, Y, g:i a', strtotime($post['latest_comment_time'])) ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($_SESSION['user']['role'] != 'doctor') : ?>
                                        <div>
                                            <a href="#" class="d-block link-body-emphasis text-decoration-none">
                                                Post By:
                                                <img src="../uploads/<?= $post['post_owner_avatar'] ?>" alt="Post Owner Avatar" width="32" height="32" class="rounded-circle">
                                                <?= $post['post_owner_username'] ?>
                                                <div>
                                                Created at <?= date('F j, Y, g:i a', strtotime($post['created']) )?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-md-12">
                    <p>No problems yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>


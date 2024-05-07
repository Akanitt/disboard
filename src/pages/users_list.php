<?php
include 'header.php';

$query = "SELECT * FROM users WHERE role = 'doctor' OR role = 'professor'";
$stmt = $connection->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll();

$count_query = "SELECT COUNT(*) as count FROM users WHERE role = 'doctor' OR role = 'professor'";
$stmt = $connection->prepare($count_query);
$stmt->execute();
$count = $stmt->fetch()['count'];
?>
<link href="../assets/css/users_list.css" rel="stylesheet">
<div class="container">
    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade show active" id="pills-friends" role="tabpanel" aria-labelledby="pills-friends-tab"
            tabindex="0">
            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Doctor & Professor 
                    <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2"><?=$count?></span></h3>

            </div>
            <div class="row">
                <?php foreach ($users as $user): ?>
                <div class="col-sm-6 col-lg-4">
                <a href="profile.php?id=<?=$user['id']?>">
                    <div class="card hover-img">
                        <div class="card-body p-4 text-center border-bottom">
                            <img src="../uploads/<?=$user['avatar']?>" alt=""
                                class="rounded-circle mb-3" width="100" height="100">
                            <h5 class="fw-semibold mb-0"><?=$user['firstname']?> <?=$user['lastname']?></h5>
                            <div>
                            <span class="fs-2"><?=$user['title']?></span>
                            </div>
                            <span class="fs-2"><?=$user['department']?> <?=$user['institution']?></span>
                        </div>
                        
                    </div>
                </a>
                </div>
                <?php endforeach; ?>
        </div>

    </div>
</div>
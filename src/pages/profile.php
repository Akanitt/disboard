
<?php
    include 'header.php';
    $query = "SELECT * FROM users WHERE users.id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: users_list.php");
        exit();
    }

?>

<link href="../assets/css/profile.css" rel="stylesheet">
<title>Profile</title>
    
<section>
    <div class="container">
        <div class="row">
        <?php if ($_SESSION['user']['id'] == $user['id']): ?>
            <form action="edit_profile.php?id=<?=$user['id']?>" method="POST">
                
                <button type="submit" class="btn btn-primary">Edit Profile</button>
            </form>
        <?php endif; ?>
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div class="card card-style1 border-0">
                    <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <img src="../uploads/<?= $user['avatar'] ?>" alt="" width ="300px" >
                            
                            </div>
                            <div class="col-lg-6 px-xl-10">
                                <div class="bg-secondary d-lg-inline-block py-1-9 px-1-9 px-sm-6 mb-1-9 rounded">
                                    <h3 class="h2 text-white mb-0"><?=$user['firstname']?> <?=$user['lastname']?></h3>
                                    <span class="text-primary"><?=$user['title']?></span>
                                    
                                </div>
                                <ul class="list-unstyled mb-1-9">
                                    <li class="mb-2 mb-xl-3 display-28"><?=$user['department']?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><?=$user['institution']?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><?=$user['email']?></li>
                                    <?php if (!empty($user['url'])): ?>
                                    <li class="mb-2 mb-xl-3 display-28"><a href="<?=$user['url']?>" target="_blank">Research Website</a></li>
                                    <?php endif ; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div>
                    <span class="section-title text-primary mb-3 mb-sm-4">Interest</span>
                    <p><?=nl2br($user['interests'])?></p>
                    
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 mb-4 mb-sm-5">
                        
                        <div>
                            <span class="section-title text-primary mb-3 mb-sm-4">Education</span>
                            <p><?=nl2br($user['education'])?></p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
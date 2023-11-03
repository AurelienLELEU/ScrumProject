<?php
if (!isset($_GET['search'])) {
    $_GET['search'] = "";
}
if (!preg_match("/^[a-zA-Z0-9' \sèàéç]*$/", $_GET['search'])) { ?>
    <!--Verif d'injections dans la barre search-->
    <script>
        alert("Lettres ou espaces uniquement!")
    </script>
    <?php $_GET['search'] = "";
}

include("./classloader.php");

if ($_POST) { // creation du commentaire
    if ($_POST['type'] == 'com') {
        $donnees = [
            "id_post" => $_POST["post_id"],
            "content" => $_POST["content"],
            "created_at" => date("Y-m-d H:i"),
            "user_name" => $_SESSION['username'],
            "user_id" => $_SESSION['id']
        ];
        $commentairesManager->add(new Commentaire($donnees));
    }
    if ($_POST['type'] == 'likeadd') {
        if ($dislikesManager->getPrecDislike($_POST["post_id"], $_SESSION['id'])) {
            $dislikesManager->delete($_POST["post_id"], $_SESSION['id']);
        }
        $donnees = [
            "post_id" => $_POST["post_id"],
            "liker_id" => intval($_SESSION['id']),
            "user_id" => $postsManager->get($_POST["post_id"])->getUser_id()
        ];
        $likesManager->add(new Like($donnees));
    }
    if ($_POST['type'] == 'likedel') {
        $donnees = [
            "post_id" => $_POST["post_id"],
            "liker_id" => intval($_SESSION['id'])
        ];
        $likesManager->delete($_POST["post_id"], $_SESSION['id']);
    }
    if ($_POST['type'] == 'dislikeadd') {
        if ($likesManager->getPrecLike($_POST["post_id"], $_SESSION['id'])) {
            $likesManager->delete($_POST["post_id"], $_SESSION['id']);
        }
        $donnees = [
            "post_id" => $_POST["post_id"],
            "disliker_id" => intval($_SESSION['id']),
            "user_id" => $postsManager->get($_POST["post_id"])->getUser_id()
        ];
        $dislikesManager->add(new Dislike($donnees));
    }
    if ($_POST['type'] == 'dislikedel') {
        $donnees = [
            "post_id" => $_POST["post_id"],
            "liker_id" => intval($_SESSION['id'])
        ];
        $dislikesManager->delete($_POST["post_id"], $_SESSION['id']);
    }
    if ($_POST['type'] == 'update') {
        $donnees = [
            "id" => $_POST["post_id"],
            "content" => $_POST["content"],
            "created_at" => date("Y-m-d H:i:s"),
            "user_id" => $_SESSION['id'],
            "user_name" => $_SESSION['username']
        ];
        $postsManager->update(new Post($donnees));
    }
    if ($_POST['type'] == 'create') {
        $valid = True;
        $_POST["content"] = "https://" . $_POST["content"];
        var_dump($_POST["content"]);
        $donnees = [
            "content" => $_POST["content"],
            "created_at" => date("Y-m-d H:i"),
            "user_id" => $_SESSION['id'], //recupere l'username du user connecté
            "user_name" => $usersManager->getUsername($_SESSION['id'])
        ];
        if (!isset($donnees["content"])) {
            var_dump($donnees["content"]);
            $valid = False;
    ?>
            <script>
                alert("Merci de mettre du contenu!")
            </script>
<?php
        }
        if ($valid) { // si tout est niquel ca cré l'post
            if (strpos(strtolower($_SESSION["username"]), "chris") !== true) {
                $postsManager->add(new Post($donnees));
            }
            echo "<script>window.location.href='index.php'</script>";
        }
    }
} ?>

<body class="color">
    <div class="container-fluid d-flex justify-content-center align-items-center bg-white border nav-bar sticky-top nav-color">
        <div class="navglobal d-flex container-fluid flex-wrap justify-content-between align-items-center">

            <div class="mx-auto navdiv container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="./pic-required/navinsta.png" alt="navinsta.png" height="50px">
                </a>
            </div>

            <div class="navdiv container-fluid">
                <form class="d-none d-md-flex" role="search">
                    <input class="form-control me-2 border-none navdiv color-search" id="search" name="search" type="search" placeholder="<?php if ($_GET['search'] == '') {
                                                                                                                                                echo ("Search");
                                                                                                                                            } else {
                                                                                                                                                echo ($_GET['search']);
                                                                                                                                            } ?>">
                </form>
            </div>

            <div class="my-auto navdiv container-fluid">
                <ul class="container-fluid d-flex align-items-center justify-content-center mb-0">
                    <li class="btn p-0">
                        <a class="nav-link text-dark" href="index.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                                <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
                            </svg>
                        </a>
                    </li>

                    <li class="btn p-0">
                        <a class="nav-link text-dark" href="index.php" data-toggle="modal" data-target="<?php if (isset($_SESSION)) {
                                                                                                            if (isset($_SESSION["username"])) { ?> #staticBackdropCreate <?php } else { ?> #PleaseLoginFirst <?php }
                                                                                                                                                                                                        } ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                            </svg>
                        </a>
                    </li>

                    <?php if (isset($_SESSION)) {
                        if (isset($_SESSION["username"])) { ?>
                            <li class="btn p-0">
                                <div class="dropdown">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                        </svg>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item" href="myprofile.php">My Profile</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            </li>
                        <?php } else { ?>
                            <div class="mx-auto navdiv container-fluid">
                                <a class="btn" href="login.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                </a>
                            </div>
                    <?php }
                    } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid d-flex justify-content-center border-none mt-4">
        <div class="d-flex justify-content-center flex-column align-items-center mx-3">
            <div class="modal fade" id="PleaseLoginFirst" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-body d-flex flex-column modal-height justify-content-center align-items-center w-100 mx-4 p-4">
                            <p class="fs-1"> Please, Login first! </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="staticBackdropCreate" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content bg-white border-0">
                        <form method="post" enctype="multipart/form-data">
                            <div class="modal-header bg-white justify-content-between p-0">
                                <div class="w-25"></div> <!-- c'est de la triche je sais mais au moins ca marche! -->
                                <div class="w-25 p-2 fw-bold d-inline-flex justify-content-center">
                                    Signaler un site web
                                </div>
                                <div class="d-inline-flex">
                                    <input type="submit" value="Signaler" class="btn btn-outline-primary border-0 m-3">
                                </div>
                            </div>
                            <div class="modal-body d-flex flex-column justify-content-center modal-height w-100 p-4">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">https://</span>
                                    <input type="text" class="form-control" aria-describedby="basic-addon3 basic-addon4" name="content" id="content" placeholder="Site à signaler">
                                    <input type="hidden" name="type" id="type" value="create">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            foreach ($postsManager->getAll($_GET['search']) as $post) { //pareil
            ?>
                <div class="modal fade" id="staticBackdrop<?php echo ($post->getId()); ?>" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body d-flex justify-content-center p-0">
                                <div class="d-flex flex-column w-50 modal-height">
                                    <div class="card-body bg-white">
                                        <p class="card-text m-0">
                                            <b><?php echo ($usersManager->getUsername($post->getUser_id()) . ' ') ?></b><?= $post->getContent() ?>
                                        </p>
                                        <small class="text-muted"><?= $post->getCreated_at() ?> </small>
                                        <div class="d-flex flex-column justify-content-between overflow-auto com-section">
                                            <?php foreach ($commentairesManager->getAll() as $commentaire) {
                                                if ($commentaire->getId_post() == $post->getId()) { ?>
                                                    <div class="d-flex">
                                                        <div class="card-body">
                                                            <p class="card-text my-0"> <b><?php echo ($commentaire->getUser_name() . ' ') ?></b><?= $commentaire->getContent() ?></p>
                                                            <small class="text-muted"><?= $commentaire->getCreated_at() ?></small>
                                                        </div>
                                                        <div class="p-3">
                                                            <?php if (isset($_SESSION["username"])) {
                                                                if ($_SESSION["username"] == $post->getUser_name() || $_SESSION["username"] == $commentaire->getUser_name()) { ?>
                                                                    <a class="dropdown-item" href="delete_comment.php?id=<?= $commentaire->getId() ?>">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                                        </svg>
                                                                    </a>

                                                            <?php }
                                                            } ?>
                                                        </div>
                                                    </div>
                                            <?php }
                                            } ?>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="staticBackdropup<?php echo ($post->getId()); ?>" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content bg-transparent border-0">
                            <form method="post">
                                <div class="modal-header bg-white justify-content-between p-0">
                                    <div></div>
                                    <div>Modifier les Infos</div>
                                    <button type="submit" class="btn"> Terminé </button>
                                </div>
                                <div class="modal-body d-flex justify-content-center p-0">
                                    <div class="d-flex flex-column w-50 modal-height">
                                        <div class="card-header bg-white d-flex align-items-center justify-content-between border-0">
                                            <?php if ($post->getUser_id() != NULL) {
                                                echo $usersManager->getUsername($post->getUser_id());
                                            } ?>
                                        </div>
                                        <div class="card-body bg-white py-0 ">
                                            <textarea class="form-control border-0" name="content" id="content" placeholder="Contenu du post" required><?= $post->getContent() ?></textarea>
                                            <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                            <input type="hidden" name="type" id="type" value="update">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 d-flex justify-content-center">
                    <div class="card-body pt-0">
                        <p class="mb-1 card-text fw-bold cash"> <?= $post->getContent() ?> </p>
                        <?php if (isset($_SESSION)) {
                            if (isset($_SESSION["username"])) { ?>
                                <div class="container-fluid d-flex img-footer bd-highlight p-0">
                                    <form method="post">
                                        <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                        <?php if ($likesManager->getPrecLike($post->getId(), $_SESSION['id'])) { ?>
                                            <input type="hidden" name="type" id="type" value="likedel">
                                            <button type="submit" class="btn p-2">
                                                <img src="./pic-required/up_vote.png" alt="upvote" height="20px">
                                            </button>
                                        <?php } else { ?>
                                            <input type="hidden" name="type" id="type" value="likeadd">
                                            <button type="submit" class="btn p-2">
                                                <img src="./pic-required/base_vote.png" alt="upvote" height="20px">
                                            </button>
                                        <?php } ?>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                        <?php if ($dislikesManager->getPrecDislike($post->getId(), $_SESSION['id'])) { ?>
                                            <input type="hidden" name="type" id="type" value="dislikedel">
                                            <button type="submit" class="btn p-2">
                                                <img src="./pic-required/down_vote.png" alt="downvote" height="20px">
                                            </button>
                                        <?php } else { ?>
                                            <input type="hidden" name="type" id="type" value="dislikeadd">
                                            <button type="submit" class="btn p-2">
                                                <img src="./pic-required/down_base_vote.png" alt="downvote" height="20px">
                                            </button>
                                        <?php } ?>
                                    </form>
                                </div>
                                <?php foreach ($commentairesManager->getAll() as $commentaire) {
                                    if ($commentaire->getId_post() == $post->getId()) { ?>
                                        <div class="d-flex">
                                            <div class="card-body">
                                                <p class="card-text my-0"> <b><?php echo ($commentaire->getUser_name() . ' ') ?></b><?= $commentaire->getContent() ?></p>
                                                <small class="text-muted"><?= $commentaire->getCreated_at() ?></small>
                                            </div>
                                            <div class="p-3">
                                                <?php if (isset($_SESSION["username"])) {
                                                    if ($_SESSION["username"] == $post->getUser_name() || $_SESSION["username"] == $commentaire->getUser_name()) { ?>
                                                        <a class="dropdown-item" href="delete_comment.php?id=<?= $commentaire->getId() ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                            </svg>
                                                        </a>

                                                <?php }
                                                } ?>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                                <form method="post" class="bg-white card-footer">
                                    <div class="content">
                                        <div class="col-auto">
                                            <input class="form-control" name="content" id="content" placeholder="Ajouter un commentaire...">
                                            <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                            <input type="hidden" name="type" id="type" value="com">
                                        </div>
                                    </div>
                                </form>
                        <?php }
                        } ?>
                        <p class="mb-1 card-text"> <?= $likesManager->countRelated($post->getId())[0][0] ?> Avis fiable, <?= $dislikesManager->countRelated($post->getId())[0][0] ?> Avis pas fiable</p>

                        <small class="text-muted"> <?= $post->getCreated_at() ?> </small>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="d-none d-md-flex flex-column recom-container">
            <div>
                <?php
                foreach ($usersManager->getAll() as $user) { ?>
                    <div>
                        <?php echo ($user->getUsername()) ?>
                    </div>
                    <div class="signup-text mx-5 mb-2 signup-bottom">
                        <?php echo ($user->getFirst_name()) ?>
                        <?php echo ($user->getLast_name()) ?>
                    </div>
                <?php } ?>
            </div>
            <footer class="d-flex justify-content-center">© 2023 SafetyCASH par Les Casheurs</footer>
        </div>
    </div>
</body>
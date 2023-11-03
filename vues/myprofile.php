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
if (empty($_SESSION)) {
    echo "<script>window.location.href='./index.php'</script>";
}

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
        $id_image = NULL;
        $valid = True;

        if (!is_dir("upload/")) { // verif si le dossier "upload" existe deja, sinnon il le cré
            mkdir("upload/");
        }



        $fileName = $_FILES["image"]["name"]; //nommage et placement de l'image dans le fichier "upload"
        $targetFile = "upload/$fileName" . date("Y-m-d-H-i-s") . $_SESSION['id'];
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagesManager->add(new Image(["name" => $fileName . date("Y-m-d-H-i-s") . $_SESSION['id'], "image" => $targetFile])); //ajout des images dans la base de données
            $id_image = $imagesManager->getLastImageId(); //recup l'id de l'image pour le setup à la creation de l'post
        }
        $donnees = [
            "id_image" => $id_image,
            "content" => $_POST["content"],
            "created_at" => date("Y-m-d H:i"),
            "user_id" => $_SESSION['id'], //recupere l'username du user connecté
            "user_name" => $usersManager->getUsername($_SESSION['id'])
        ];
        var_dump($fileName);
        var_dump($donnees["content"]);
        if (!isset($fileName)) {
            var_dump($fileName);
            $valid = False;
    ?>
            <script>
                alert("Merci de joindre une photo!")
            </script>
        <?php
        }

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
    <div class="container-fluid d-flex justify-content-center align-items-center bg-white border nav-bar sticky-top">
        <div class="navglobal d-flex container-fluid flex-wrap justify-content-between align-items-center">

            <div class="mx-auto navdiv container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="./pic-required/navinsta.png" alt="navinsta.png">
                </a>
            </div>

            <div class="navdiv container-fluid">
                <form class="d-none d-md-flex" role="search">
                    <input class="form-control me-2 border-none navdiv color-search" id="search" name="search" type="search" placeholder="<?php if ($_GET['search'] == '') {
                                                                                                                                                echo ("Search");
                                                                                                                                            } else {
                                                                                                                                                echo ($_GET['search']);
                                                                                                                                            }
                                                                                                                                            ?>">
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
                        <button type="button" class="nav-link text-dark border-0 bg-white" data-toggle="modal" data-target="<?php if (isset($_SESSION)) {
                                                                                                                                if (isset($_SESSION["username"])) { ?> #staticBackdropCreate <?php } else { ?> #PleaseLoginFirst <?php }
                                                                                                                                                                                                                            } ?>">
                            <svg aria-label="Nouvelle publication" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                <path d="M2 12v3.45c0 2.849.698 4.005 1.606 4.944.94.909 2.098 1.608 4.946 1.608h6.896c2.848 0 4.006-.7 4.946-1.608C21.302 19.455 22 18.3 22 15.45V8.552c0-2.849-.698-4.006-1.606-4.945C19.454 2.7 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.547 2 5.703 2 8.552Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="6.545" x2="17.455" y1="12.001" y2="12.001"></line>
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="12.003" x2="12.003" y1="6.545" y2="17.455"></line>
                            </svg>
                        </button>
                    </li>

                    <?php if (isset($_SESSION)) {
                        if (isset($_SESSION["username"])) { ?>
                            <li class="btn p-0">
                                <div class="dropdown">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg aria-label="J’aime" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                            <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"></path>
                                        </svg>
                                    </a>
                                    <ul class="dropdown-menu dropdown-resized container-fluid" aria-labelledby="dropdownMenuLink">
                                        <a class="btn m-0 p-0 dropdown-resized" href="myprofile.php">
                                            <li class="com-section">
                                                <?php foreach ($likesManager->getRelated($_SESSION['id']) as $like) { ?>
                                                    <div class="com-section d-flex justify-content-between mb-1">
                                                        <div class="mx-2 d-flex align-items-center">
                                                            <?php echo ($usersManager->get($like->getLiker_id())->getUsername()); ?> has liked your Photo
                                                        </div>
                                                        <div class=" mx-2 d-flex">
                                                            <img class="card-img-top img-resized" src="<?= $imagesManager->get($postsManager->get($like->getPost_id())->getId_image())->getImage() ?>" alt="Card image cap">
                                                        </div>
                                                    </div>
                                                <?php }
                                                if (empty($likesManager->getRelated($_SESSION['id']))) {
                                                    echo ("No Likes Yet!");
                                                } ?>
                                            </li>
                                        </a>
                                    </ul>
                                </div>
                            </li>
                        <? } else { ?>
                            <button type="button" class="nav-link text-dark border-0 bg-white" data-toggle="modal" data-target=" #PleaseLoginFirst">
                                <svg aria-label="J’aime" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                    <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"></path>
                                </svg>
                            </button>
                    <?php }
                    } ?>

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
                        <? } else { ?>
                            <li class="btn p-0">
                                <a class="nav-link text-dark" href="login.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                </a>
                            </li>
                    <?php }
                    } ?>
                </ul>
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
                            Créer une publication
                        </div>
                        <div class="d-inline-flex">
                            <input type="submit" value="Créer le Post" class="btn btn-outline-primary border-0 m-3">
                        </div>
                    </div>
                    <div class="modal-body d-flex flex-column justify-content-center modal-height w-100 p-4">
                        <input type="file" class="form-control form-control-lg" name="image" id="image">
                        <div class="content col-auto">
                            <textarea class="form-control mt-3" name="content" id="content" placeholder="Contenu de l'Post"></textarea>
                            <input type="hidden" name="type" id="type" value="create">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mx-auto navglobal">
        <div class="d-flex flex-column align-items-center justify-content-center pb-3 border-bottom">
            <p class="fs-4 m-0 d-flex"><?= $usersManager->get($_SESSION['id'])->getUsername(); ?></p>
            <div class="d-flex justify-ccontent-center">
                <div class="mx-2">
                    <p class="d-flex m-0"><?php $number = $postsManager->countRelated($_SESSION['id'])[0][0];
                                            if ($number <= 1) {
                                                echo ('<b class="px-2">' . $number . "</b> Publication");
                                            } else {
                                                echo ('<b class="px-2">' . $number . "</b> Publications");
                                            } ?>
                    </p>
                </div>
                <div class="mx-2">
                    <p class="d-flex m-0"><?php $likesnumber = $likesManager->countUserRelated($_SESSION['id'])[0][0];
                                            if ($likesnumber <= 1) {
                                                echo ('<b class="px-2">' . $likesnumber . "</b> Like");
                                            } else {
                                                echo ('<b class="px-2">' . $likesnumber . "</b> Likes");
                                            } ?>
                    </p>
                </div>
            </div>
            <div>
                <p class="fw-bold d-flex m-0">
                    <?= $usersManager->get($_SESSION['id'])->getFirst_name(); ?> <?= $usersManager->get($_SESSION['id'])->getLast_name(); ?>
                </p>
            </div>
        </div>

        <div class="mt-3 d-flex flex-wrap container-fluid">
            <?php
            foreach ($postsManager->getRelated($_GET['search'], $_SESSION['id']) as $post) { //pareil
            ?>
                <div class="modal fade" id="staticBackdrop<?php echo ($post->getId()); ?>" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body d-flex justify-content-center p-0">
                                <div class="d-flex justify-content-center align-items-center bg-black w-50">
                                    <?php if ($post->getId_image()) { ?>
                                        <img class="card-img-top modal-height" src="<?= $imagesManager->get($post->getId_image())->getImage() ?>" alt="Card image cap">
                                    <?php } ?>
                                </div>
                                <div class="d-flex flex-column w-50 modal-height">
                                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                        <?php if ($post->getUser_id() != NULL) {
                                            echo $usersManager->getUsername($post->getUser_id());
                                        } ?>
                                        <div>
                                            <button class="btn btn-light btn-lg bg-white border-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                ...
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">
                                                        Signaler
                                                    </a>
                                                </li>
                                                <?php if (isset($_SESSION["username"])) {
                                                    if ($_SESSION["username"] == $post->getUser_name()) { ?>
                                                        <li>
                                                            <a class="dropdown-item" href="update.php?id=<?php echo ($post->getId()); ?>">
                                                                Modifier le Post
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="delete.php?id=<?= $post->getId() ?>">
                                                                Supprimer le Post
                                                            </a>
                                                        </li>
                                                <?php }
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
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
                                    <?php if (isset($_SESSION)) {
                                        if (isset($_SESSION["username"])) { ?>
                                            <form method="post" class="bg-white card-footer">
                                                <div class="content">
                                                    <div class="col-auto">
                                                        <input class="form-control" name="content" id="content" placeholder="Ajouter un commentaire...">
                                                        <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                                        <input type="hidden" name="type" id="type" value="com">
                                                    </div>
                                                </div>
                                            </form>
                                    <?php  }
                                    } ?>
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
                                    <div class="d-flex align-items-center bg-black w-50 color px-5">
                                        <?php if ($post->getId_image()) { ?>
                                            <img class="card-img-top modal-height" src="<?= $imagesManager->get($post->getId_image())->getImage() ?>" alt="Card image cap">
                                        <?php } ?>
                                    </div>
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
                <div class="card card-myprofile mb-3 mx-2">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <?php if ($post->getUser_id() != NULL) {
                            echo $usersManager->getUsername($post->getUser_id());
                        } ?>

                        <button class="btn btn-light btn-lg bg-white border-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ...
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">
                                    Signaler
                                </a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-white" data-toggle="modal" data-target="#staticBackdrop<?php echo ($post->getId()); ?>">
                                    Accéder à la publication
                                </button>
                            </li>
                            <?php if (isset($_SESSION)) {
                                if (isset($_SESSION["username"])) {
                                    if ($_SESSION["username"] == $post->getUser_name()) { ?>
                                        <li>
                                            <button type="button" class="btn btn-white" data-toggle="modal" data-target="#staticBackdropup<?php echo ($post->getId()); ?>">
                                                Modifier la Publication
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="delete.php?id=<?= $post->getId() ?>">
                                                Supprimer le Post
                                            </a>
                                        </li>
                            <?php }
                                }
                            } ?>
                        </ul>
                    </div>
                    <?php if ($post->getId_image()) { ?>
                        <img class="card-img-top" src="<?= $imagesManager->get($post->getId_image())->getImage() ?>" alt="Card image cap">
                    <?php } ?>
                    <?php if (isset($_SESSION)) {
                        if (isset($_SESSION["username"])) { ?>
                            <div class="d-flex img-footer bd-highlight p-0">
                                <form method="post">
                                    <input type="hidden" name="post_id" id="post_id" value="<?php echo ($post->getId()); ?>">
                                    <?php if ($likesManager->getPrecLike($post->getId(), $_SESSION['id'])) { ?>
                                        <input type="hidden" name="type" id="type" value="likedel">
                                        <button type="submit" class="btn p-2">
                                            <svg aria-label="Je n’aime plus" class="_ab6-" color="#ed4956" fill="#ed4956" height="24" role="img" viewBox="0 0 48 48" width="24">
                                                <path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>
                                            </svg>
                                        </button>
                                    <?php } else { ?>
                                        <input type="hidden" name="type" id="type" value="likeadd">
                                        <button type="submit" class="btn p-2">
                                            <svg aria-label="J’aime" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                                <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"></path>
                                            </svg>
                                        </button>
                                    <?php } ?>
                                </form>
                                <div>
                                    <button type="button" class="btn btn-white p-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo ($post->getId()); ?>">
                                        <svg aria-label="Commenter" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                            <path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div>
                                    <a class="btn btn-white p-2" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">
                                        <svg aria-label="Enregistrer" class="_ab6-" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                            <polygon fill="none" points="20 21 12 13.44 4 21 4 3 20 3 20 21" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polygon>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                    <?php }
                    } ?>
                    <div class="card-body pt-0">
                        <p class="mb-1 card-text fw-bold"> <?= $likesManager->countRelated($post->getId())[0][0] ?> J'aime </p>
                        <p class="mb-1 card-text"> <?= $post->getContent() ?> </p>
                        <small class="text-muted"> <?= $post->getCreated_at() ?> </small>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
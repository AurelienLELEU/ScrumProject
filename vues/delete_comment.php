<?php
include("./classloader.php");
// rien de compliqué, ca delete l'id passé en get ou en post, ca verifie que l'user qui veut delete un commentaire est bien l'auteur de ce dernier

if ($_GET) {
    if ($_SESSION["username"] == $postsManager->get($commentairesManager->get($_GET['id'])->getId_post())->getUser_name() || $_SESSION["username"] == $commentairesManager->get($_GET['id'])->getUser_name() || $_SESSION["username"] == "admin") {
        $commentairesManager->delete($_GET['id']);
    }
}
echo "<script>window.location.href='index.php'</script>";

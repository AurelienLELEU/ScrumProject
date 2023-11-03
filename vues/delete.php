<?php
include("./classloader.php");
// pareil que delete_comment.php sauf que la c'est pour les articles, rien de compliqué, ca verifie que l'user qui veut delete un article est bien l'auteur de ce dernier 

if ($_POST) {
    if ($_SESSION["username"] == $usersManager->getUsername($postsManager->get($_POST['id'])->getUser_id()) || $_SESSION["username"] == "admin") {
        $postsManager->delete($_POST['id']);
    }
}
if ($_GET['id']) {
    if ($_SESSION["username"] == $usersManager->getUsername($postsManager->get($_GET['id'])->getUser_id()) || $_SESSION["username"] == "admin") {
        $postsManager->delete($_GET['id']);
    }
}
echo "<script>window.location.href='index.php'</script>";

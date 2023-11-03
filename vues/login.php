<?php
$_GET['page'] = "none";
include("./classloader.php");

if ($_POST) { //verification des infos de login
    $userData = [
        "password" => $_POST['password'],
        "email" => $_POST['email']
    ];
    $email = True;
    $alert = False;
    $existingAccount = False;

    if (!$userData['email']) { ?>
        <!-- pas pratique de se co sans mail -->
        <script>
            alert("Email is required")
        </script>
    <?php $email = False;
        $alert = True;
    }

    if ((!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) && ($email)) { ?>
        <!-- verifie que le mail join est bien un mail (et pas une injection (au hasard)) -->
        <script>
            alert("Invalid email format")
        </script>
    <?php $alert = True;
    }

    foreach ($usersManager->getAll() as $user) { // recup tous les utilisateurs et verifie qu'il en existe bien un avec le meme mdp et mail
        if ($user->getEmail() == $userData['email'] && $existingAccount == False) {
            if (password_verify($userData['password'], $user->getPassword())) {
                $existingAccount = True;
            }
            break;
        }
    }

    if ($existingAccount) { // si l'user existe et que le mdp est bon, on setup la $_SESSION et on retourne au index.php
        $_SESSION["username"] = $user->getUsername();
        $_SESSION["id"] = $user->getId();
        echo "<script>window.location.href= './index.php'</script>";
    } else if (!$alert) { ?>
        <!-- si un truc est faux, on le signal à l'utilisateur et il reessaie -->
        <script>
            alert("Email or Password is incorrect!")
        </script>
<?php }
}
?>

<body class="color">
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <form method="post" class="mx-3">
            <div class="form mb-2 border mt-3">
                <img src="./pic-required/navinsta.png" alt="instaccueil2.png" width="300px">
                <div class="d-flex flex-column align-items-center">
                    <div class="input container-fluid mb-1">
                        <input type="text" class="form-control color" name="email" id="email" maxlength="50" placeholder="Email" required />
                    </div>
                    <div class="input container-fluid mb-1">
                        <input type="password" class="form-control color" name="password" id="password" maxlength="50" placeholder="Mot de passe" required />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary input mb-2">Log in</button>
                    </div>
                </div>
            </div>
            <div class="form border mt-3 py-3 text-center">
                Vous n'avez pas de compte?
                <a href="signup.php" name="signup" id="signup" class="text-decoration-none ">Inscrivez-vous</a>
            </div>
        </form>
    </div>
    <footer class="container-fluid d-flex justify-content-center real-footer signup-text">© 2023 SafetyCASH par LeGodChap</footer>
</body>
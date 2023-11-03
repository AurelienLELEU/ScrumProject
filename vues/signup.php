<?php
$_GET['page'] = "none";
include("./classloader.php");
if ($_POST) { // recuperation et verification des données et creation du compte
    $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userData = [
        "first_name" => $_POST['first_name'],
        "last_name" => $_POST['last_name'],
        "username" => $_POST['username'],
        "password" => $_POST['password'],
        "email" => $_POST['email']
    ];
    $existingAccount = False;

    $users = $usersManager->getAll();

    if (empty($userData['username'])) { ?>
        <!-- verifie que la case a bien été saisie -->
        <script>
            alert("Name is required")
        </script>
    <?php $existingAccount = True;
    }
    if (!preg_match("/^[a-zA-Z-'.éè ]*$/", $userData['username'])) { ?>
        <!-- empeche les injections -->
        <script>
            alert("Only letters and white spaces allowed")
        </script>
    <?php $existingAccount = True;
    }
    if (empty($userData['first_name'])) { ?>
        <!-- verifie que la case a bien été saisie -->
        <script>
            alert("First Name is required")
        </script>
    <?php $existingAccount = True;
    }
    if (!preg_match("/^[a-zA-Z-'.éè ]*$/", $userData['first_name'])) { ?>
        <!-- empeche les injections -->
        <script>
            alert("Only letters and white spaces allowed")
        </script>
    <?php $existingAccount = True;
    }
    if (empty($userData['last_name'])) { ?>
        <!-- verifie que la case a bien été saisie -->
        <script>
            alert("Last Name is required")
        </script>
    <?php $existingAccount = True;
    }
    if (!preg_match("/^[a-zA-Z-'.éè ]*$/", $userData['last_name'])) { ?>
        <!-- empeche les injections -->
        <script>
            alert("Only letters and white spaces allowed")
        </script>
    <?php $existingAccount = True;
    }
    if (empty($userData['email'])) { ?>
        <!-- verifie que la case a bien été saisie -->
        <script>
            alert("Email is required")
        </script>
    <?php $existingAccount = True;
    }
    if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) { ?>
        <!-- empeche les injections -->
        <script>
            alert("Invalid email format")
        </script>
    <?php $existingAccount = True;
    }
    if (empty($userData['password'])) { ?>
        <!-- verifie que la case a bien été saisie -->
        <script>
            alert("Password is required")
        </script>
        <?php $existingAccount = True;
    }
    foreach ($users as $user) { // verifie que l'username existe pas deja
        if ($user->getUsername() == $userData['username']) { ?>
            <script>
                alert("This username is already taken !")
            </script>
        <?php $existingAccount = True;
            break;
        }
        if ($user->getEmail() == $userData['email'] && $existingAccount == False) { ?>
            <script>
                alert("This email address is already taken !")
            </script>
<?php $existingAccount = True;
            break;
        }
    }
    if ($existingAccount == False) { // si le compte existe pas deja, on le cré
        $usertoadd = new User($userData);
        $usersManager->add($usertoadd);
        session_destroy();
        echo "<script>window.location.href='login.php'</script>";
    }
}
?>

<body class="color">
    <!-- vous connaissez la chanson... -->
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <form method="post" class="mx-3">
            <div class="form mb-2 border mt-3">
                <!-- <div class="container-fluid d-flex flex-column justify-content-center align-items-center"> -->
                <!-- <div class="form border mb-2 mt-3"> -->
                <!-- <form method="post" class="mx-3"> -->
                <img src="./pic-required/instaccueil2.png" width="300PX">
                <div class="d-flex flex-column align-items-center">
                    <!-- <div class="font-weight-bold d-flex flex-wrap justify-content-center text-center signup-text mx-5 mb-2">
                        Inscrivez-vous pour pouvoir contribuer et signaler des sites malveillants.
                    </div> -->
                    <div class="input mb-1">
                        <input type="text" class="form-control color" name="first_name" id="first_name" placeholder="Prénom" maxlength="50" required />
                    </div>
                    <div class="input mb-1">
                        <input type="text" class="form-control color" name="last_name" id="last_name" placeholder="Nom" maxlength="50" required />
                    </div>
                    <div class="input mb-1">
                        <input type="text" class="form-control color" name="username" id="username" placeholder="Username" maxlength="50" required />
                    </div>
                    <div class="input mb-1">
                        <input type="text" class="form-control color" name="email" id="email" placeholder="Email" maxlength="50" required />
                    </div>
                    <div class="input mb-1">
                        <input type="password" class="form-control color" name="password" id="password" placeholder="Mot de passe" maxlength="50" required />
                    </div>
                    <div class="input mb-1">
                        <button type="submit" class="btn btn-primary input mb-2">Sign up</button>
                    </div>
                </div>
            </div>
            <div class="form border mt-3 py-3 text-center">
                Vous avez un compte ?
                <a href="login.php" name="login" id="login" class="text-decoration-none ">Connectez-vous</a>
            </div>
        </form>
        <!-- </div> -->
        <!-- <div class="form mt-3 mb-2 border text-center py-3"> -->

    </div>
    <footer class="container-fluid d-flex justify-content-center real-footer signup-text">© 2022 Instagram par LeGodChap</footer>
</body>
<?php
session_start();
require_once "Storage.php";

$users_storage = new Storage(new JsonIO('users.json'));
$errors = [];

if ($_POST) {
    if (empty($_POST['name'])) {
        $errors['name'] = "A felhasználónév megadása kötelező.";
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "A jelszó megadása kötelező.";
    }

    $user = $users_storage->findOne(['name' => $_POST['name']]);
    if ($user && $_POST['password']== $user['password']) {
        $_SESSION['name'] =$_POST['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['money'] =$user['money'];
        $_SESSION['permission'] = $user['permission'];
        $_SESSION['cards'] = $user['cards'];
        header("Location: index.php?login=OK");
    }elseif(empty($errors)){
        $errors['name']="Sikertelen bejelentkezés! Hibás név/jelszó.";
    }
}

$users = $users_storage->findAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Login
    </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/register.css">
    <link rel="shortcut icon" href="https://assets.pokemon.com/static2/_ui/img/favicon.ico">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Login</h1>
    </header>
    <div id="content">
        <div class="register-card">

            <form action="login.php?id=user<?= count($users); ?>" method="post">
                <label for="name">Username:</label>
                <input type="text" id="name" name="name"><br>
                <span style="color: red;">
                    <?= isset($errors['name']) ? $errors['name'] . '<br>' : "" ?>
                </span>

                <label for="password">Password:</label>&nbsp;
                <input type="password" id="password" name="password"><br>
                <span style="color: red;">
                    <?= isset($errors['password']) ? $errors['password'] . '<br>' : "" ?>
                </span>

                <input class="submit" type="submit" value="Login">
            </form>

        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
<?php
require_once "Storage.php";

$users_storage = new Storage(new JsonIO('users.json'));
$errors = [];

$data=[];


if ($_POST) {
    if (empty($_POST['name'])) {
        $errors['name'] = "A felhasználónév megadása kötelező.";
    }else if ($users_storage->findOne(['name' => $_POST['name']])) {
        $errors['name'] = "Ez a felhasználónév már foglalt.";
    }else{
        $data['name']=$_POST['name'];
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Az e-mail cím megadása kötelező.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Az e-mail cím formátuma érvénytelen.";
    }else if ($users_storage->findOne(['email' => $_POST['email']])) {
        $errors['email'] = "Ez az email cim már foglalt.";
    }else{
        $data['email']=$_POST['email'];
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "A jelszó megadása kötelező.";
    } else if ($_POST['password'] != $_POST['passwordagain']) {
        $errors['password'] = "A két jelszó nem egyezik meg.";
    }else{
        $data['password']=$_POST['password'];
        $data['passwordagain']=$_POST['passwordagain'];
    }

    if (empty($errors)) {
        $users_storage->add($_POST['name'], [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "money" => 879,
            "permission" => 1,
            "cards" => []
        ]);
        header("Location: index.php?registration=OK");
    }
}




$users = $users_storage->findAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Register
    </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/register.css">
    <link rel="shortcut icon" href="https://assets.pokemon.com/static2/_ui/img/favicon.ico">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Register</h1>
    </header>
    <div id="content">
        <div class="register-card">

            <form action="register.php" method="post">
                <label for="name">Username:</label>
                <input type="text" id="name" name="name" value="<?= isset($data['name']) ? $data['name'] : "" ?>"><br>
                <span style="color: red;"><?= isset($errors['name']) ? $errors['name']. '<br>': "" ?></span>

                <label for="email">E-mail:</label>&nbsp;&nbsp;&nbsp;&emsp;
                <input type="email" id="email" name="email" value="<?= isset($data['email']) ? $data['email'] : "" ?>"><br>
                <span style="color: red;"><?= isset($errors['email']) ? $errors['email'] . '<br>': "" ?></span>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?= isset($data['password']) ? $data['password'] : "" ?>"><br>
                <label for="passwordagain">Password:</label>
                <input type="password" id="passwordagain" name="passwordagain" value="<?= isset($data['passwordagain']) ? $data['passwordagain'] : "" ?>"><br>
                <span style="color: red;"><?= isset($errors['password']) ? $errors['password'] . '<br>': "" ?></span>

                <input class="submit" type="submit" value="Registration">
            </form>

        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
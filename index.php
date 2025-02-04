<?php
session_start();
require_once "Storage.php";

$errors = [];

$users_storage = new Storage(new JsonIO('users.json'));
$cards_storage = new Storage(new JsonIO('cards.json'));

$admin_user = $users_storage->findOne(['name' => 'admin']);

$randomKey = array_rand($admin_user['cards']);
$randomCard = $admin_user['cards'][$randomKey];

if ($_POST && isset($_GET['id'])) {
    $cardId = $_GET['id'];

    if (empty($_POST['image'])) {
        $errors['image'] = "A kép megadása kötelező.";
    }
    if (empty($_POST['name'])) {
        $errors['name'] = "A pokémon nevének megadása kötelező.";
    }
    if (empty($_POST['type'])) {
        $errors['type'] = "A pokémon típusának megadása kötelező.";
    }
    if (empty($_POST['description'])) {
        $errors['description'] = "A pokémon leírásának megadása kötelező.";
    }
    if ($_POST['hp'] > 99 || $_POST['hp'] < 1) {
        $errors['hp'] = "A pokémon életereje 1 és 99 között kell lennie megadása kötelező.";
    }
    if ($_POST['attack'] > 99 || $_POST['attack'] < 1) {
        $errors['attack'] = "A pokémon életereje 1 és 99 között kell lennie megadása kötelező.";
    }
    if ($_POST['defense'] > 99 || $_POST['defense'] < 1) {
        $errors['defense'] = "A pokémon életereje 1 és 99 között kell lennie megadása kötelező.";
    }
    if ($_POST['price'] < 1 || $_POST['price'] > 999) {
        $errors['price'] = "A pokémon életereje 1 és 999 között kell lennie megadása kötelező.";
    }

    if (empty($errors)) {
        $cards_storage->add($cardId, [
            "id" => $cardId,
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "hp" => (int) $_POST['hp'],
            "attack" => (int) $_POST['attack'],
            "defense" => (int) $_POST['defense'],
            "price" => (int) $_POST['price'],
            "description" => $_POST['description'],
            "image" => $_POST['image']
        ]);
        $admin_user['cards'][] = $cardId;
        header("Location: index.php?success=OK");
    }
}

if (isset($_GET['delete'])) {
    $cards_storage->delete($_GET['delete']);
    if (($_GET['delete'] = array_search($_GET['delete'], $admin_user['cards'])) !== false) {
        unset($admin_user['cards'][$_GET['delete']]);
        $admin_user['cards'] = array_values($admin_user['cards']);
    }
}
$users_storage->update('admin', $admin_user);

$filterType = isset($_POST['filter']) ? $_POST['filter'] : 'all';
$admin_cards = $cards_storage->findMany(function ($card) use ($admin_user) {
    return in_array($card['id'], $admin_user['cards']);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="shortcut icon" href="https://assets.pokemon.com/static2/_ui/img/favicon.ico">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home</h1>
        <div class="logreg"><span>
                <?php if (!isset($_SESSION['email'])): ?>
                    <a href="login.php">Login</a> | <a href="register.php">Register</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['email'])): ?>
                    <span class="icon">👑:</span><a href="user.php?name=<?= $_SESSION['name'] ?>">
                        <?= $_SESSION['name'] ?>
                    </a> &emsp;
                    <span class="icon">💰</span>
                    <?= $_SESSION['money'] ?>
                    | <a href="logout.php">Kijelentkezés</a>
                <?php endif; ?>
            </span>
        </div>
    </header>
    <div id="content">
        <form method="post">
            <?php if (isset($_SESSION['permission']) && $_SESSION['permission'] == 1): ?>
                <span class="filterinput"><a href="buy.php?id=<?= $randomCard ?>">Buy a random card</a></span>
            <?php endif; ?>
            <select id="filter" name="filter">
                <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>All</option>
                <?php
                $addedTypes = [];
                foreach ($admin_cards as $card):
                    $type = $card['type'];
                    if (!in_array($type, $addedTypes)):
                        ?>
                        <option value="<?= $type ?>" <?= $filterType === $type ? 'selected' : '' ?>>
                            <?= $type ?>
                        </option>
                        <?php
                        $addedTypes[] = $type;
                    endif;
                endforeach;
                ?>
            </select>
            <input class="filterinput" type="submit" value="Filter">
        </form>

        <div id="card-list">
            <?php foreach ($admin_cards as $cardId => $card): ?>
                <form action="buy.php?id=<?= $cardId ?>" method="post" novalidate>
                    <div style="<?= ($filterType === 'all' ? '' : ($filterType != $card['type'] && $_POST ? 'display:none' : 'display:block')) ?>"
                        class="pokemon-card">

                        <div class="image clr-<?= $card['type'] ?>">
                            <?php if (isset($_SESSION['permission']) && $_SESSION['permission'] == 0): ?>
                                <span class="icon delete"><a href="index.php?delete=<?= $card['id'] ?>">❌</a></span>
                            <?php endif; ?>
                            <img src="<?= $card['image'] ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=<?= $card['id'] ?>">
                                    <?= $card['name'] ?>
                                </a></h2>
                            <span class="card-type"><span class="icon">🏷</span>
                                <?= $card['type'] ?>
                            </span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span>
                                    <?= $card['hp'] ?>
                                </span>
                                <span class="card-attack"><span class="icon">⚔</span>
                                    <?= $card['attack'] ?>
                                </span>
                                <span class="card-defense"><span class="icon">🛡</span>
                                    <?= $card['defense'] ?>
                                </span>
                            </span>
                        </div>
                        <?php if (isset($_SESSION['permission']) && $_SESSION['permission'] == 1): ?>
                            <input type="submit" value="<?= "💰" . $card['price'] ?>" class="buy card-price icon"></input>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endforeach; ?>
            <?php if (isset($_SESSION['permission']) && $_SESSION['permission'] == 0): ?>
                <form action="index.php?id=card<?= count($admin_cards); ?>" method="post" novalidate>
                    <div class="pokemon-card new">
                        <div class="image">
                            <h2>Új lap létrehozása</h2>
                        </div>
                        <div class="details">
                            <span>Image: </span><span><input type="text" name="image"
                                    value="https:\/\/assets.pokemon.com\/assets\/cms2\/img\/pokedex\/full\/025.png"
                                    id="image"></span><br>
                            <span style="color: red;">
                                <?= isset($errors['image']) ? $errors['image'] . '<br>' : "" ?>
                            </span>

                            <span>Name: </span><span><input type="text" name="name" value="name..." id="name"></span>
                            <span style="color: red;">
                                <?= isset($errors['name']) ? $errors['name'] . '<br>' : "" ?>
                            </span>

                            <span class="card-type"><span class="icon">🏷 &nbsp;</span> <input type="text" name="type"
                                    value="type..." id="type"></span>
                            <span style="color: red;">
                                <?= isset($errors['type']) ? $errors['type'] . '<br>' : "" ?>
                            </span>

                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span> <input type="number" name="hp" id="hp"
                                        value="1" class="numinput"></span>
                                <span style="color: red;">
                                    <?= isset($errors['hp']) ? $errors['hp'] . '<br>' : "" ?>
                                </span>

                                <span class="card-defense"><span class="icon">🛡</span> <input type="number" name="defense"
                                        id="defense" value="1" class="numinput"></span>
                                <span style="color: red;">
                                    <?= isset($errors['defense']) ? $errors['defense'] . '<br>' : "" ?>
                                </span>

                                <span class="card-attack"><span class="icon">⚔</span> <input type="number" name="attack"
                                        id="attack" value="1" class="numinput"></span>
                                <span style="color: red;">
                                    <?= isset($errors['attack']) ? $errors['attack'] . '<br>' : "" ?>
                                </span>
                            </span>
                            <span class="card-price"><span class="icon">💰</span> <input type="number" name="price"
                                    id="price" value="1" class="numinput"></span>
                            <span style="color: red;">
                                <?= isset($errors['price']) ? $errors['price'] . '<br>' : "" ?>
                            </span>
                        </div>
                        <textarea id="description" name="description" value="descripton..." class=""></textarea><br>
                        <span style="color: red;">
                            <?= isset($errors['description']) ? $errors['description'] . '<br>' : "" ?>
                        </span>
                        <input type="submit" value="ADD" class="buy add"></input>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
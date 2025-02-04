<?php
session_start();
require_once "Storage.php";


$cards_storage = new Storage(new JsonIO('cards.json'));
$cards = $cards_storage->findMany(function ($card) {
    return in_array($card['id'], $_SESSION['cards']);
});

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Profile </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="shortcut icon" href="https://assets.pokemon.com/static2/_ui/img/favicon.ico">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> >
            <?= $_SESSION['name'] ?>
        </h1>
        <div class="logreg"><span>
                <?php if (isset($_SESSION['email'])): ?>
                    <span class="icon">📧:</span><?= $_SESSION['email']?>&emsp;
                    <span class="icon">💰</span><?=$_SESSION['money']?>
                    | <a href="logout.php">Kijelentkezés</a>
                <?php endif; ?>
            </span>
        </div>
    </header>
    <div id="content">

        <div id="card-list">
            <?php foreach ($cards as $cardId => $card): ?>
                <form action="sell.php?id=<?= $cardId ?>" method="post" novalidate>
                    <div class="pokemon-card">
                        <div class="image clr-<?= $card['type'] ?>">
                            <img src="<?= $card['image'] ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=<?= $cardId ?>">
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
                        <input type="submit" value="<?= "💱" . $card['price'] * 0.9 ?>" class="buy card-price icon"></input>
                        <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
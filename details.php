<?php
require_once "Storage.php";

$cards_storage = new Storage(new JsonIO('cards.json'));
$card = $cards_storage->findById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon |
        <?= $card['name'] ?>
    </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
    <link rel="shortcut icon" href="https://assets.pokemon.com/static2/_ui/img/favicon.ico">
</head>

<body>
    <header class="clr-<?= $card['type'] ?>">
        <h1><a href="index.php">IKémon</a> >
            <?= $card['name'] ?>
        </h1>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?= $card['type'] ?>">
                <img src="<?= $card['image'] ?>" alt="">
            </div>
            <div class="info">
                <div class="description">
                    <?= $card['description'] ?>
                </div>
                <span class="card-type"><span class="icon">🏷</span> Type:
                    <?= $card['type'] ?>
                </span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health:
                        <?= $card['hp'] ?>
                    </div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack:
                        <?= $card['attack'] ?>
                    </div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense:
                        <?= $card['defense'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="clr-<?= $card['type'] ?>">
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
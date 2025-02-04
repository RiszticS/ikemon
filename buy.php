<?php
session_start();

require_once "Storage.php";
$cards_storage = new Storage(new JsonIO('cards.json'));
$users_storage = new Storage(new JsonIO('users.json'));
$admin = $users_storage->findOne(['name' => 'admin']);
$user = $users_storage->findOne(['name' => $_SESSION['name']]);
$selectedCard = $cards_storage->findOne(['id' => $_GET['id']]);

$cardIndex = array_search($_GET['id'], $admin['cards']);

if ($cardIndex !== false && count($_SESSION['cards']) < 5 && count($user['cards']) < 5 && $user['money'] - $selectedCard['price']>0) {
    unset($admin['cards'][$cardIndex]);

    $_SESSION['money'] = $user['money'] = round($user['money'] - $selectedCard['price']);

    $_SESSION['cards'][] = $_GET['id'];
    $user['cards'][] = $_GET['id'];
}

$users_storage->update('admin', $admin);
$users_storage->update($_SESSION['name'], $user);

header("Location: index.php");
?>

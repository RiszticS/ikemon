<?php
session_start();

require_once "Storage.php";
$cards_storage = new Storage(new JsonIO('cards.json'));
$users_storage = new Storage(new JsonIO('users.json'));
$admin = $users_storage->findOne(['name' => 'admin']);
$user = $users_storage->findOne(['name' => $_SESSION['name']]);
$selectedCard = $cards_storage->findOne(['id' => $_GET['id']]);

$cardIndex = array_search($_GET['id'], $user['cards']);
$sesscardIndex = array_search($_GET['id'], $_SESSION['cards']);


if ($cardIndex !== false) {
    unset($user['cards'][$cardIndex]);
    unset($_SESSION['cards'][$sesscardIndex]);

    $_SESSION['money'] =$user['money'] = round($user['money'] + ($selectedCard['price']*0.9));

    $admin['cards'][] = $_GET['id'];
    $admin['cards'] = array_values($admin['cards']);
}






$users_storage->update('admin', $admin);
$users_storage->update($_SESSION['name'], $user);

header("Location: user.php?name=" . $_SESSION['name']);
?>
<?php 
    require_once '../libraryUser.php';
    $lib_user = new Library();
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $total = $qty * $price;
?>
<h5>Rp. <?= $total ?></h5>
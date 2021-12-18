<?php 
    require_once '../libraryUser.php';
    $lib_user = new Library();
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $total = $qty * $price;
?>
<h5 class="total-price-val">Rp. <?= number_format($total, 0,",",".") ?></h5>
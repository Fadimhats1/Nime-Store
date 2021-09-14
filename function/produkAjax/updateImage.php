<?php 
    include('../libraryUser.php');
    $lib_user = new Library();
    $idProd = $_POST['id'];
    $imageId = $_POST['imageId'];
    $lib_user->gambarSampul($imageId, $idProd);
?>
<?php 
    include('../libraryUser.php');
    $lib_user = new Library();
    $idProd = $_POST['id'];
    $imageDeleteId = $_POST['imageDeleteId'];
    $dataEdit = $lib_user->selectSpecific($idProd, 'produk');
    $lib_user->delete($imageDeleteId, 'image_produk');
    $flag = $lib_user->checkImage($dataEdit['image_produk_id']);
    $image = $lib_user->showImageProduk($idProd);
    $countImage = count($image);
    $_SESSION['countImage'] = $countImage;
?>
<div class="mt-3 bbm">
    <h2>Product Photos</h2>
</div>
<div id="imgCont" class="d-flex justify-content-evenly">
    <?php
        $i = 0;
        foreach($image as $key){?>
            <div class="d-flex flex-column gap-3 justify-content-center">
                <img src="<?= $key['nama_image']; ?>" width="140px">
                <div class="d-flex justify-content-center gap-2 align-items-center">
                    <input id="<?= $key['id'] ?>" type="radio" name="gambarSampul" <?php if($flag){ echo $check = $dataEdit['image_produk_id'] == $key['id'] ? 'checked' : ''; }else{ echo $check = !$i ? 'checked' : ''; } ?>>
                    <button class="btn btn-danger d-flex gap-3 align-items-center hapus" name="hapus" value="<?= $key['id'] ?>" <?= $disabled = $countImage > 1 ? '' : 'disabled' ?>><i class="fas fa-trash"></i></button>
                </div>
            </div>
    <?php $i++; }?>
</div>

<form id="imageForm" method="POST" enctype="multipart/form-data">
    <div class="d-flex gap-3 align-items-center">
        <div>
            <div class="border border-dark">
                <input id="imgInp" name="gambarProduk[]" class="p-1" type="file" multiple <?= $disabled = $countImage == 5 ? 'disabled' : '' ; ?>>
            </div>
            <p><?= $error = isset($_SESSION['error']['totalImage']) ? $_SESSION['error']['totalImage'] : ''; ?></p>
        </div>
        <button id="save" class="btn btn-primary px-5" name="save" disabled>Save</button>
    </div>
</form>
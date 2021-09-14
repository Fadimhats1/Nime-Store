<?php 
    require_once '../libraryUser.php';

    $lib_user = new Library();
    $currPage = $_POST['currPage'];
    $perPage = 24;
    $startPage = ($currPage - 1) * $perPage;
    $produk = $lib_user->showProduk('', $startPage, $perPage);
    foreach($produk AS $key){ ?>
        <div class="col">
            <a class="card" href="../../produkPage.php?id=<?php echo $key['id'] ?>" role="button">
                <img src="<?= $key['nama_image'] ?>" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column gap-2">
                    <p class="card-text"><?= $key['nama_produk'] ?></p>
                    <h5 class="card-text"><?= $key['harga_produk'] ?></h4>
                </div>
            </a>
        </div>
    <?php }
?>
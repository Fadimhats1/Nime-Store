<?php 
    require_once '../libraryUser.php';
    require_once '../helper/text.php';
    $lib_user = new Library();
    $currPage = $_POST['currPage'];
    $perPage = 24;
    $startPage = ($currPage - 1) * $perPage;
    $produk = $lib_user->showProduk('', $startPage, $perPage);
    foreach($produk AS $key){ ?>
        <div class="col">
            <a class="card h-100" href="produkPage.php?id=<?php echo $key['id'] ?>" role="button">
                <img src="<?= $key['nama_image'] ?>" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column gap-2">
                    <p class="card-text text-break" title="<?= $key['nama_produk'] ?>"><?= elipsis($key['nama_produk'], 25); ?></p>
                    <p class="card-text">Rp. <?= $key['harga_produk'] ?></p>
                </div>
            </a>
        </div>
    <?php }
?>
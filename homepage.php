<div id="body" class="d-flex flex-column align-items-center gap-4 body-homepage">
    <?php 
        $slides = $lib_user->showSlider(0, 10); 
        $totalDataScroll = $lib_user->searchTotalPage('produk', 'nama_produk', '', 24);
        $kategori = $lib_user->searchKategori('', 0, 24);
        if($slides){ ?>
            <div class="owl-one owl-carousel owl-theme mt-4 px-3">
                <?php
                    foreach($slides AS $key){?>
                    <img src="<?= $key['gambar_slides'] ?>" alt="" height="200px">
                <?php } ?>
            </div>
        <?php }else{ ?>
            <div class="owl-one owl-carousel owl-theme mt-4 px-3">
               <img src="image/default.png" alt="" height="200px">
               <img src="image/default.png" alt="" height="200px">
               <img src="image/default.png" alt="" height="200px">
               <img src="image/default.png" alt="" height="200px">
               <img src="image/default.png" alt="" height="200px">
               <img src="image/default.png" alt="" height="200px">
            </div>
        <?php } ?>
    <div class="w-75 mt-4">
        <h5 class="mb-2 mt-2">Categories</h5>
        <div class="card-group shadows">
            <?php 
                foreach($kategori AS $key){ ?>
                <a href="searchProduk.php?order=best&category=<?= $key['id'] ?>" class="card">
                    <div class="p-3 d-flex justify-content-center">
                        <img src="<?= $key['icon_kategori'] ?>" class="" alt="" width="80px" height="80px">
                    </div>
                    <div class="px-0 pb-3">
                        <p class="text-center text-nowrap fw-bold"><?= $key['nama_kategori'] ?></p>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="w-75 mb-5">
        <h5 class="mb-2">Item Lists</h5>
        <div class="card text-center shadows">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="true" href="#">All Items</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div id="section-1" class="row row-cols-1 row-cols-sm-6 g-4">

                </div>
            </div>
            <div class="d-flex justify-content-center my-4">
                <button id="loadMore" class="btn btn-primary hide">click to see other recent items</button>
            </div>
        </div>
    </div>
    
</div>
<script>
    var totalPage = parseInt("<?php echo $totalDataScroll ?>");
</script>
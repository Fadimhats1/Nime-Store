<?php 
    require_once '../libraryUser.php';
    require_once '../helper/text.php';
    $lib_user = new Library();
    $perPage = 24;
    $min = isset($_POST['minPrice']) ? $_POST['minPrice'] : 0;
    $max = isset($_POST['maxPrice']) ? $_POST['maxPrice'] : 0;
    $page = isset($_POST['pageProd']) ? $_POST['pageProd'] : 1;
    $firstData = ($page-1) * $perPage;
    $order = isset($_POST['order']) ? $_POST['order'] : '';
    $keyword = isset($_POST['searchProd']) ? $_POST['searchProd'] : '';
    $searchKate = isset($_POST['searchKate']) ? $_POST['searchKate'] : '';
    $search = isset($_POST['searchSubKate']) ? $_POST['searchSubKate'] : '';
    if($searchKate){
        $search = [];
    }
    $dataProduk = $lib_user->searchByKategori($keyword, $search, $order, $firstData, $min, $max, $searchKate);
    $totalData = $lib_user->db->query("SELECT FOUND_ROWS()")->fetchColumn();
    $totalPage = ceil($totalData/$perPage);
?>
<div class="d-flex w-100 justify-content-between align-items-center">
    <div>
        <h4><?= $searched = $keyword ? "Search Result for \"$keyword\"" : 'All Items' ?></h4>
        <p>Showing <?= $first = $totalData ? $firstData + 1 : 0 ?> - <?= $last = $firstData + 24 <= $totalData ? $firstData + 24 : $totalData ?> of <?= $totalData ?></p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <h5 class="fw-normal text-nowrap fs-5">Sort By</h5>
        <select id="order-by" class="form-select">

            <option value="best" <?= $temp = $order == 'best' ? 'selected' : ''; ?>>Best Rating</option>
            <option value="high" <?= $temp = $order == 'high' ? 'selected' : ''; ?>>Highest Price</option>
            <option value="low" <?= $temp = $order == 'low' ? 'selected' : ''; ?>>Lowest Price</option>
        </select>
    </div>
</div>
<hr>
<?php if($totalPage > 0){ ?>
    <div id="show-search-prod" class="d-flex flex-column align-items gap-5">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php foreach($dataProduk AS $key){ ?>
                <a href="produkPage.php?id=<?= $key['id'] ?>" class="col">
                    <div class="card h-100">
                        <img src="<?= $key['nama_image']; ?>" class="card-img-top" alt="..." height="144.175px">
                        <div class="card-body">
                            <p class="card-text fw-bold" title="$key['nama_produk']"><?= elipsis($key['nama_produk'], 25) ?></p>
                            <p class="card-text fw-bold">Rp. <?= number_format($key['harga_produk'], 0,",",".") ?></p>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
        <?php if($totalPage > 1){ ?>
            <nav class="d-flex justify-content-center w-100">
                <ul class="pagination">
                    <li id="firstButton" class="page-item <?= $disabled = $page == 1 ? 'disabled' : '' ?>">
                        <button class="page-link" value="<?= 1 ?>">First</button>
                    </li>
                    <?php
                    if(!($totalPage > 0)){
                        $totalPage = 1;
                    }
                    $pageEnd = $page + 1 > $totalPage ? $totalPage : $page + 1;
                    $pageEnd = $page == 1 && $totalPage >= 3 ? $page + 2 : $pageEnd; 

                    $pageFirst = $page - 1 > 0 ? $page - 1 : 1;
                    $pageFirst = $page - 2 > 0 && $page == $totalPage ? $page - 2 : $pageFirst;

                    // GENERATE NUMBER BUTTON

                    for($i = $pageFirst ; $i <= $pageEnd; $i++){ ?> 
                        <li class="page-item <?= $active = $i == $page ? 'active' : ''; ?>">
                            <button class="page-link" value="<?= $i ?>"><?= $i ?></button>
                        </li>
                    <?php } ?>

                    <li id="lastButton" class="page-item <?= $disabled = $page == $totalPage ? 'disabled' : '' ?>">
                        <button class="page-link" value="<?= $totalPage ?>">Last</button>
                    </li>
                </ul>
            </nav>
        <?php } ?>
    </div>
<?php }else{ ?>
    <div class="d-flex justify-content-center">
        <h1 class="text-center">There is no such data</h1>
    </div>
<?php } ?>
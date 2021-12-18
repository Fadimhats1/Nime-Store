<?php 
    require_once '../libraryUser.php';
    $lib_user = new Library();

    $kategori = $lib_user->selectKategori();
    $keyword = isset($_POST['searchProd']) ? $_POST['searchProd'] : '';
    $getKate = isset($_POST['searchKate']) ? $_POST['searchKate'] : '';
    $getSubKate = isset($_POST['searchSubKate']) ? $_POST['searchSubKate'] : '';
    if($getKate){
        $getSubKate = [];
    }
    $dataProdSubKate = $lib_user->searchProdKategori($keyword, $getKate);
    $tempSubKate = array_column($dataProdSubKate, 'subkategori_id');
    $numbers = $lib_user->minMaxPrice($keyword, $getSubKate, $getKate);
    $max = isset($_GET['max']) ? $_GET['max'] : $numbers[0];
    $min = isset($_GET['min']) ? $_GET['min'] : $numbers[1];
?>
<div class="d-flex flex-column gap-3">
    <h5>Price</h5>
    <label for="inpPrice1" class="d-flex gap-1 align-items-center price-1 mt-1">
        <p class="fw-bold">Rp.</p>
        <input id="inpPrice1" class="w-100" type="text" placeholder="Minimum Price" value="<?= number_format(0, 0,",","."); ?>">
    </label>

    <label for="inpPrice2" class="d-flex gap-1 align-items-center price-2">
        <p class="fw-bold">Rp.</p>
        <input id="inpPrice2" class="w-100" type="text" placeholder="Maximum Price" value="<?= number_format($max, 0,",","."); ?>">
    </label>
    
    <div class="container-slider">
        <div class="slider-track"></div>
        <input id="slider-1" type="range" min="0" max="<?= $max ?>" value="0">
        <input id="slider-2" type="range" min="0" max="<?= $max ?>" value="<?= $max ?>">
    </div>
</div>
<hr>
<div class="d-flex flex-column gap-2">
    <div class="d-flex justify-content-between align-items-center">
        <h5>Category</h5>
        <p class="text-primary reset" role="button">Reset</p>
    </div>
    <div class="text-primary">
        <ul class="list-category ps-3">
            <?php for($i = 0; $i < count($kategori); $i++){
                $subKategori = $lib_user->showSubKateSelect($kategori[$i]['id']);
                $subKategoriId = array_column($subKategori, 'id');
                $flag = !empty(array_intersect($subKategoriId, $tempSubKate));
                if($flag){ ?>
                    <li class="d-flex align-items-center kategori" role="button" value="<?= $kategori[$i]['id'] ?>" >
                        <p class="flex-grow-1 fw-bold text-start"><?= $kategori[$i]['nama_kategori'] ?></p>
                        <i class="fas fa-check  <?php if($getKate) { echo $hide = $getKate == $kategori[$i]['id'] ? '' : 'hide'; }else{ echo $hide = 'hide';} ?>"></i>
                    </li>
                    <?php 
                        for($j = 0; $j < count($subKategori); $j++){
                            if(in_array($subKategori[$j]['id'], $tempSubKate)){ 
                                $countKate = $lib_user->countKate($keyword, $subKategori[$j]['id']);
                                ?>
                                <li class="d-flex align-items-center ps-3 subkategori" role="button" value="<?= $subKategori[$j]['id'] ?>">
                                    <p class="flex-grow-1"><?= $subKategori[$j]['nama_subkategori']; ?> (<?= $countKate; ?>)</p>
                                    <i class="fas fa-check <?php if($getSubKate) {echo $hide = in_array($subKategori[$j]['id'], $getSubKate) ? '' : 'hide'; }else{ echo $hide = 'hide';} ?>"></i>
                                </li>
                            <?php }
                        } ?>
                <?php }
            } ?>
        </ul>
    </div>
</div>
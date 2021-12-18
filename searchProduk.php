<?php 
    include('function/libraryUser.php');
    require_once 'function/helper/text.php';
    $lib_user = new Library();
    session_start();
    $keyword = isset($_GET['search']) ? $_GET['search'] : '';
    $orderBy = isset($_GET['order']) ? $_GET['order'] : '';
    $getKate = isset($_GET['category']) ? $_GET['category'] : '';
    $page = isset($_GET['pageProd']) ? $_GET['pageProd'] : 1;
    
    $getSubKate = [];
    
    $page = is_numeric($page) ? $page : 1;
    if(isset($_GET['subcategory'])){
        $getSubKate = explode(',', $_GET['subcategory']);
    }
    if($getKate){
        $getSubKate = [];
    }

    $perPage = 24;
    $firstData = ($page-1) * $perPage;
    $kategori = $lib_user->selectKategori();
    $numbers = $lib_user->minMaxPrice($keyword, $getSubKate, $getKate);
    $max = isset($_GET['max']) ? $_GET['max'] : $numbers[0];
    $min = isset($_GET['min']) ? $_GET['min'] : $numbers[1];

    $dataProduk = $lib_user->searchByKategori($keyword, $getSubKate, $orderBy, $firstData, $min, $max, $getKate);

    $totalData = $lib_user->db->query("SELECT FOUND_ROWS()")->fetchColumn();

    $totalPage = ceil($totalData/$perPage);
    $dataProdSubKate = $lib_user->searchProdKategori($keyword, $getKate);
    $tempSubKate = array_column($dataProdSubKate, 'subkategori_id');
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <div id="body" class="d-flex justify-content-center pt-3 pb-5">
        <div class="w-75 pt-3 pb-4 d-flex gap-4">
            <div id="section-kategori" class="card p-3 d-flex flex-column gap-3 shadows" style="width: 18rem;">
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
                    <!-- SELECTION KATEGORI & SUBKATEGORI -->
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
            </div>

            <div id="section-show-produk" class="card flex-grow-1 p-3 d-flex flex-column section-produk gap-3 shadows" style="width: 18rem;">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div>
                        <h4><?= $searched = $keyword ? "Search Result for \"$keyword\"" : 'All Items' ?></h4>
                        <p>Showing <?= $first = $totalData ? $firstData + 1 : 0 ?> - <?= $last = $firstData + 24 <= $totalData ? $firstData + 24 : $totalData ?> of <?= $totalData ?></p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <h5 class="fw-normal text-nowrap fs-5">Sort By</h5>
                        <select id="order-by" class="form-select">
                            <option value="best" <?= $temp = $orderBy == 'best' ? 'selected' : ''; ?>>Best Rating</option>
                            <option value="high" <?= $temp = $orderBy == 'high' ? 'selected' : ''; ?>>Highest Price</option>
                            <option value="low" <?= $temp = $orderBy == 'low' ? 'selected' : ''; ?>>Lowest Price</option>
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
                                        <img src="<?= $key['nama_image']; ?>" class="card-img-top" alt="" height="144.175px">
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
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let search = [], searchProd = '<?= isset($_GET['search']) ? $_GET['search'] : '' ?>', orderBy = $('#order-by option:selected').val(), link = window.location.protocol + "//" + window.location.host + window.location.pathname, minGap = 0, maxx = parseInt($('#slider-2').attr('max')), max = '<?= $max ?>', min = '<?= $min ?>', pages = 1, category =  '<?= $getKate ?>';

        fillColor();

        $.each($('.subkategori'), function(){
            if($(this).children('i').is(':visible')){
                let searchKate = $(this).val();
                search.push(searchKate);
            }
        })

        // PAGINATION
        $('body').on('click', '.page-link' , (e)=>{
            pages = $(e.target).val();
            loadProduk(pages, search, searchProd, orderBy, link, min, max);
        })
        // ORDER
        $('body').on('change', 'select', function(){
            orderBy = $('#order-by option:selected').val();
            searchProduk(search, searchProd, orderBy, link, pages, min, max);
        })
        // KATEGORI
        $('body').on('click', '.kategori', function(){
            if($(this).children('i').is(':visible')){
                $(this).children('i').hide();
                category = '';
            }else{
                $(this).children('i').show();
                category = $(this).val();
                search = [];
            }
            loadKategori(pages, search, searchProd, orderBy, link);
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
        })
        // SUBKATEGORI
        $('body').on('click', '.subkategori', function(){
            let searchKate = $(this).val(), flagKategori = false;
            if($(this).children('i').is(':visible')){
                $(this).children('i').hide();
                search = $.grep(search, function(value){
                    return value != searchKate;
                })
            }else{
                $(this).children('i').show();
                search.push(searchKate);
                $.each($('.kategori'), function(){
                    if($(this).children('i').is(':visible')){
                        flagKategori = true;
                    }
                })
                if(flagKategori){
                    category = '';
                    loadKategori(pages, search, searchProd, orderBy, link);
                }
            }
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
        })
        // RESET
        $('body').on('click', '.reset', function(){
            $.each($('li'), function(){
                if($(this).children('i').is(':visible')){
                    $(this).children('i').hide();
                }
            })
            search = [];
            category = '';
            loadKategori(pages, search, searchProd, orderBy, link);
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
        })
        // SLIDER 1
        $('body').on('input', '#slider-1', function(){
            if(parseInt($('#slider-2').val()) - parseInt($(this).val()) <= minGap){
                $(this).css('z-index', '1');
                $(this).val(parseInt($('#slider-2').val()) - minGap);
            }else{
                $(this).css('z-index', '0');
            }
            $('#inpPrice1').val(mask(parseInt($(this).val())));
            min = unmask(Array.from($('#inpPrice1').val()), 1);
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
            fillColor();
        })
        // SLIDER 2
        $('body').on('input', '#slider-2', function(){
            if(parseInt($(this).val()) - parseInt($('#slider-1').val()) <= minGap){
                $(this).val(parseInt($('#slider-1').val()) + minGap);
            }
            $('#inpPrice2').val(mask(parseInt($(this).val())));
            max = unmask(Array.from($('#inpPrice2').val()), 2);
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
            fillColor();
        })
        // INPUT PRICE 1
        $('body').on('input', '#inpPrice1', function(){
            if(!$(this).val()){
                $(this).val(0);
            }
            if($(this).val() > parseInt('<?php echo $max ?>')){
                $('#slider-1').val(<?php echo $max ?>);
                min = '<?php echo $max ?>';
            }else if($(this).val() < 0){
                let temp = '<?php echo $min ?>'; 
                $('#slider-1').val(temp);
                min = temp;
            }else{
                $('#slider-1').val($(this).val());
                min = $(this).val();
            }
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
            fillColor();
        })
        // INPUT PRICE 2
        $('body').on('input', '#inpPrice2', function(){
            if(!$(this).val()){
                $(this).val(0);
            }
            if($(this).val() > parseInt('<?php echo $max ?>')){
                $('#slider-2').val(<?php echo $max ?>);
                max = '<?php echo $max ?>';
            }else if($(this).val() < 0){
                let temp = '<?php echo $min ?>';
                $('#slider-2').val(temp);
                max = temp;
            }else{
                $('#slider-2').val($(this).val());
                max = $(this).val();
            }
            searchProduk(search, searchProd, orderBy, link, 1, min, max);
            fillColor();
        })
        

        // FUNCTION
        let searchProduk = $.fn.searchProduk = function(searches, key, order, links, pages, minP, maxP){
            searches.sort();
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/searchByProduk.php',
                data:{
                    pageProd: pages,
                    searchSubKate: searches,
                    searchProd: key,
                    order: order,
                    minPrice: minP,
                    maxPrice: maxP,
                    searchKate: category
                }, success: function(data){
                    $('#section-show-produk').html(data);
                    addtoParam(searches, links, order, pages, minP, maxP);
                }
            })
        }, loadProduk = $.fn.loadProduk = function(pages, searches, key, order, links, minP, maxP){
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/searchByProduk.php',
                data:{
                    pageProd: pages,
                    searchSubKate: searches,
                    searchProd: key,
                    order: order, 
                    minPrice: minP,
                    maxPrice: maxP, 
                    searchKate: category
                }, success: function(data){
                    $('#section-show-produk').html(data);
                    addtoParam(searches, links, order, pages, minP, maxP);
                }
            });
        }, loadKategori = $.fn.loadKategori = function(pages, searches, key, order, links){
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/generateKategori.php',
                data:{
                    searchSubKate: searches,
                    searchProd: key,
                    searchKate: category
                }, success: function(data){
                    $('#section-kategori').html(data);
                    maxx = parseInt($('#slider-2').attr('max'));
                    minP = unmask(Array.from($('#inpPrice1').val()), 1);
                    fillColor();
                    maxP = unmask(Array.from($('#inpPrice2').val()), 2);
                    addtoParam(searches, links, order, pages, minP, maxP);
                }
            });
        };

        function addtoParam(searches, links, order, pages, mins, maxs){
            let flag = false;
            if(searchProd){
                links += `?search=${searchProd}`;
                flag = true;
            }
            if(searches.length){
                for(let i = 0; i < searches.length ; i++){
                    if(i){
                        links += ',';
                    }else{
                        links = flag ? links + '&subcategory=' : links + '?subcategory=';
                    }
                    links += searches[i];
                }
                flag = true;
            }
            links = flag ? links + `&order=${order}&pageProd=${pages}` : links + `?order=${order}&pageProd=${pages}`
            links = mins ? links + `&min=${mins}` : links + ''; 
            links = maxs && maxs != '<?php echo $max ?>' ? links + `&max=${maxs}` : links + '';
            links = category ? links + `&category=${category}` : links + '';
            window.history.pushState({ path: links }, '', links);
        }

        function mask(number){
            const format = number.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            return convert.join('.').split('').reverse().join('');
        }

        function fillColor(){
            let percent1 = (parseInt($('#slider-1').val())/maxx) * 100, percent2 = (parseInt($('#slider-2').val())/maxx) * 100;
            $('.slider-track').css('background', `linear-gradient(to right, #dadae5 ${percent1}%, #0d6efd ${percent1}%, #0d6efd ${percent2}%, #dadae5 ${percent2}%`)
        }

        function unmask(arr, check){
            arr = arr.filter(chara => chara != '.');
            arr.forEach(e => {
                if(e >= 'a' && e <= 'z' || e >= 'A' && e <= 'Z'){
                    if(check == 1){
                        return min
                    }else{
                        return max;
                    }
                }
            });
            if(arr[0] == 0 && arr.length > 1){
                arr.shift();
            }
            return parseInt(arr.join(''));
        }
    </script>
</body>
</html>
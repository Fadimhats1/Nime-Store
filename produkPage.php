<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    $checking = false;
    session_start();
    $recentView = json_decode($_COOKIE['recentView']);

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];
        array_push($recentView, $id);
        $recentView = array_unique($recentView);
        setcookie('recentView', json_encode($recentView), time() + (3600 * 24));
        $checking = true;
    }

    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';

    if($login && isset($_POST['quit'])){
        session_destroy();
        header('Location:index.php');
    }
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
    <?php 
        include('navbar.php');
        if($checking){
        $produk = $lib_user->selectSpecific($id, 'produk'); ?>
        <div id="body" class="d-flex justify-content-center pt-3 pb-5">
            <div class="w-75 pt-3 pb-4 d-flex flex-column gap-5">
                <div class="card card-produk-page shadow-card">
                    <div class="row g-0">
                        <div id="sectImg" class="col-md-5 p-3 d-flex flex-column gap-3">
                            <div id="carousel" class="rounded">
                                <div id="imgProd" class="d-flex">
                                    <?php $imageProduk = $lib_user->showImageProduk($id);
                                        foreach($imageProduk as $key){ ?>
                                            <img src="<?= $key['nama_image'] ?>" class="d-block w-100" alt="..." height="500px" width="400px">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-evenly flex-wrap my-3">
                                <?php $imageProduk = $lib_user->showImageProduk($id);
                                    foreach($imageProduk as $key){ ?>
                                        <img src="<?= $key['nama_image'] ?>" class="d-block rounded img-outside" alt="..." height="50px" width="50px" role="button">
                                <?php } ?>
                            </div>
                        </div>
                        <div id="sectText" class="col-md-7">
                            <div class="card-body d-flex flex-column gap-2">
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title"><?= $produk['nama_produk'] ?></h3>
                                        <button type="button" class="btn btn-outline-primary btn-sm d-flex gap-2 align-items-center justify-content-center">Share<i class="far fa-share-square"></i></button>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php include('rating.php'); ?>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="my-3">Rp. <?= $produk['harga_produk'] ?></h4>
                                <div class="d-flex gap-12 align-items-center">
                                    <div class="d-flex gap-3 align-items-center">
                                        <p>QTY:</p>
                                        <div class="input-group inline-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-minus">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input id="qty" class="form-control quantity text-center" min="1" name="quantity" value="1" type="number">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-plus">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <h5>Total:</h5>
                                        <div id="totalPrice">
                                            <h5>Rp. <?= $produk['harga_produk'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center my-2">
                                    <button class="btn btn-primary w-100 py-2">Add to Cart</button>
                                </div>
                                <hr>
                                <p id="descripProd" class="card-text mt-3 mb-4"><?= $produk['deskripsi_produk'] ?></p>
                                <div class="d-flex gap-3">
                                    <p class="card-text text-muted">Category: </p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <?php
                                                $selectSubKategori = $lib_user->showProdukSub($id);
                                                foreach($selectSubKategori AS $key){ ?>
                                            <a href="#" class="btn btn-secondary btn-sm"><?= $key['nama_subkategori'] ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4>More Stuff Like This!</h4>
                        <a href="">See All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                    <div>
                        <div id="moreCont" class="cards-container d-flex align-items-center justify-content-center px-2 shadow-card">
                            <button class="prev-more btn btn-outline-primary justify-content-center align-items-center hide"><i class="fas fa-chevron-left fs-3"></i></button>
                            <div id="more-stuff" class="cards-group row row-cols-1 row-cols-md-3 g-4 w-100 ">
                                <?php
                                    $moreSubKate = array_column($selectSubKategori, 'subkategori_id');
                                    $moreStuff = $lib_user->moreStuff($moreSubKate, $id);
                                    foreach($moreStuff AS $key){?>
                                    <a href="../../produkPage.php?id=<?= $key['id'] ?>" class="cards col">
                                        <div class="card">
                                            <img src="<?= $key['nama_image']; ?>" class="card-img-top" alt="..." height="144.175px">
                                            <div class="card-body">
                                                <p class="card-title fs-5 fw-bold"><?= $key['nama_produk'] ?></p>
                                                <p class="card-text fs-5 fw-bold"><?= $key['harga_produk'] ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                            <button class="next-more btn btn-outline-primary justify-content-center align-items-center hide"><i class="fas fa-chevron-right fs-3"></i></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4>Recently Viewed Items!</h4>
                    </div>
                    <div>
                        <div id="recentCont" class="cards-container d-flex align-items-center justify-content-center px-2 shadow-card">
                            <button class="prev-recent btn btn-outline-primary justify-content-center align-items-center hide"><i class="fas fa-chevron-left fs-3"></i></button>
                            <div id="recent" class="cards-group row row-cols-1 row-cols-md-3 g-4 w-100 ">
                                <?php
                                    for($i = count($recentView) - 1; $i >= 0; $i--){ 
                                        $recentProduk = $lib_user->showRecentProduk($recentView[$i]);?>
                                    <a href="../../produkPage.php?id=<?= $recentView[$i] ?>" class="cards col">
                                        <div class="card">
                                            <img src="<?= $recentProduk['nama_image']; ?>" class="card-img-top" alt="..." height="144.175px">
                                            <div class="card-body">
                                                <p class="card-title fs-5 fw-bold"><?= $recentProduk['nama_produk'] ?></p>
                                                <p class="card-text fs-5 fw-bold"><?= $recentProduk['harga_produk'] ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                            <button class="next-recent btn btn-outline-primary justify-content-center align-items-center hide"><i class="fas fa-chevron-right fs-3"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div id="body" class="d-flex justify-content-center">
            <div class="w-75 pt-3 pb-4">
                <h1 class="text-center">There is no such data</h1>
            </div>
        </div>
    <?php } ?>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let price = <?php echo $produk['harga_produk'] ?>, qty = 1, temp, currImg = 0, len = $('.img-outside').length ? $('.img-outside').length : 1, heightImage = $('#sectImg').height(), idxRecent = 0, idxMore = 0, totalRecent = Math.ceil(<?php echo count($recentView)/5 ?>), totalMore = Math.ceil(<?php echo count($moreStuff)/5 ?>);

        slideRecent(idxRecent);
        slideMore(idxMore);

        $('#descripProd').css('min-height', `${heightImage*23/100}px`);

        $('.btn-plus, .btn-minus').click(function(e){
            totalPrice(price);
        })

        $('#qty').keyup(function(){
            totalPrice(price);
        })


        let totalPrice = $.fn.totalPrice = function(prices){
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/totalPrice.php',
                data:{
                    price: prices,
                    qty: $('#qty').val() && $.isNumeric($('#qty').val()) ? $('#qty').val() : 1
                }, success: function(data){
                    $('#totalPrice').html(data);
                }
            })
        }
        $('body').on('click', '.img-outside', function(){
            currImg = $(this).index();
            changeImg(currImg);
        });

        function changeImg(curr){
            op = curr * $('#sectImg').width() * -1;
            $('#imgProd').css('transform', `translateX(${op}px)`);
        }
        
        $('body').on('click', '.prev-recent', function(){
            if(idxRecent > 0){
                --idxRecent;
            }
            slideRecent(idxRecent);
        })

        $('body').on('click', '.next-recent', function(){
            if(idxRecent < totalRecent - 1){
                ++idxRecent;
            }
            slideRecent(idxRecent);
        })

        $('body').on('click', '.prev-more', function(){
            if(idxMore > 0){
                --idxMore;
            }
            slideMore(idxMore);
        })

        $('body').on('click', '.next-more', function(){
            if(idxMore < totalMore - 1){
                ++idxMore;
            }
            slideMore(idxMore);
        })
        
        function slideRecent(curr){
            if(curr == 0){
                $('.prev-recent').addClass('hide').removeClass('d-flex');
            }else{
                $('.prev-recent').addClass('d-flex').removeClass('hide');
            }
            if(curr == totalRecent - 1){
                $('.next-recent').addClass('hide').removeClass('d-flex');
            }else{
                $('.next-recent').addClass('d-flex').removeClass('hide');
            }
            op = curr * $('#recentCont').width() * -1;
            $('#recent').css('transform', `translateX(${op}px)`);
        }

        function slideMore(curr){
            if(curr == 0){
                $('.prev-more').addClass('hide').removeClass('d-flex');
            }else{
                $('.prev-more').addClass('d-flex').removeClass('hide');
            }
            if(curr == totalMore - 1){
                $('.next-more').addClass('hide').removeClass('d-flex');
            }else{
                $('.next-more').addClass('d-flex').removeClass('hide');
            }
            op = curr * $('#moreCont').width() * -1;
            $('#more-stuff').css('transform', `translateX(${op}px)`);
        }
    </script>
</body>
</html>
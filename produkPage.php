<?php 
    include('function/libraryUser.php');
    require_once 'function/helper/text.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        include('navbar.php');
        if($checking){
        $produk = $lib_user->selectSpecific($id, 'produk'); ?>
        <div id="body" class="d-flex justify-content-center pt-3 pb-5">
            <div class="w-75 pt-3 pb-4 d-flex flex-column gap-5">
                <div class="card mb-3 p-3">
                    <div class="row">

                        <div class="col-6 ">
                            <div class="owl-img owl-carousel owl-theme d-flex flex-column">
                                <?php $imageProduk = $lib_user->showImageProduk($id);
                                    foreach($imageProduk as $key){ ?>
                                        <img src="<?= $key['nama_image'] ?>" class="d-block w-100" alt="..." height="500px" width="400px">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-6 d-flex flex-column">
                            <div class="p-3 d-flex flex-column gap-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title"><?= $produk['nama_produk'] ?></h3>
                                    <button type="button" class="btn btn-outline-primary btn-sm d-flex gap-2 align-items-center justify-content-center">Share<i class="far fa-share-square"></i></button>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <?php include('rating.php'); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="p-3 d-flex flex-column gap-3">
                                <h5>Rp. <?= number_format($produk['harga_produk'], 0,",",".") ?></h4>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex gap-3 align-items-center">
                                        <p>Qty:</p>
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
                                            <h5>Rp. <?= number_format($produk['harga_produk'], 0,",",".") ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center my-2">
                                    <button class="btn btn-primary w-100 py-2">Add to Cart</button>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body flex-grow-1">
                                <div class="d-flex flex-column w-100 gap-4">
                                    <p class="card-text"><?= $produk['deskripsi_produk'] ?></p>
                                    <div class="d-flex gap-3">
                                        <p class="text-muted">Category: </p>
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
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4>More Stuff Like This!</h4>
                        <a href="">See All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                    <div class="p-3 bg-white shadow-card rounded">
                        <div class="owl-more owl-carousel owl-theme mt-4 px-3">
                            <?php
                                $moreSubKate = array_column($selectSubKategori, 'subkategori_id');
                                $moreStuff = $lib_user->moreStuff($moreSubKate, $id);
                                foreach($moreStuff AS $key){?>
                                <a href="produkPage.php?id=<?= $key['id'] ?>" class="cards col">
                                    <div class="card">
                                        <img src="<?= $key['nama_image']; ?>" class="card-img-top" alt="..." height="144.175px">
                                        <div class="card-body">
                                            <p class="card-text fw-bold" title="$key['nama_produk']"><?= elipsis($key['nama_produk'], 25) ?></p>
                                            <p class="card-text fw-bold">Rp. <?= number_format($key['harga_produk'], 0,",",".") ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h4>Recently Viewed Items!</h4>
                    <div class="p-3 bg-white shadow-card rounded">
                        <div class="owl-recent owl-carousel owl-theme mt-4 px-3">
                            <?php
                                for($i = count($recentView) - 1; $i >= 0; $i--){ 
                                    $recentProduk = $lib_user->showRecentProduk($recentView[$i]);?>
                                <a href="produkPage.php?id=<?= $recentView[$i] ?>" class="cards col">
                                    <div class="card">
                                        <img src="<?= $recentProduk['nama_image']; ?>" class="card-img-top" alt="..." height="144.175px">
                                        <div class="card-body">
                                            <p class="card-title fw-bold" title="$recentProduk['nama_produk']"><?= elipsis($recentProduk['nama_produk'], 25) ?></p>
                                            <p class="card-text fw-bold">Rp. <?= number_format($key['harga_produk'], 0,",",".") ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let price = <?php echo $produk['harga_produk'] ?>, qty = 1, temp, currImg = 0, len = $('.img-outside').length ? $('.img-outside').length : 1, heightImage = $('#sectImg').height(), idxRecent = 0, idxMore = 0, totalRecent = Math.ceil(<?php echo count($recentView)/5 ?>), totalMore = Math.ceil(<?php echo count($moreStuff)/5 ?>);


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

        $(document).ready(function(){
            owlMore();
            owlImage();
            owlRecent();
        })
        
        function owlImage(){
            $('.owl-img').owlCarousel({
                margin: 10,
                items: 1,
                loop: true,
                singleItem: true
            })
            $('.owl-img .owl-next, .owl-img .owl-prev').hide();
        }


        function owlMore(){
            $('.owl-more').owlCarousel({
                margin: 10,
                dots: false,
                items: 6,
                slideBy: 3,
                nav: true,
                navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'], 
                singleItem: true
            })

            $('.owl-more .owl-prev').css({'left': '1.5rem', 'display': function(){
                if($('.owl-more .owl-item').first().hasClass('active')){
                    return 'none'
                }
                return 'block';
            }});

            $('.owl-more .owl-next').css({'right': '1.5rem', 'display': function(){
                if($('.owl-more .owl-item').last().hasClass('active')){
                    return 'none'
                }
                return 'block';
            }})

            $('.owl-prev').click(function(){
                if($('.owl-more .owl-item').first().hasClass('active')){
                    $('.owl-more .owl-prev').css('display', 'none');
                }else{
                    $('.owl-more .owl-prev').css('display', 'block');
                }
                if(!$('.owl-more .owl-item').last().hasClass('active')){
                    $('.owl-more .owl-next').css('display', 'block');
                }
            })

            $('.owl-next').click(function(){
                if($('.owl-more .owl-item').last().hasClass('active')){
                    $('.owl-more .owl-next').css('display', 'none');
                }else{
                    $('.owl-more .owl-next').css('display', 'block');
                }
                if(!$('.owl-more .owl-item').first().hasClass('active')){
                    $('.owl-more .owl-prev').css('display', 'block');
                }
            })
        }

        function owlRecent(){
            $('.owl-recent').owlCarousel({
                margin: 10,
                dots: false,
                items: 6,
                slideBy: 3,
                nav: true,
                navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'], 
                singleItem: true
            })

            $('.owl-recent .owl-prev').css({'left': '1.5rem', 'display': function(){
                if($('.owl-recent .owl-item').first().hasClass('active')){
                    return 'none'
                }
                return 'block';
            }});

            $('.owl-recent .owl-next').css({'right': '1.5rem', 'display': function(){
                if($('.owl-recent .owl-item').last().hasClass('active')){
                    return 'none'
                }
                return 'block';
            }})

            $('.owl-prev').click(function(){
                if($('.owl-recent .owl-item').first().hasClass('active')){
                    $('.owl-recent .owl-prev').css('display', 'none');
                }else{
                    $('.owl-recent .owl-prev').css('display', 'block');
                }
                if(!$('.owl-recent .owl-item').last().hasClass('active')){
                    $('.owl-recent .owl-next').css('display', 'block');
                }
            })

            $('.owl-next').click(function(){
                if($('.owl-recent .owl-item').last().hasClass('active')){
                    $('.owl-recent .owl-next').css('display', 'none');
                }else{
                    $('.owl-recent .owl-next').css('display', 'block');
                }
                if(!$('.owl-recent .owl-item').first().hasClass('active')){
                    $('.owl-recent .owl-prev').css('display', 'block');
                }
            })
        }
    </script>
</body>
</html>
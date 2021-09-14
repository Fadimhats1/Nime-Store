<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
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
    <?php include('navbar.php');?>
    <?php if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $produk = $lib_user->selectSpecific($id, 'produk'); ?>
        <div id="body" class="d-flex justify-content-center">
            <div class="w-75 pt-3 pb-4">
                <div class="card mb-3 card-produk-page">
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
                                    <div class="d-flex justify-content-evenly flex-wrap gap-2 align-items-center">
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
        </div>
    <?php }else{ ?>
        <div id="body" class="d-flex justify-content-center">
            <div class="w-75 pt-3 pb-4">
                <h1 class="text-center">There is no such data</h1>
            </div>
        </div>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let price = <?php echo $produk['harga_produk'] ?>, qty = 1, temp, currImg = 0, len = $('.img-outside').length ? $('.img-outside').length : 1, heightImage = $('#sectImg').height();
        
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
    </script>
</body>
</html>
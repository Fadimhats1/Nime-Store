<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $dataEdit = isset($_SESSION['dataEdit']) ? $_SESSION['dataEdit'] : '';
    $selectKategori = $lib_user->selectKategori();

    if($dataEdit){
        $selected = $lib_user->selectedProdukKate($dataEdit['id']);
        $image = $lib_user->showImageProduk($dataEdit['id']);
        $flag = $lib_user->checkImage($dataEdit['image_produk_id']);
        $countImage = count($image);
    }

    if(isset($_POST['idDeleteSubKate'])){
        $id = $_POST['idDeleteSubKate'];
        $lib_user->deleteProdukKate($id, $dataEdit['id']);
    }else if(isset($_POST['edits'])){
        $name = $_POST['nameProduk'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['categorySelect'];
        $lib_user->editProduk($dataEdit['id'], $name, $desc, $price, $stock, $category);
        header('Location: manageProduk.php');
    }else if(isset($_POST['save'])){
        $totalImage = isset($_SESSION['countImage']) ? $_SESSION['countImage'] : $countImage;
        $fileName = $_FILES['gambarProduk']['name'];
        $tempFile = $_FILES['gambarProduk']['tmp_name'];
        $fileSize = $_FILES['gambarProduk']['size'];
        $count = count($fileName);
        if($count + $totalImage > 5){
            $_SESSION['error']['totalImage'] = 'Maximum gambar adalah 5 file';
        }else{
            for($i = 0; $i < $count; $i++){
                $lib_user->addImage($dataEdit['id'], $fileName[$i], $tempFile[$i], $fileSize[$i]);
            }
            if(isset($_SESSION['error']['totalImage'])){
                unset($_SESSION['error']['totalImage']);
            }
        }
        if(isset($_SESSION['countImage'])){
            unset($_SESSION['countImage']);
        }
        header('Location: editProduk.php');
    }

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
    <script>
        <?php 
            if(!$login){ ?>
                if(window.confirm("Anda belum login")){
                document.location = 'http://localhost:3000/index.php';
            }else{
                document.location = 'http://localhost:3000/index.php';
            }
        <?php } ?>
    </script>
    <?php include('navbar.php');?>  
    <div id="body" class="p-3 d-flex justify-content-center">
        <div class="gap-3 d-flex flex-column w-50">

            <!-- TITLE PAGE -->
        
            <div class="d-flex justify-content-between bbm">
                <h2>Edit Product</h2>
            </div>

            <!-- BODY ADD PRODUK -->
            <div class="d-flex flex-column gap-4">
                <form id="produkForm" method="POST" action="" class="d-flex flex-column gap-4" enctype="multipart/form-data">
                    <div class="d-flex justify-content-between">
                        <label for="">Id</label>
                        <p class="w-32"><?= $dataEdit['id'] ?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label for="">Name</label>
                        <input class="w-32" type="text" name="nameProduk" value="<?= $dataEdit['nama_produk'] ?>">
                    </div>
                    <div class="d-flex justify-content-between">
                        <label for="desc">Description</label>
                        <textarea class="w-32" name="description" id="desc" cols="20" rows="5"><?= $dataEdit['deskripsi_produk'] ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label for="">Price</label>
                        <div class="w-32">
                            <input type="text" name="price" value="<?= $dataEdit['harga_produk'] ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label for="">Stock</label>
                        <div class="w-32">
                            <input type="text" name="stock" value="<?= $dataEdit['stok_produk'] ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label for="">Category</label>
                        <select class="w-32" name="categorySelect[]" id="category-select" multiple>
                            <?php if($selectKategori){ 
                                foreach($selectKategori as $key){?>
                                <?php $selectSubKategori = $lib_user->showSubKateSelect($key['id']) ?>
                                <optgroup label="<?= $key['nama_kategori'] ?>">
                                    <?php foreach($selectSubKategori as $keys){?>
                                        <option value="<?= $keys['id'] ?>" <?php echo $select = in_array($keys['id'], $selected) ? 'selected' : ''; ?>><?= $keys['nama_subkategori'] ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php }} ?>
                        </select>
                    </div>
                </form>
                <div id="showImage" class="d-flex flex-column gap-4">
                    <div class="mt-3 bbm">
                        <h2>Product Photos</h2>
                    </div>
                    <div id="imgCont" class="d-flex justify-content-evenly flex-wrap gap-3">
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
                </div>
                <div class="d-flex justify-content-center">
                    <button id="edits" name="edits" class="btn btn-primary mt-4 px-4" form="produkForm">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let id = <?php echo $dataEdit['id']; ?>, currId = parseInt(<?php echo $dataEdit['image_produk_id'] ?>), changeId = currId;
        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php } ?>

        $('#category-select').on('select2:unselect', function(e){
            let id = e.params.data.id;
            $.ajax({
                type: 'POST',
                url: '',
                data:{
                    idDeleteSubKate: id
                }
            })
        });
        
        $(body).on('click', 'input[type=radio]', function(){
            $.each($('input[type=radio]'), function(key, value){
                $(this).removeAttr('checked');
            })
            $(this).attr('checked', 'checked');
        })
        
        $('#edits').click(function(){
            $.each($('input[type=radio]'), function(key, value){
                if($(this).attr('checked')){
                    changeId = parseInt($(this).attr('id'));
                }
            })
            if(currId != changeId){
                $.ajax({
                    type: 'POST',
                    url: 'function/produkAjax/updateImage.php',
                    data:{
                        id: id,
                        imageId: changeId
                    } 
                })
            }
        })

        $(body).on('click', '.hapus', function(){
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/deleteImage.php',
                data:{
                    id: id,
                    imageDeleteId: $(this).val()
                }, success: function(data){
                    $('#showImage').html(data);
                }
            })
        })

        $(body).on('change', '#imgInp', function() {
            if(this.files[0]){
                $('#save').removeAttr('disabled');
            }else{
                $('#save').attr('disabled','disabled');
            }
        });
        
    </script>
    <script src="addProduk.js"></script>
</body>
</html>
<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $selectKategori = $lib_user->selectKategori();

    if(isset($_POST['add'])){
        $name = $_POST['nameProduk'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['categorySelect'];
        $fileName = $_FILES['gambarProduk']['name'];
        $tempFile = $_FILES['gambarProduk']['tmp_name'];
        $fileSize = $_FILES['gambarProduk']['size'];
        $link = $lib_user->addProduk($name, $desc, $price, $stock, $category, $fileName, $tempFile, $fileSize);
        header('Location:'.$link);
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
                <h2>Add Product</h2>
            </div>

            <!-- BODY ADD PRODUK -->
            
            <form method="POST" action="" class="d-flex flex-column gap-4" enctype="multipart/form-data">
                <div class="d-flex justify-content-between">
                    <label for="">Name</label>
                    <input class="w-32" type="text" name="nameProduk">
                </div>
                <div class="d-flex justify-content-between">
                    <label for="desc">Description</label>
                    <textarea class="w-32" name="description" id="desc" cols="20" rows="5"></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <label for="">Price</label>
                    <div class="w-32">
                        <input type="text" name="price">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <label for="">Stock</label>
                    <div class="w-32">
                        <input type="text" name="stock">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <label for="">Category</label>
                    <select class="w-32" name="categorySelect[]" id="category-select" multiple>
                        <?php if($selectKategori){ 
                            foreach($selectKategori as $key){?>
                            <?php $selectSubKategori = $lib_user->showSubKateSelect($key['id']) ?>
                            <optgroup label="<?= $key['nama_kategori'] ?>">
                                <?php foreach($selectSubKategori as $key){?>
                                    <option value="<?= $key['id'] ?>"><?= $key['nama_subkategori'] ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php }} ?>
                    </select>
                </div>
                <div class="mt-3 bbm">
                    <h2>Product Photos</h2>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="border border-dark w-50">
                        <input class="p-1" id="gambar" type="file" name="gambarProduk[]" multiple>
                    </div>
                    <p>Total Max Photos 5</p>
                </div>
                <div class="d-flex justify-content-center">
                    <button name="add" class="btn btn-primary mt-4">Add</button>
                </div>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let prevButton = document.querySelector('#prev'), nextButton = document.querySelector('#next'), pageNumber = document.querySelectorAll('.pageNumberContent'), tableBody = document.querySelectorAll('tbody tr'), i = 0;
        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php } ?>
    </script>
    <script src="addProduk.js"></script>
</body>
</html>
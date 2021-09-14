<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    
    // PAGINATION
    $perPage = 4;
    $_SESSION['perPage'] = $perPage;
    $keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';
    $totalPage = $lib_user->searchTotalPage('produk', 'nama_produk', $keyword, $perPage);
    $page = isset($_SESSION['page']) ? $_SESSION['page'] : 1;
    $firstData = ($page-1) * $perPage;

    if(isset($_POST['edit'])){
        $id = $_POST['edit'];
        $_SESSION['dataEdit'] = $lib_user->selectSpecific($id, 'produk');
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
    <div id="body" class="px-5 py-3 gap-3 d-flex flex-column">
        
        <!-- TITLE PAGE -->
        
        <div class="d-flex justify-content-between">
            <h2>Manage Produk</h2>
            <div class="d-flex gap-3 align-items-center">
                <input id="keyword" class="form-control form-control-sm" type="search" placeholder="Cari nama produk...">
                <a id="add" class="btn btn-primary d-flex gap-3 align-items-center h-100" href="addProduk.php">Add<i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <!-- SHOW DATA -->
        <div id="show" class="w-100 d-flex flex-column gap-3">
            <table id="table-produk" class="w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Cost</th>
                        <th>Stock</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $produk = $lib_user->showProduk($keyword, $firstData ,$perPage);
                        if(!$produk){ ?>
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ada</td>
                            </tr>
                    <?php }else{ foreach($produk as $key){?>
                            <tr>
                                <td class="d-flex gap-3 align-items-center">
                                    <img src="<?= $key['nama_image']?>" alt="" width="40px">
                                    <p class="text"><?= $key['nama_produk']?></p>
                                </td>
                                <td>
                                    <?= $key['harga_produk'] ?>
                                </td>
                                <td>
                                    <?= $key['stok_produk'] ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <form method="POST" action="">
                                            <button class="btn btn-primary d-flex gap-3 align-items-center action" value="<?php echo $key['id'] ?>" name="edit"><i class="fas fa-pencil-alt"></i>Edit</button>
                                        </form>

                                        <button class="btn btn-danger d-flex gap-3 align-items-center action" value="<?php echo $key['id'] ?>" name="hapus"><i class="fas fa-trash"></i>Hapus</button>
                                    </div>
                                </td>
                            </tr>
                    <?php }} ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            
            <nav class="d-flex justify-content-end w-100">
                <ul class="pagination">
                    <li id="firstButton" class="page-item">
                        <button class="page-link">First</button>
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
                            <button class="page-link" ><?= $i ?></button>
                        </li>
                    <?php } ?>

                    <li id="lastButton" class="page-item">
                        <button class="page-link">Last</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let firstButton = document.querySelector('#firstButton'), lastButton = document.querySelector('#lastButton'), tableBody = document.querySelectorAll('tbody tr'), i = 0;
        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php } if($page == 1){ ?>
            firstButton.classList.add('disabled');
        <?php }else{ ?>
            firstButton.classList.remove('disabled');
        <?php }if($page == $totalPage){ ?>
            lastButton.classList.add('disabled');
        <?php }else{ ?>
            lastButton.classList.remove('disabled');
        <?php } ?>
        let loadData = $.fn.loadData = function(status = false, id, page, keyword){
            if(status){
                $.ajax({
                    type: 'POST',
                    url: 'function/produkAjax/showTable.php',
                    data:{
                        id: id,
                        page: page,
                        keyword: keyword
                    }, success: function(data){
                        $('#show').html(data);
                    }
                });
            }
        };

        $('#keyword').keyup((e)=>{
            loadData(true, '', 1, $('#keyword').val());
        })

        $('body').on('click', '.page-link' , (e)=>{
            loadData(true, '', $(e.target).text(), $('#keyword').val());
        })

        $('body').on('click', '.action', (e)=>{
            if($(e.target).attr('name') == 'hapus'){
                loadData(true, $(e.target).val(), $('.active').text() , $('#keyword').val());
            }
        })

    </script>
</body>
</html>
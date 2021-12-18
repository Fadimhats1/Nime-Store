<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $dataEdit = isset($_SESSION['dataEdit']) ? $_SESSION['dataEdit'] : '';
    $pages = isset($_SESSION['pages']) ? $_SESSION['pages'] : 1;
    $searchData = isset($_SESSION['searchData']) ? $_SESSION['searchData'] : '';

    // PAGINATION
    $perPage = 4;
    $_SESSION['perPage'] = $perPage;
    $keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';
    $totalPage = $lib_user->SubKategoriPage($keyword, $dataEdit['id'], $perPage);
    $page = isset($_SESSION['page']) ? $_SESSION['page'] : 1;
    $firstData = ($page-1) * $perPage;

    if(isset($_POST['submit'])){
        $subKategori = $_POST['subKategori'];
        $link = $lib_user->addSubKategori($dataEdit['id'], $subKategori);
        header('Location:editKategori.php');
    }else if(isset($_POST['editData'])){
        $judul = $_POST['judul'];
        $id = $_POST['id'];
        $lib_user->editSubKategori($judul, $id, $dataEdit['id']);
        header('Location:editKategori.php');
    }else if(isset($_POST['editKategori'])){
        $nameKategori = $_POST['nameKategori'];
        $fileName = isset($_FILES['iconKategori']['name']) ? $_FILES['iconKategori']['name'] : NULL;
        $tempFile = $_FILES['iconKategori']['tmp_name'];
        $fileSize = $_FILES['iconKategori']['size'];
        $link = $lib_user->editKategori($pages, $dataEdit['id'], $nameKategori, $fileName, $tempFile, $fileSize, $searchData);
        if(!str_contains($link, 'extension') && !str_contains($link, 'size')){
            $loop = array('pages', 'searchData', 'page', 'perPage', 'keyword', 'dataEdit');
            foreach($loop as $key){
                unset($_SESSION[$key]);
            }
        }
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
    <link rel="stylesheet" href="subKategori.css">
</head>
<body>
    <script>
        <?php 
            if(!$login){ ?>
                if(window.confirm("Anda belum login")){
                document.location = 'http://localhost:3000/index.php';
            }
        <?php } ?>
    </script>
    <?php include('navbar.php');?>
    <div id="body" class="p-3 d-flex justify-content-center">
        <div class="gap-3 d-flex flex-column w-50">

        <!-- TITLE PAGE-->
        
            <div class="py-3 d-flex justify-content-between bbm">
                <h2>Edit Categories</h2>
            </div>

        <!-- SHOW DATA -->

            <!-- CATEGORIES -->
            
            <form id="editKategori" method="POST" action="" class="d-flex flex-column gap-4" enctype="multipart/form-data">
                <div class="d-flex justify-content-between">
                    <label for="">ID</label>
                    <p class="w-32 p-1"><?= $dataEdit['id']; ?></p>
                </div>
                <div class="d-flex justify-content-between">
                    <label for="">Name</label>
                    <input class="w-32 p-1" type="text" name="nameKategori" value="<?= $dataEdit['nama_kategori']; ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <label for="gambar">icon</label>
                    <div class="border border-dark">
                        <input class="w-32 p-1" id="gambar" type="file" name="iconKategori">
                    </div>
                </div>
            </form>

            <!-- SUB CATEGORIES -->

            <div class="mt-5 py-3 d-flex justify-content-between bbm">
                <h2>Sub-Categories</h2>
                <div class="d-flex gap-3 align-items-center">
                    <input id="keyword" class="form-control form-control-sm" type="search" placeholder="Search Sub-Categories..." value="<?= $keyword ?>">
                    <button class="btn btn-primary d-flex gap-3 align-items-center h-100" data-bs-toggle="modal" data-bs-target="#addSliderModal">Add<i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div id="show" class="w-100 d-flex flex-column gap-3">
                <table id="table-produk">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="show-data">
                        <?php 
                            $subKategori= $lib_user->showSubKategori($dataEdit['id'], $keyword, $firstData, $perPage);
                            if(!$subKategori){ ?>
                                <tr>
                                    <td colspan="4" class="text-center">Data tidak ada</td>
                                </tr>
                        <?php }else{ foreach($subKategori as $key){ ?>
                                <tr>
                                    <td>
                                        <?= $key['nama_subkategori']?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-3">
                                            <button class="btn btn-primary d-flex gap-3 align-items-center action" value="<?php echo $key['id'] ?>" name="edit"><i class="fas fa-pencil-alt"></i>Edit</button>

                                            <button class="btn btn-danger d-flex gap-3 align-items-center action" value="<?php echo $key['id'] ?>" name="hapus"><i class="fas fa-trash"></i>Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                        <?php }} ?>
                    </tbody>
                </table>

                <!-- PAGINATION -->
            
                <nav id="pagination" class="d-flex justify-content-end w-100">
                    <ul class="pagination mb-0">
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

            <div class="d-flex justify-content-center">
                <button form="editKategori" class="btn btn-primary" name="editKategori">Save Changes</button>
            </div>
        </div>

        <!-- MODAL ADD -->

        <div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content py-3 px-4">
                    <div class="modal-header position-relative">
                        <h3 class="modal-title title">Add Sub-Category</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex flex-column gap-4" action="" method="POST" enctype="multipart/form-data">
                            <div class="d-flex justify-content-between">
                                <label for="judul">Name of Categories</label>
                                <p class="w-16 p-1"><?= $dataEdit['nama_kategori'] ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="category-select">Sub-Categories</label>
                                <select class="w-16 p-1" name="subKategori[]" id="category-select" multiple></select>
                            </div>
                            <input type="submit" name="submit" class="btn btn-primary mt-3">
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MODAL BUATAN SENDIRI -->
        
        <div class="bgModal opacOff">
            
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="select2SubCategory.js"></script>
    <script>
        let modal = document.querySelector('.bgModal'), closeModal = document.querySelector('.closeModal'), firstButton = document.querySelector('#firstButton'), lastButton = document.querySelector('#lastButton'), pageNumber = document.querySelectorAll('.pageNumberContent'), tableBody = document.querySelectorAll('tbody tr'), idKategori = <?php echo $dataEdit['id'] ?>;

        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php }if($page == 1){ ?>
            firstButton.classList.add('disabled');
        <?php }else{ ?>
            firstButton.classList.remove('disabled');
        <?php }if($page == $totalPage){ ?>
            lastButton.classList.add('disabled');
        <?php }else{ ?>
            lastButton.classList.remove('disabled');
        <?php } ?>
        let j = pageNumber.length;
        for(let i = 0; i < j; i++){
            let temp = pageNumber[i].textContent;
            if(pageNumber[i].classList.contains('active')){
                pageNumber[i].parentElement.classList.remove('active');
            }else if(temp == <?= $page ?>){
                pageNumber[i].parentElement.classList.add('active');
            }
        }

        let loadData = $.fn.loadData = function(status = false, id, page, keyword){
            if(!false){
                $.ajax({
                    type: 'POST',
                    url: 'function/subKategoriAjax/showTable.php',
                    data:{
                        idKategori: idKategori,
                        id: id,
                        page: page,
                        keyword: keyword
                    },success: function(data){
                        $('#show').html(data);
                    }
                });
            }
        }, action = $.fn.action = function(type, id, page, keyword){
            if(type === 'hapus'){
                loadData(true, id, page, keyword);
            }else if(type === 'edit'){
                $('.bgModal').removeClass('opacOff')
                $('.bgModal').addClass('opacOn');
                $.ajax({
                    type: 'POST',
                    url: 'function/subKategoriAjax/editData.php',
                    data:{
                        id: id
                    },success: function(data){
                        $('.bgModal').html(data);
                    }
                })
            }
        }

        $('#keyword').keyup((e)=>{
            loadData(true, '', 1, e.target.value);
        })
        
        $('body').on('click', '.page-link' , (e)=>{
            loadData(true, '', $(e.target).text(), $('#keyword').val());
        })
        
        $('body').on('click', '.action', (e)=>{
            action($(e.target).attr('name'), $(e.target).val(), $('.active').text() , $('#keyword').val());
        })

        $('.bgModal').on('click', '.btn-close', (e)=>{
            $('.bgModal').removeClass('opacOn')
            $('.bgModal').addClass('opacOff');
        })
        

    </script>
</body>
</html>
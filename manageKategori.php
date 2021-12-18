<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $dataEdit = isset($_SESSION['dataEdit']) ? $_SESSION['dataEdit'] : '';

    // PAGINATION
    $perPage = 4;
    $tp = $lib_user->totalPage($perPage,'kategori');
    if(isset($_GET['page'])){
        $page = is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $page = $page > $tp ? $tp : $page;
        $page = $page > 0 ? $page : 1;
    }else{
        $page = 1;  
    }
    $firstData = ($page-1) * $perPage;

    // SEARCH KATEGORI
    $searchData = NULL;
    if(isset($_POST['search'])){
        $searchData = $_POST['searchData'];
        header('Location: manageKategori.php?'.$searchData = $searchData ? 'search='.$searchData.'&page=1' : ''.'page=1');
    }else if(isset($_GET['search'])){
        $searchData = str_replace('%20', '', $_GET['search']);
        $tp = $lib_user->searchTotalPage('kategori', 'nama_kategori', $searchData, $perPage);
    }

    // ADD KATEGORI
    if(isset($_POST['submit'])){
        $judul = $_POST['judul'];
        $subCategory = isset($_POST['subCategory']) ? $_POST['subCategory'] : $judul;
        $fileName = $_FILES['gambar']['name'];
        $tempFile = $_FILES['gambar']['tmp_name'];
        $fileSize = $_FILES['gambar']['size'];
        $link = $lib_user->addKategori($judul, $subCategory, $fileName, $tempFile, $fileSize);
        header('Location:'.$link);
    }

    // BUTTON EDIT KATEGORI
    if(isset($_POST['edit'])){
        $idEdit = $_POST['idEdit'];
        $dataEdit = $lib_user->selectSpecific($idEdit, 'kategori');
        $_SESSION['dataEdit'] = $dataEdit;
        $_SESSION['pages'] = $page;
        $_SESSION['searchData'] = $searchData;
        header('Location: editKategori.php');
    }

    // DELETE KATEGORI
    if(isset($_POST['hapus'])){
        $idHapus = $_POST['idHapus'];
        $lib_user->delete($idHapus, 'kategori');
        $totalPage = $lib_user->totalPage($perPage, 'kategori');
        $totalSearchPage = $lib_user->searchTotalPage('kategori', 'nama_kategori', $searchData, $perPage);
        $tp = $searchData ? $totalSearchPage : $totalPage;
        $page = $page > $tp ? $tp : $page;
        $page = $page > 0 ? $page : 1;
        header('Location: manageKategori.php?'.$searchData = $searchData ? 'search='.$searchData.'&page='.$page : ''.'page='.$page);
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
    <?php include('navbar.php') ?>
    <div id="body" class="px-5 py-3 gap-3 d-flex flex-column">
        
        <!-- DATA SHOW SLIDER -->
        
        <div class="d-flex justify-content-between">
            <h2>Manage Kategori</h2>
            <div class="d-flex gap-3 align-items-center">
                <form method="POST" action="" class="d-flex h-2 flex-row">
                    <input class="form-control form-control-sm" type="search" placeholder="Cari Kategori" name="searchData">
                    <button class="btn btn-primary" name="search">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button class="btn btn-primary d-flex gap-3 align-items-center h-100" data-bs-toggle="modal" data-bs-target="#addSliderModal">Add<i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Icon</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $kategori = $lib_user->searchKategori($searchData, $firstData, $perPage);
                    if(!$kategori){?>
                        <tr>
                            <td colspan="3" class="text-center">Data tidak ada</td>
                        </tr>
                    <?php }else{ foreach ($kategori as $key){ ?>
                        <tr>
                            <?php
                                $namaFile = str_replace('image/kategori/', '', $key['icon_kategori']);
                            ?>
                            <td scope="col">
                                <?php echo $key['nama_kategori']; ?>
                            </td>
                            <td scope="col">
                                <div class="d-flex gap-3">
                                    <img src="<?php echo $key['icon_kategori']; ?>" alt="" width="40px;">
                                    <p><?= $namaFile ?></p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-3">
                                    <form method="POST" action="">
                                        <input type="hidden" name="idEdit" value="<?php echo $key['id'] ?>">
                                        <button class="btn btn-primary d-flex gap-3 align-items-center" name="edit"><i class="fas fa-pencil-alt"></i>Edit</button>
                                    </form>

                                    <form method="POST" action="">
                                        <input type="hidden" name="idHapus" value="<?php echo $key['id'] ?>">
                                        <button class="btn btn-danger d-flex gap-3 align-items-center" name="hapus"><i class="fas fa-trash"></i>Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php }}?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->

        <nav class="d-flex justify-content-center w-100">
            <ul class="pagination">
                <li id="prev" class="page-item"><a class="page-link" href="manageKategori.php?<?= $search = $searchData ? 'search='.$searchData.'&' : ''; ?>page=1">First</a></li>
                <?php
                $totalPage = $tp > 0 ? $tp : 1; 
                $j = $page + 1 <= $totalPage ? $page + 1 : $totalPage;
                $j = $page == 1 && $page + 1 < $totalPage ? 3 : $j;
                $firstPage = $page == 1 ? 1 : $page-1;
                for($i = $page == $totalPage && $page-2 > 0 ? $page-2 : $firstPage; $i <= $j; $i++){ ?> 
                    <li class="page-item pageNumber"><a class="page-link pageNumberContent" href="manageKategori.php?<?= $search = $searchData ? 'search='.$searchData.'&' : ''; ?>page=<?= $i ?>"><?= $i ?></a></li>
                <?php } ?>
                <li id="next" class="page-item"><a class="page-link" href="manageKategori.php?<?= $search = $searchData ? 'search='.$searchData.'&' : ''; ?>page=<?= $totalPage ?>">Last</a></li>
            </ul>
        </nav>

        <!-- MODAL ADD -->

        <div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content py-3 px-4">
                    <div class="modal-header position-relative">
                        <h5 class="modal-title title">Add Slider</h5>
                        <button class="modal-title back position-absolute "><h5 class="modal-title"><i class="fas fa-arrow-left"></i> Kembali</h5></button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex flex-column gap-4" action="" method="POST" enctype="multipart/form-data">
                            <div class="d-flex justify-content-between">
                                <label for="judul">Name</label>
                                <input class="w-16 p-1" id="judul" name="judul" type="text" placeholder="Judul">
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="category-select">Sub-Categories</label>
                                <select class="w-16 p-1" name="subCategory[]" id="category-select" multiple></select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="gambar">Icon</label>
                                <div class="border border-dark">
                                    <input class="w-16 p-1" id="gambar" type="file" name="gambar">
                                </div>
                            </div>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary mt-3">
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let prevButton = document.querySelector('#prev'), nextButton = document.querySelector('#next'), pageNumber = document.querySelectorAll('.pageNumberContent');
        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php }if($page == 1){ ?>
            prevButton.classList.add('disabled');
        <?php }else{ ?>
            prevButton.classList.remove('disabled');
        <?php }if($page == $totalPage){ ?>
            nextButton.classList.add('disabled');
        <?php }else{ ?>
            nextButton.classList.remove('disabled');
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
    </script>
    <script src="select2SubCategory.js"></script>
</body>
</html>
<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $dataEdit = isset($_SESSION['dataEdit']) ? $_SESSION['dataEdit'] : '';
    // Pagination
    $perPage = 3;
    $tp = $lib_user->totalPage($perPage, 'sliders');
    if(isset($_GET['page'])){
        $page = is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $page = $page > $tp ? $tp : $page;
        $page = $page > 0 ? $page : 1;
    }else{
        $page = 1;  
    }
    $firstData = ($page-1) * $perPage;

    // ADD SLIDER
    if(isset($_POST['submit'])){
        $judul = $_POST['judul'];
        $sequences = $_POST['sequences'];
        $mulai = $_POST['mulai'];
        $berakhir = isset($_POST['berakhir']) ? $_POST['berakhir'] : NULL;
        $hyperlink = $_POST['hyperlink'];
        $fileName = $_FILES['gambar']['name'];
        $tempFile = $_FILES['gambar']['tmp_name'];
        $fileSize = $_FILES['gambar']['size'];
        $link = $lib_user->addSlider($judul, $fileName, $tempFile, $fileSize, $hyperlink, $mulai, $berakhir, $sequences);
        header('Location:'.$link);
    }
    // BUTTON EDIT SLIDER
    if(isset($_POST['edit'])){
        $idEdit = $_POST['idEdit'];
        $dataEdit = $lib_user->selectSpecific($idEdit, 'sliders');
        $_SESSION['dataEdit'] = $dataEdit;
        header('Location: manageSlider.php?page='.$page.'&id='.$dataEdit['id']);
    }
    // DELETE SLIDER
    if(isset($_POST['hapus'])){
        $idHapus = $_POST['idHapus'];
        $lib_user->delete($idHapus, 'sliders');
        $tp = $lib_user->totalPage($perPage, 'sliders');
        $page = $page > $tp ? $tp : $page;
        $page = $page > 0 ? $page : 1;
        header('Location: manageSlider.php?page='.$page);
    }
    // UPDATE SLIDER
    if(isset($_POST['saveChanges'])){
        $page = $_POST['page'];
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $seqOld = $_POST['sequencesOld'];
        $seqNew = $_POST['sequencesNew'];
        $mulai = $_POST['mulai'];
        $berakhir = isset($_POST['berakhir']) ? $_POST['berakhir'] : NULL;
        $hyperlink = $_POST['hyperlink'];
        $fileName = isset($_FILES['gambar']['name']) ? $_FILES['gambar']['name'] : NULL;
        $tempFile = $_FILES['gambar']['tmp_name'];
        $fileSize = $_FILES['gambar']['size'];
        $link = $lib_user->editSlider($page, $id, $judul, $fileName, $tempFile, $fileSize, $hyperlink, $mulai, $berakhir, $seqNew, $seqOld);
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
    <link rel="stylesheet" href="style.css">
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
    <div id="body" class="px-5 py-3 gap-3 d-flex flex-column">
        
        <!-- DATA SHOW SLIDER -->
        
        <div class="d-flex justify-content-between">
            <h2>Manage Slider</h2>
            <button class="btn btn-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#addSliderModal">Add<i class="fas fa-plus"></i>
            </button>
        </div>
        <div>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope="col">Sequence</th>
                        <th scope="col">Name</th>
                        <th scope="col">Image</th>
                        <th scope="col">Hyperlink</th>
                        <th scope="col">Start At</th>
                        <th scope="col">End At</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $slides = $lib_user->showSlider($firstData, $perPage);
                    if(!$slides){?>
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ada</td>
                        </tr>
                    <?php }else{ foreach ($slides as $key){ ?>
                        <tr>
                            <td scope="col"><?php echo $key['sequences']; ?></td>
                            <td scope="col"><?php echo $key['nama_slides']; ?></td>
                            <td scope="col">
                                <div class="w-8">
                                    <img src="<?php echo $key['gambar_slides']; ?>" alt="">
                                </div>
                            </td>
                            <td scope="col"><?php echo $key['hyperlink']; ?></td>
                            <td scope="col"><?php echo $key['mulai']; ?></td>
                            <td scope="col"><?php echo $key['akhir']; ?></td>
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
                <li id="prev" class="page-item"><a class="page-link" href="manageSlider.php?page=<?= $page - 1 ?>">Previous</a></li>
                <?php
                $tp = $lib_user->totalPage($perPage, 'sliders');
                $totalPage = $tp > 0 ? $tp : 1; 
                $j = $page + 1 <= $totalPage ? $page + 1 : $totalPage;
                $j = $page == 1 && $page + 1 < $totalPage ? 3 : $j;
                $firstPage = $page == 1 ? 1 : $page-1;
                for($i = $page == $totalPage && $page-2 > 0 ? $page-2 : $firstPage; $i <= $j; $i++){ ?> 
                    <li class="page-item pageNumber"><a class="page-link pageNumberContent" href="manageSlider.php?page=<?= $i ?>"><?= $i ?></a></li>
                <?php } ?>
                <li id="next" class="page-item"><a class="page-link" href="manageSlider.php?page=<?= $page + 1 ?>">Next</a></li>
            </ul>
        </nav>


        <!-- MODAL ADD SLIDER -->


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
                                <label for="sequences">Sequences</label>
                                <input class="w-16 p-1" id="sequences" name="sequences" type="text" placeholder="1">
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="mulai">Start At</label>
                                <input class="w-16 p-1" id="mulai" name="mulai" type="date" placeholder="start">
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="berakhir">End At</label>
                                <input class="w-16 p-1" id="berakhir" name="berakhir" type="date" placeholder="end">
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="hyperlink">Hyperlink</label>
                                <textarea class="w-16 p-1" id="hyperlink" name="hyperlink" type="text" placeholder="hyperlink" cols="16" rows="4"></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <label for="gambar">Image</label>
                                <div class="border border-dark">
                                    <input class="w-16 p-1" id="gambar" type="file" name="gambar">
                                </div>
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
        
        <?php if(isset($_GET['id'])){?>
            <div class="bgModal">
                <div class="cardModal w-24 h-24">
                    <div class="contentModal d-flex flex-column justify-content-between gap-3">
                        <div class="headerModal d-flex justify-content-between align-items-center bbm">
                            <h3>TEXT</h3>
                            <button type="button" class="btn-close closeModal" aria-label="Close"></button>
                        </div>
                        <div class="bodyModal">
                            <form class="d-flex flex-column gap-3" action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="page" value="<?= $page ?>">
                                <div class="d-flex justify-content-between gap-4">
                                    <input type="hidden" name="id" value="<?= $dataEdit['id'] ?>">
                                    <label for="">ID</label>
                                    <p class="w-16 p-1"><?= $dataEdit['id'] ?></p>
                                </div>

                                <div class="d-flex justify-content-between gap-4">
                                    <label for="sequences">Sequences</label>
                                    <input type="hidden" name="sequencesOld" value="<?= $dataEdit['sequences']?>">
                                    <input class="w-16 p-1" id="sequences" name="sequencesNew" type="text" placeholder="1" value="<?php echo $dataEdit['sequences'] ?>">
                                </div>

                                <div class="d-flex justify-content-between gap-4">
                                    <label for="judul">Name</label>
                                    <input class="w-16 p-1" id="judul" name="judul" type="text" placeholder="Judul" value="<?php echo $dataEdit['nama_slides'] ?>">
                                </div>
                                
                                <div class="d-flex justify-content-between gap-4">
                                    <label for="hyperlink">Hyperlink</label>
                                    <textarea class="w-16 p-1" id="hyperlink" name="hyperlink" type="text" placeholder="hyperlink" cols="16" rows="4"><?php echo $dataEdit['hyperlink'] ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between gap-4">
                                    <label for="mulai">Start At</label>
                                    <input class="w-16 p-1" id="mulai" name="mulai" type="date" placeholder="start" value="<?php echo $dataEdit['mulai'] ?>">
                                </div>

                                <div class="d-flex justify-content-between gap-4">
                                    <label for="berakhir">End At</label>
                                    <input class="w-16 p-1" id="berakhir" name="berakhir" type="date" placeholder="end" value="<?php echo $dataEdit['akhir'] ?>">
                                </div>
                                
                                <div class="d-flex justify-content-between gap-4">
                                    <label for="gambar">Image</label>
                                    <div class="border border-dark">
                                        <input class="w-16 p-1" id="gambar" type="file" name="gambar">
                                    </div>
                                </div>
                                <button name="saveChanges" class="btn btn-primary mt-3">Save Changes</button>
                            </form>
                        </div>
                        <div class="footerModal d-flex justify-content-between btp">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let modal = document.querySelector('.bgModal'), closeModal = document.querySelector('.closeModal'), prevButton = document.querySelector('#prev'), nextButton = document.querySelector('#next'), pageNumber = document.querySelectorAll('.pageNumberContent');
        <?php if(isset($_GET['extension'])){ ?>
            alert('format file harus JPEG, JPG, atau PNG');
        <?php }else if(isset($_GET['size'])){?>
            alert('size file lebih dari 2MB');
        <?php }else if(isset($_GET['sequences'])){?> 
            alert('Sequence sudah ada, silahkan pilih yang lain');
        <?php }if(isset($_GET['id'])){ ?>
            modal.classList.remove('opacOff');
            modal.classList.add('opacOn');
            window.addEventListener('click', (e)=>{
                if(e.target == modal){
                    modal.classList.add('opacOff');
                }
            });
            closeModal.addEventListener('click', ()=>{
                modal.classList.remove('opacOn');
                modal.classList.add('opacOff');
            });
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
</body>
</html>
<?php 
    include('../libraryUser.php');
    $lib_user = new Library();
    session_start();

    if(isset($_POST['id'])){
        $lib_user->delete($_POST['id'], 'sub_kategori');
    }
    
    $idKategori = $_POST['idKategori'];
    $keyword = $_POST['keyword'];
    $page = $_POST['page'];
    $flag = is_numeric($page);
    $perPage = $_SESSION['perPage'];
    $totalPage = $lib_user->SubKategoriPage($keyword, $_SESSION['dataEdit']['id'], $perPage);
    if(!$flag){
        $page = $page == 'First' ? 1 : $totalPage;
    }else if($page > $totalPage){
        if($totalPage == 0){
            $page = 1;
        }else{
            $page = $totalPage;
        }
    }
    $firstData = ($page - 1) * $perPage;
    $subKategori = $lib_user->showSubKategori($idKategori, $keyword, $firstData, $perPage);
    $_SESSION['page'] = $page;
    $_SESSION['keyword'] = $keyword;
?>
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
                if(!$subKategori){ ?>
                    <tr>
                        <td colspan="4" class="text-center">Data tidak ada</td>
                    </tr>
            <?php }else{ foreach($subKategori as $key){?>
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
            <li id="firstButton" class="page-item <?= $disabled = $page == 1 ? 'disabled' : '' ?>">
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
                    <button  class="page-link" ><?= $i ?></button>
                </li>
            <?php } ?>

            <li id="lastButton" class="page-item <?= $disabled = $page == $totalPage ? 'disabled' : '' ?>">
                <button class="page-link">Last</button>
            </li>
        </ul>
    </nav>
</div>

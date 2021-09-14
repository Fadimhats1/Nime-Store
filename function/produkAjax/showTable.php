<?php 
    include('../libraryUser.php');
    $lib_user = new Library();
    session_start();

    if(isset($_POST['id'])){
        $lib_user->delete($_POST['id'], 'produk');
    }

    $keyword = $_POST['keyword'];
    $perPage = $_SESSION['perPage'];
    $page = $_POST['page'];
    $flag = is_numeric($page);
    $totalPage = $lib_user->searchTotalPage('produk', 'nama_produk', $keyword, $perPage);
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
    $produk = $lib_user->showProduk($keyword, $firstData, $perPage);
    $_SESSION['page'] = $page;
    $_SESSION['keyword'] = $keyword;
?>
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
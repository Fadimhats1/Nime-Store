<?php 
    include('../libraryUser.php');
    $lib_user = new Library();
    $id = $_POST['id'];
    $dataEditSub = $lib_user->selectSpecific($id, 'sub_kategori');
?>

<div class="cardModal">
    <div class="contentModal d-flex flex-column justify-content-between gap-3">
        <div class="headerModal d-flex justify-content-between align-items-center bbm">
            <h3>Edit Sub-Categories</h3>
            <button class="btn-close closeModal" aria-label="Close"></button>
        </div>
        <div class="bodyModal">
            <form id="formEdit" class="d-flex flex-column gap-3" action="" method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-between gap-8">
                    <input type="hidden" name="id" value="<?= $dataEditSub['id'] ?>">
                    <label for="">ID</label>
                    <p class="w-16 p-1"><?= $dataEditSub['id'] ?></p>
                </div>

                <div class="d-flex justify-content-between gap-8">
                    <label for="judul">Name</label>
                    <input class="w-16 p-1" id="judul" name="judul" type="text" placeholder="Judul" value="<?php echo $dataEditSub['nama_subkategori'] ?>">
                </div>
                <button name="editData" class="btn btn-primary mt-3">Save Changes</button>
            </form>
        </div>
        <div class="footerModal d-flex justify-content-between btp">
            <div class="d-flex justify-content-between align-items-center gap-8 py-2">
                <label for="">Last Updated</label>
                <p class="w-16 p-1"><?= $dataEditSub['last_updated'] ?></p>
            </div>
        </div>
    </div>
</div>
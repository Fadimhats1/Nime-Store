<div id="body" class="d-flex justify-content-center">
    <div class="w-75 pt-3 pb-4">
        <div>
            <div>
                Navbar
            </div>
            <div id="section-1" class="row row-cols-1 row-cols-sm-6 g-4">

            </div>
            <div class="d-flex justify-content-center my-4">
                <button id="loadMore" class="btn btn-primary hide">click to see other recent items</button>
            </div>
        </div>
    </div>
</div>
<script>
    var totalPage = <?php echo $lib_user->searchTotalPage('produk', 'nama_produk', '', 24) ?>
</script>
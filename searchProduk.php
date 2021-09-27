<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    session_start();

    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';

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
    <?php include('navbar.php'); ?>
    <div id="body" class="d-flex justify-content-center pt-3 pb-5">
        <div class="w-75 pt-3 pb-4 d-flex gap-4">
            <div class="card p-3 d-flex flex-column gap-3" style="width: 18rem;">
                <div class="d-flex flex-column gap-3">
                    <h5>Price</h5>
                    <input class="mt-1 p-1" type="text">
                    <input class="p-1" type="text">
                </div>
                <hr>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Category</h5>
                        <p class="text-primary" role="button">Reset</p>
                    </div>
                    <div class="text-primary">
                        <ul class="list-category">
                            <li class="d-flex align-items-center" role="button" value="">
                                <p class="flex-grow-1">asdasd</p>
                                <i class="fas fa-check"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card flex-grow-1 p-3 d-flex flex-column" style="width: 18rem;">
                
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        let search = [];
        $('li').on('click', function(){
            let searchKate = $(this).val();
            if($(this).children('i').is(':visible')){
                $(this).children('i').hide();
                search = search.filter(data => data != searchKate);
            }else{
                $(this).children('i').show();
                search.push(searchKate);
            }
            
        })
        let searchByProduk = $.fn.searchByProduk = function(search){
            $.ajax({
                type: 'POST',
                url: 'function/produkAjax/searchByProduk.php',
                data:{
                    searchKate: search,
                    keyword: keyword
                }, success: function(data){

                }
            })
        }
    </script>
</body>
</html>
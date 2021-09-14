<?php 
    include('function/libraryUser.php');
    $lib_user = new Library();
    if(isset($_POST['add'])){
        $nama_user = $_POST['nama'];
        $email_user = $_POST['email'];
        $pass_user = $_POST['pass'];
        $gender_user = $_POST['gender'];
        $data_user = $lib_user->add_user($nama_user, $email_user, $pass_user, $gender_user);
        if($data_user){
            header('Location:index.php');
        }
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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleRegister.css">
</head>
<body>
    <div id="body" class="bg-primary d-flex align-items-center justify-content-end p-4">
        <div id="body-bg"></div>
        <div class="form-wrapper d-flex flex-column justify-content-center align-items-center bg-light shadow">
            <div class="title-form">
                <h5 class="text-center">SIGN UP</h5>
                <a class="text-end" href=""></a>
            </div>
            <form action="" class="form-register gap-2" method="POST" enctype="multipart/form-data">
                <!-- <div class="image-def-wrapper">
                    <img class="image-default" src="image/default.png" alt="image">
                    <input type="file" name="image" id="input_file" hidden>
                    <label for="input_file" class="btn btn-primary button-image-default">
                        <i class="fas fa-pencil-alt"></i>
                    </label>
                </div> -->
                <label for="">Username</label>
                <input class="form-control" type="text" name="nama" placeholder="Username Kamu">
                <label for="">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Email Kamu">
                <label for="">Password</label>
                <input class="form-control" type="password" name="pass" id="pass" placeholder="Ayo Password nya">
                <label for="">Confirm Password</label>
                <input class="form-control" type="password" id="pass-confirm" placeholder="Passwordnya Lagi Yaa">
                <label for="">Gender</label>
                <div class=" gender d-flex flex-wrap">
                    <div>
                        <input class="form-check-input" type="radio" id="male" name="gender" value="laki-laki">
                        <label for="male">Laki - laki</label>
                    </div>
                    <div>
                        <input class="form-check-input" type="radio" id="female" name="gender" value="perempuan">
                    <label for="female">Perempuan</label>
                    </div>
                </div>
                <input type="submit" name="add" class="btn btn-primary mt-3">
            </form>
        </div>
    </div>
</body>
</html>
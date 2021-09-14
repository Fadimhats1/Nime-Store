<?php 
    include('function/libraryUser.php');
    $lib_user = new Library;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>

    <div id="box" style="width: 20rem; min-height: 8rem; border: 1px solid black"></div>
    <select name="" id="" style="width: 20rem; min-height: 8rem;" multiple>

        <option value="">1</option>
        <option value="">2</option>
        <option value="">3</option>
        <option value="">4</option>
    </select>
<script>
    $('option').click(function(e){
        $('#box').append(`<span onclick="rem(this)">${e.target.innerHTML}</span>`)
    });

    function rem(e){
        e.remove();
    }

    class kucing{
        energi = 1000;
        lari(ujang){
            console.log(ujang);
        }
        loncat(){
            let mood = 'senang', test = 'rika';
            console.log(this.energi);
            console.log(mood);
            this.lari(test)
        }
    }

        let kucings = new kucing();
        kucings.loncat();
</script>
</body>
</html>
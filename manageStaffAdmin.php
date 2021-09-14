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

  <div id="body" class="p-5">
    <div class="wrapper-table d-flex flex-column">
      <div id="staf-admin" class="d-flex flex-column align-items-center">
        <table class="table table-dark table-striped">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody id="row-admin">
            
          </tbody>
        </table>
        <!-- PAGINATION -->
        <nav id="page-button-admin">
          <ul class="pagination">

          </ul>
        </nav>
      </div>
      <div id="user" class="d-flex flex-column align-items-center">
        <table class="table table-dark table-striped">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody id="row-user">
            
          </tbody>
        </table>
        <!-- PAGINATION -->
        <nav id="page-button-user">
          <ul class="pagination">

          </ul>
        </nav>
      </div>
    </div>
    
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  <script src="script.js"></script>
  <script>

    // USER
    const rowUser = document.querySelector('#row-user'), pageButtonUser = document.querySelector('#page-button-user .pagination');
    let currPageUser = 1, perPage = 8;

    function dataUser(){
      <?php 
        $items = $lib_user->show('user');
      ?>
      let items = <?php echo json_encode($items) ?>;
      return items;
    }

    function pageUser(){
      let items = dataUser();
      console.log(items);
      if(!items[0]){
        rowUser.innerHTML = `<td colspan="5">Tidak ada data</td>`;
      }else{
        pageData = paginator(items, currPageUser, perPage);
        let start = 1, end = 5, flag = 0, totalPage = pageData.total_pages, noUser = 1;
        pageShow(items, noUser);
        pageButtonGen(totalPage, flag, start, end);
        //pageButton(items, start, end, totalPage);
      }
    }

    function pageShow(items, noUser){
      let pageData = paginator(items, currPageUser, perPage);
      rowUser.innerHTML = ``;
      pageData.data.forEach((e)=>{
        rowUser.innerHTML += `
          <tr>
            <th>${noUser}</th>
            <td>${e.nama_user}</td>
            <td>${e.email_user}</td>
            <td>${e.role}</td>
            <td>
              <div class="d-flex justify-content-center gap-3">
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Action</button>
                  <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item role" href="#">Admin</a></li>
                    <li><a class="dropdown-item role" href="#">Staff</a></li>
                  </ul>
                </div>
                <button class="btn btn-danger">Hapus</button>
              </div>
            </td>
          </tr>`
      ++noUser; });
    }

    function pageButton(items, start, end, totalPage){
      const buttonNumberUser = Array.from(document.querySelectorAll('.button-number-user'));
      buttonNumberUser[0].classList.add('active');
      $('.button-number-user').click((e)=>{
        let flag = 1;
        buttonNumberUser.forEach((e) =>{
          if(e.classList.contains('active')){
            e.classList.remove('active');
          }
        })
        currPageUser = parseInt(e.target.innerHTML);
        if(totalPage > 5){
          start = currPageUser - 2 > 1 ? currPageUser - 2 : 1;
          end = currPageUser + 2 < totalPage ? currPageUser + 2 : totalPage;
          if(end < 5){
            end = 5;
          }
          if(end === totalPage){
            start = end - 4;
          }
        }else{
          end = totalPage;
        }
        pageShow(items,(currPageUser * 8) - 7);
        pageButtonGen(totalPage, flag, start, end);
        buttonNumberUser.forEach((e) =>{
          let temp = parseInt(e.children[0].innerHTML);
          if(temp == currPageUser){
            e.classList.add('active');
          }
        })
      });

      $('.button-prev-user').click(()=>{
        const buttonNumberUser = Array.from(document.querySelectorAll('.button-number-user'));
        let flag = 1;
        buttonNumberUser.forEach((e) =>{
          if(e.classList.contains('active')){
            e.classList.remove('active');
          }
        })
        currPageUser = 1;
        start = 1;
        end = totalPage < 5 ? totalPage : 5;
        pageShow(items, (currPageUser * 8) - 7);
        pageButtonGen(totalPage, flag, start, end);
        buttonNumberUser.forEach((e) =>{
          let temp = parseInt(e.children[0].innerHTML);
          if(temp == currPageUser){
            e.classList.add('active');
          }
        })
      });

      $('.button-next-user').click(()=>{
        const buttonNumberUser = Array.from(document.querySelectorAll('.button-number-user'));
        let flag = 1;
        buttonNumberUser.forEach((e) =>{
          if(e.classList.contains('active')){
            e.classList.remove('active');
          }
        })
        currPageUser = totalPage;
        end = totalPage;
        start = totalPage - 4;
        pageShow(items, (currPageUser * 8) - 7);
        pageButtonGen(totalPage, flag, start, end);
        buttonNumberUser.forEach((e) =>{
          let temp = parseInt(e.children[0].innerHTML);
          if(temp == currPageUser){
            e.classList.add('active');
          }
        })
      });
    }

    function pageButtonGen(totalPage, flag, start, end){
      if(!flag){
        pageButtonUser.innerHTML += `
          <li class="page-item button-prev-user hide">
            <button class="page-link">First</button>
          </li>
        `
        if(totalPage <= 5){
          for(let i = 1; i <= totalPage; i++){
            pageButtonUser.innerHTML += `
              <li class="page-item button-number-user">
                <button class="page-link">${i}</button>
              </li>
            `
          }
        }else{
          for(let i = 1; i <= 5; i++){
            pageButtonUser.innerHTML += `
              <li class="page-item button-number-user">
                <button class="page-link">${i}</button>
              </li>
            `
          }
        }
        pageButtonUser.innerHTML += `
          <li class="page-item button-next-user hide">
            <button class="page-link">Last</button>
          </li>
        `
        if(totalPage > 5){
          const buttonNextUser = document.querySelector('.button-next-user');
          buttonNextUser.classList.remove('hide');
          buttonNextUser.classList.add('show');
        }
      }else{
        const buttonPrevUser = document.querySelector('.button-prev-user'), buttonNextUser = document.querySelector('.button-next-user'), buttonNum = document.querySelectorAll('.button-number-user button');
        if(start === 1){
          buttonPrevUser.classList.remove('show');
          buttonPrevUser.classList.add('hide');
        }else{
          buttonPrevUser.classList.remove('hide');
          buttonPrevUser.classList.add('show');
        }
        if(totalPage > 5){
          for(let i = 0, j = start; j <= end; i++, j++){
            buttonNum[i].innerHTML = j;
          }
        }
        if(end === totalPage){
          buttonNextUser.classList.remove('show');
          buttonNextUser.classList.add('hide');
        }else{
          buttonNextUser.classList.remove('hide');
          buttonNextUser.classList.add('show');
        }
      }
    }

    pageUser();
    
    // USER

    // ADMIN
    const rowAdmin = document.querySelector('#row-admin'), pageButtonAdmin = document.querySelector('#page-button-admin .pagination');
    var currPageAdmin = 1;

    function dataAdmin(){
      <?php $items = $lib_user->show('admin'); ?>

      let items = <?php echo json_encode($items) ?>;

      return items;
    }

    function pageAdmin(){
      let items = dataAdmin();
      if(!items[0]){
        rowAdmin.innerHTML = `<td colspan="5">Tidak ada data</td>`
      }else{
        let noAdmin = 1;
        pageShowAdmin(items, noAdmin);
        pageButtonConAdmin(items);
      }
    }

    function pageShowAdmin(items, noAdmin){
      let pageData = paginator(items, currPageAdmin, perPage);
      rowAdmin.innerHTML = ``;
      pageData.data.forEach((e)=>{
        rowAdmin.innerHTML += `
          <tr>
            <th>${noAdmin}</th>
            <td>${e.nama_user}</td>
            <td>${e.email_user}</td>
            <td>${e.role}</td>
            <td>
              <div class="d-flex justify-content-center gap-3">
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Action</button>
                  <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item role" href="#">Admin</a></li>
                    <li><a class="dropdown-item role" href="#">Staff</a></li>
                  </ul>
                </div>
                <button class="btn btn-danger">Hapus</button>
              </div>
            </td>
          </tr>`
      ++noAdmin; });
    }

    function pageButtonConAdmin(items){
      let pageData = paginator(items, currPageAdmin, perPage), totalPage = pageData.total_pages, start = 0, end = 5;
      pageButtonGenAdmin(totalPage);
      const buttonNextAdmin = document.querySelector('.button-next-admin'), buttonPrevAdmin = document.querySelector('.button-prev-admin'), buttonNumberAdmin = Array.from(document.querySelectorAll('.button-number-admin'));
      if(totalPage > 5){
        buttonNextAdmin.classList.remove('hide');
        buttonNextAdmin.classList.add('show');
        for(let i = start; i < end; i++){
          buttonNumberAdmin[i].classList.remove('hide');
          buttonNumberAdmin[i].classList.add('show');
        }
      }else{
        for(let i = start; i < totalPage; i++){
          buttonNumberAdmin[i].classList.remove('hide');
          buttonNumberAdmin[i].classList.add('show');
        }
      }
      buttonNumberAdmin[0].classList.add('active');
      $('.button-number-admin').click((e)=>{
        currPageAdmin = parseInt(e.target.innerHTML);
        if(totalPage > 5 ){
          start = currPageAdmin - 2 > 1 ? (currPageAdmin - 1) - 2 : 0;
          end = currPageAdmin + 2 < totalPage ? currPageAdmin + 2 : totalPage;
          if(start === 0){
            end = 5;
          }
          if(end === totalPage){
            start = (totalPage - 1) - 4;
          }
        }else{
          end = totalPage;
        }
        buttonAdmin(items, totalPage, start, end, buttonNumberAdmin, buttonPrevAdmin, buttonNextAdmin);
        activate(buttonNumberAdmin, totalPage);
      });

      buttonPrevAdmin.addEventListener('click', ()=>{
        currPageAdmin = 1;
        start = 0;
        end = totalPage > 5 ? 5 : totalPage;
        buttonAdmin(items, totalPage, start, end, buttonNumberAdmin, buttonPrevAdmin, buttonNextAdmin);
        activate(buttonNumberAdmin, totalPage);
      });

      buttonNextAdmin.addEventListener('click', ()=>{
        currPageAdmin = totalPage;
        end = totalPage;
        start = end - 5 > 1 ? end - 5 : 1;
        buttonAdmin(items, totalPage, start, end, buttonNumberAdmin, buttonPrevAdmin, buttonNextAdmin);
        activate(buttonNumberAdmin, totalPage);
      });
    }

    function pageButtonGenAdmin(totalPage){
      pageButtonAdmin.innerHTML += `
          <li class="page-item button-prev-admin hide">
            <button class="page-link">First</button>
          </li>
        `
      for(let i = 1; i <= totalPage; i++){
        pageButtonAdmin.innerHTML += `
          <li class="page-item button-number-admin hide">
            <button class="page-link">${i}</button>
          </li>
        `
      }
      pageButtonAdmin.innerHTML += `
          <li class="page-item button-next-admin hide">
            <button class="page-link">Last</button>
          </li>
        `
    }

    function buttonAdmin(items, totalPage, start, end, buttonNumberAdmin, buttonPrevAdmin, buttonNextAdmin){
      for(let i = 0; i < totalPage; i++){
        if(i >= start && i < end){
          buttonNumberAdmin[i].classList.remove('hide');
          buttonNumberAdmin[i].classList.add('show');
        }else{
          buttonNumberAdmin[i].classList.add('hide');
          buttonNumberAdmin[i].classList.remove('show');
        }
      }
      if(end + 1 < totalPage){
          buttonNextAdmin.classList.remove('hide');
          buttonNextAdmin.classList.add('show');
        }else{
          buttonNextAdmin.classList.remove('show');
          buttonNextAdmin.classList.add('hide');
        }
        if(start + 1 > 1){
          buttonPrevAdmin.classList.remove('hide');
          buttonPrevAdmin.classList.add('show');
        }else{
          buttonPrevAdmin.classList.remove('show');
          buttonPrevAdmin.classList.add('hide');
        }
        pageShowAdmin(items, (currPageAdmin * 8) - 7);
    }

    function activate(buttonNumberAdmin, totalPage){
      for(let i = 0; i < totalPage; i++){
        if(buttonNumberAdmin[i].classList.contains('active')){
          buttonNumberAdmin[i].classList.remove('active');
        }
      }
      buttonNumberAdmin[currPageAdmin - 1].classList.add('active');
    }
    pageAdmin();

    // ADMIN

    

  </script>
</body>
</html>
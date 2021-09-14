<header class="shadow-sm p-3 bg-body rounded">
    <div class="d-flex justify-content-between align-items-center px-4 py-2 w-100">
        <a href="index.php">Nime's Store</a>
        <div class="d-flex wrapper-search">
        <input class="form-control form-control-sm" type="search" placeholder="Mau beli apa hari ini ?" aria-label=".form-control-sm example">
        <button class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
        </div>
        <div class="d-flex align-items-center gap-4 w-auto pe-3">
        <a href="">
            <i class="fas fa-shopping-basket fs-4"></i>
        </a>
        <?php if(!$login){ ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">SIGN IN</button>
        <?php }else{
            if($login['role'] == 'admin'){ ?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-plus"></i>
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="manageStaffAdmin.php">Manage Staff/admin</a></li>
                <li><a class="dropdown-item" href="manageSlider.php">Manage Slider</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Manage Couriers</a></li>
                <li><a class="dropdown-item" href="manageKategori.php">Manage Categories</a></li>
                <li><a class="dropdown-item" href="manageProduk.php">Manage Products</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <form method="POST" action="">
                <button type="button" class="d-flex justify-content-around gap-2 btn btn-primary"><img class="icon_user" src="<?php echo $login['image_user'] ?>" alt="icon_user"><?php echo $login['nama_user'] ?></button>
                </form>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="">
                    <button class="dropdown-item" name="quit">Log Out</button>
                    </form>
                </li>
                </ul>
            </div>
            <?php }else if($login['role'] == 'staff'){ ?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-plus"></i>
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Manage Staff/Admin</a></li>
                <li><a class="dropdown-item" href="#">Manage Slider</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Manage Couriers</a></li>
                <li><a class="dropdown-item" href="#">Manage Categories</a></li>
                <li><a class="dropdown-item" href="#">Manage Products</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <form method="POST" action="">
                <button type="button" class="d-flex justify-content-around gap-2 btn btn-primary"><img class="icon_user" src="<?php echo $login['image_user'] ?>" alt="icon_user"><?php echo $login['nama_user'] ?></button>
                </form>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="">
                    <button class="dropdown-item" name="quit">Log Out</button>
                    </form>
                </li>
                </ul>
            </div>
            <?php }else{ ?>
            <div class="btn-group">
                <form method="POST" action="">
                <button type="button" class="d-flex justify-content-around gap-2 btn btn-primary"><img class="icon_user" src="<?php echo $login['image_user'] ?>" alt="icon_user"><?php echo $login['nama_user'] ?></button>
                </form>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="">
                    <button class="dropdown-item" name="quit">Log Out</button>
                    </form>
                </li>
                </ul>
            </div>
            <?php } ?>
        <?php }?>
        <!-- MODAL -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialogs modal-dialog-centered">
            <div class="modal-content py-3 px-4">
                <div class="modal-header modal-headers position-relative">
                <h5 class="modal-title title">Masuk</h5>
                <button class="modal-title back position-absolute "><h5 class="modal-title"><i class="fas fa-arrow-left"></i> Kembali</h5></button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                    <div class="wrapper-signin d-flex justify-content-center align-items-center">
                        <div class="d-flex flex-column justify-content-center uName now">
                        <div class="d-flex justify-content-between">
                            <label for="emailUname">Email/Username</label>
                            <a class="text-primary" href="register.php">Daftar</a>
                        </div>
                        <input id="emailUname" class="form-control" type="text" name="email" placeholder="Email/username kamu">
                        </div>
                        <div class="d-flex flex-column justify-content-center pass left">
                        <label for="pass">Password</label>
                        <div class="d-flex align-items-center position-relative">
                            <input id="pass"  class="form-control" type="password" name="pass" placeholder="Password kamu">
                            <i id="eyes" class="fas fa-eye position-absolute"></i>
                        </div>
                        </div>
                    </div>
                    <div class="wrapper-button-signin d-flex flex-column">
                    <span class="btn btn-primary next show">Selanjutnya</span>
                    <button class="btn btn-primary submit hide" name="submit">Submit</button>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</header>
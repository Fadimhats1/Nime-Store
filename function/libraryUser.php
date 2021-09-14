<?php 
    class Library{
        public function __construct(){
            $host = "localhost";
            $dbname = "nime_store";
            $uName = "root";
            $pass = "";
            try{
                $this->db = new PDO("mysql:host={$host};dbname={$dbname}", $uName, $pass);
            }catch(PDOException $e){
                echo "connection failed. ".$e->getMessage();
            }
        }

        public function add_user($nama, $email, $pass, $gender){
            $data = $this->db->prepare('INSERT INTO user (nama_user, email_user, pass_user, gender_user) VALUES(?, ?, ?, ?)');
            $pass_hash = password_hash($pass, PASSWORD_BCRYPT);
            $data->bindParam(1, $nama);
            $data->bindParam(2, $email);
            $data->bindParam(3, $pass_hash);
            $data->bindParam(4, $gender);

            $data->execute();
            return $data->rowCount();
        }

        private function users(){
            $var = 'user';
            $query = $this->db->prepare('SELECT * FROM user WHERE role=?');
            $query->bindParam(1, $var);
            $query->execute();
            return $query;
        }

        private function staffAdmin(){
            $var1 = 'staff';
            $var2 = 'admin';
            $query = $this->db->prepare('SELECT * FROM user WHERE role=? OR role=?');
            $query->bindParam(1, $var1);
            $query->bindParam(2, $var2);
            $query->execute();
            return $query;
        }

        public function show($cons){
            if($cons == 'admin'){
                $data = $this->staffAdmin();
                return $data->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $data = $this->users();
                return $data->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        
        public function delete($idHapus, $table){
            $str = "DELETE FROM $table WHERE id = ?";
            $query = $this->db->prepare($str);
            $query->bindParam(1, $idHapus);
            $query->execute();
        }

        public function count($cons){
            if($cons == 'admin'){
                $data = $this->staffAdmin();
                return $data->rowCount();
            }else{
                $data = $this->users();
                return $data->rowCount();
            }
        }

        public function login($email_user, $pass_user){
            $query = $this->db->prepare('SELECT * FROM user WHERE(email_user=? OR nama_user=?)');
            $query->bindParam(1, $email_user);
            $query->bindParam(2, $email_user);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if($data){
                if(!password_verify($pass_user, $data['pass_user'])){
                    return 0;
                }
                return $data;
            }
            return -1;
        }

        public function get_id($id_user){
            $query = $this->db->prepare('SELECT * FROM user WHERE id=?');
            $query->bindParam(1, $id_user);
            $query->execute();
            return $query->fetch();
        }

        public function update($nama, $email, $pass, $alamat, $image, $id_user, $role){
            $query = $this->db->prepare('UPDATE user SET nama_user=?, email_user=?, pass_user=?, alamat_user=?, image_user=?, role=? WHERE id=?');

            $query->bindParam(1, $nama);
            $query->bindParam(2, $email);
            $query->bindParam(3, $pass);
            $query->bindParam(4, $alamat);
            $query->bindParam(5, $image);
            $query->bindParam(6, $id_user);
            $query->bindParam(7, $role);

            $query->execute();
            return $query->rowCount();
        }

        private function selectAll($table){
            $str = "SELECT * FROM $table";
            $query = $this->db->prepare($str);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function selectSpecific($id, $table){
            $str = "SELECT * FROM $table WHERE id=?";
            $query = $this->db->prepare($str);
            $query->bindParam(1, $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        private function selectReallySpecific($table, $type, $data, $len){
            if(is_numeric($data)){
                $query = $this->db->prepare("SELECT * FROM $table WHERE $type=?");
                $query->bindParam(1, $data, PDO::PARAM_INT);
            }else{
                $query = $this->db->prepare("SELECT * FROM $table WHERE $type LIKE ? AND CHAR_LENGTH($type)=?");
                $query->bindValue(1, '%'.$data.'%', PDO::PARAM_STR);
                $query->bindParam(2, $len, PDO::PARAM_INT);
            }
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function totalPage($perPage, $table){
            $totalPage = ceil(count($this->selectAll($table))/$perPage);
            return $totalPage;
        }

        // SLIDER

        public function addSlider($nama, $gambar, $tGambar, $sizeGambar, $hyperlink, $mulai, $akhir, $sequences){
            $query = $this->db->prepare('INSERT INTO sliders(nama_slides, gambar_slides, hyperlink, mulai, akhir, sequences) VALUES(?, ?, ?, ?, ?, ?)');
            $query->bindParam(1, $nama);
            $query->bindParam(3, $hyperlink);
            $query->bindParam(4, $mulai);
            $query->bindParam(5, $akhir);
            $query->bindParam(6, $sequences);
            $upload = "image/upload/".$gambar;
            $fileExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
            $validExtension = array("png","jpeg","jpg");
            if(in_array($fileExtension, $validExtension)){
                if($sizeGambar > 2000000){
                    return 'manageSlider.php?size=false';
                }
                move_uploaded_file($tGambar, $upload);
                $query->bindParam(2, $upload);
                $query->execute();
                return 'manageSlider.php';
            }else{
                return 'manageSlider.php?extension=false';
            }
            
        }

        public function showSlider($firstData, $perPage){
            $str = "SELECT * FROM sliders LIMIT ?,?";
            $query = $this->db->prepare($str);
            $query->bindParam(1, $firstData, PDO::PARAM_INT);
            $query->bindParam(2, $perPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function editSlider($page, $id, $nama, $gambar, $tGambar, $sizeGambar, $hyperlink, $mulai, $akhir, $sequencesNew, $sequenceOld){
            $data = $this->selectAll('sliders');
            $flag = true;
            if($sequencesNew != $sequenceOld){
                foreach($data as $check){
                    if($check['sequences'] == $sequencesNew){
                        $flag = false;
                    }
                }
            }
            if(!$flag){
                return "manageSlider.php?page=$page&id=$id&sequences=false";
            }
            if($gambar){
                $str = 'UPDATE sliders SET nama_slides=?, gambar_slides=?, hyperlink=?, mulai=?, akhir=?, sequences=? WHERE id=?';
            }else{
                $str = 'UPDATE sliders SET nama_slides=?, hyperlink=?, mulai=?, akhir=?, sequences=? WHERE id=?';
            }
            $query = $this->db->prepare($str);
            $query->bindParam(1, $nama);
            $bind = array($hyperlink, $mulai, $akhir, $sequencesNew, $id);
            $i = 2;
            if($gambar){
                $i = 3;
                $upload = 'image/upload'.$gambar;
                $fileExtension = strtolower(pathinfo($gambar,PATHINFO_EXTENSION));
                $validExtension = array("png","jpeg","jpg");
                if(in_array($fileExtension, $validExtension)){
                    if($sizeGambar > 2000000){
                        return "manageSlider.php?page=$page&id=$id&size=false";
                    }
                        move_uploaded_file($tGambar, $upload);
                        $query->bindParam(2, $upload);
                }else{
                    return "manageSlider.php?page=$page&id=$id&extension=false";
                }
            }
            for($j = $i, $k = 0; $j < $i + 5; $j++, $k++){
                $query->bindParam($j, $bind[$k]);
            }

            $query->execute();
            if(isset($_SESSION['dataEdit'])){
                unset($_SESSION['dataEdit']);
            }
            return "manageSlider.php?page=$page";
        }

        // KATEGORI

        public function selectKategori(){
            $data = $this->selectAll('kategori');
            return $data;
        }

        public function addKategori($nama, $subKategori, $gambar, $tGambar, $sizeGambar){
            $query = $this->db->prepare('INSERT INTO kategori(nama_kategori, icon_kategori) VALUES(?, ?)');
            $query->bindParam(1, $nama);
            $upload = "image/kategori/".$gambar;
            $fileExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
            $validExtension = array("png","jpeg","jpg");
            if(in_array($fileExtension, $validExtension)){
                if($sizeGambar > 2000000){
                    return 'manageKategori.php?size=false';
                }
                move_uploaded_file($tGambar, $upload);
                $query->bindParam(2, $upload);
                $query->execute();
                $lastId = $this->db->lastInsertId();
                $this->addSubKategori($lastId, $subKategori);
                return 'manageKategori.php';
            }else{
                return 'manageKategori.php?extension=false';
            }
        }

        public function addSubKategori($id, $subKategori){
            if($subKategori){
                $dataParent = $this->selectSpecific($id, 'kategori');
                $query = $this->db->prepare('INSERT INTO sub_kategori(nama_subkategori, kategori_id) VALUES(?, (SELECT id FROM kategori WHERE id=?))');

                $count = count($subKategori);

                for($i = 0; $i < $count; $i++){
                    $exists = $this->selectReallySpecific('sub_kategori', 'nama_subkategori', $subKategori[$i], strlen($subKategori[$i]));
                    if($exists){
                        $dataParentExists = $this->selectSpecific($exists['kategori_id'], 'kategori');
                        $temp1 = $dataParentExists['nama_kategori'];
                        $temp2 = $exists['nama_subkategori']." ($temp1)";
                        $this->updateSubKategori($temp2, $exists['id']);
                        $subKategori[$i] .= ' '.'('.$dataParent['nama_kategori'].')';
                    }
                    $query->bindValue(1, $subKategori[$i], PDO::PARAM_STR);
                    $query->bindParam(2, $id, PDO::PARAM_INT);
                    $query->execute();
                }
            }
        }

        private function selectSubKategori($judul, $id){
            $query = $this->db->prepare('SELECT * FROM sub_kategori WHERE nama_subkategori LIKE ? AND kategori_id=? AND CHAR_LENGTH(nama_subkategori)=?');
            $query->bindValue(1, '%'.$judul.'%', PDO::PARAM_STR);
            $query->bindParam(2, $id, PDO::PARAM_INT);
            $query->bindValue(3, strlen($judul), PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function showSubKategori($id, $searchData, $firstData, $perPage){
            $str = "SELECT * FROM sub_kategori WHERE nama_subkategori LIKE ? AND kategori_id=? LIMIT ?,?";
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', PDO::PARAM_STR);
            $query->bindParam(2, $id, PDO::PARAM_INT);
            $query->bindParam(3, $firstData, PDO::PARAM_INT);
            $query->bindParam(4, $perPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        private function dupeSubKategori($name, $len, $idParent){
            $query = $this->db->prepare('SELECT * FROM sub_kategori WHERE nama_subkategori LIKE ? AND CHAR_LENGTH(nama_subkategori)=? AND kategori_id=?');
            $query->bindValue(1, '%'.$name.'%', PDO::PARAM_STR);
            $query->bindParam(2, $len, PDO::PARAM_INT);
            $query->bindParam(3, $idParent, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        private function updateSubKategori($name, $id){
            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d H:i:s');
            $query = $this->db->prepare('UPDATE sub_kategori SET nama_subkategori=?, last_updated=? WHERE id=?');
            $query->bindParam(1, $name);
            $query->bindParam(2, $date);
            $query->bindParam(3, $id);
            $query->execute();
        }

        public function editSubKategori($name, $id, $idParent){
            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d H:i:s');
            $dataParent = $this->selectSpecific($idParent, 'kategori');
            $nameCheck = $this->selectSubKategori($name, $id);
            if(strcmp($name, $nameCheck['nama_subkategori'])){
                $temp = $this->selectReallySpecific('sub_kategori', 'nama_subkategori', $name, strlen($name)); // NYARI DATA YANG SAMA DI DATABASE
                if($temp){
                    $temp1 = $this->selectSpecific($temp['kategori_id'], 'kategori'); // BUAT DATA PARENT (NAMA) YANG DARI DATABASE
                    $temp2 = $temp['nama_subkategori']." (".$temp1['nama_kategori'].')'; // NAMA YANG DI DATABASE DIGABUNG SAMA NAMA PARENT NYA
                    $name .= " (".$dataParent['nama_kategori'].')'; // NAMA DATA INPUT
                    $flag1 = $this->dupeSubKategori($temp2, strlen($temp2), $temp['kategori_id']);
                    if($flag1 || (!strcmp(strtolower($name),strtolower($temp2)) && $temp['kategori_id'] == $idParent)){
                        return;
                    }
                    $this->updateSubKategori($temp2, $temp['id']);
                    $flag2 = $this->dupeSubKategori($name, strlen($name), $idParent);
                    if($flag2){
                        return;
                    }
                }
            }
            $query = $this->db->prepare('UPDATE sub_kategori SET nama_subkategori=?, last_updated=? WHERE id=?');
            $query->bindParam(1, $name);
            $query->bindParam(2, $date);
            $query->bindParam(3, $id);
            $query->execute();
        }

        public function SubKategoriPage($searchData, $id, $perPage){
            $str = "SELECT * FROM sub_kategori WHERE nama_subkategori LIKE ? AND kategori_id=?";
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', PDO::PARAM_STR);
            $query->bindParam(2, $id, PDO::PARAM_INT);
            $query->execute();
            return ceil($query->rowCount()/$perPage);
        }

        public function showKategori($firstData, $perPage){
            $str = "SELECT * FROM kategori LIMIT ?,?";
            $query = $this->db->prepare($str);
            $query->bindParam(1, $firstData, PDO::PARAM_INT);
            $query->bindParam(2, $perPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function showSubKateSelect($idParent){
            $query = $this->db->prepare('SELECT * FROM sub_kategori WHERE kategori_id=?');
            $query->bindParam(1, $idParent, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function editKategori($page, $id, $nama, $gambar, $tGambar, $sizeGambar, $search){
            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d H:i:s');
            if($gambar){
                $str = 'UPDATE kategori SET nama_kategori=?, icon_kategori=?, last_updated=? WHERE id=?';
            }else{
                $str = 'UPDATE kategori SET nama_kategori=?, last_updated=? WHERE id=?';
            }
            $query = $this->db->prepare($str);
            $query->bindParam(1, $nama);
            $bind = array($date, $id);
            $i = 1;
            if($gambar){
                $i = 2;
                $upload = 'image/kategori/'.$gambar;
                $fileExtension = strtolower(pathinfo($gambar,PATHINFO_EXTENSION));
                $validExtension = array("png","jpeg","jpg");
                if(in_array($fileExtension, $validExtension)){
                    if($sizeGambar > 2000000){
                        return "editKategori.php?size=false";
                    }
                        move_uploaded_file($tGambar, $upload);
                        $query->bindParam(2, $upload);
                }else{
                    return "editKategori.php?extension=false";
                }
            }
            $i++;
            for($j = $i, $k = 0; $j < $i + 2; $j++, $k++){
                $query->bindParam($j, $bind[$k]);
            }
            $query->execute();
            if(isset($_SESSION['dataEdit'])){
                unset($_SESSION['dataEdit']);
            }
            if(!$search){
                return "manageKategori.php?page=$page";
            }
            return "manageKategori.php?search=$search&page=$page";
        }

        public function search($table, $search, $searchData){
            $str = "SELECT * FROM $table WHERE $search LIKE ?";
            if(!$searchData){
                return "manageKategori.php?page=1";
            }
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', PDO::PARAM_STR);
            $query->execute();
            return "manageKategori.php?search=$searchData&page=1";
        }

        public function searchKategori($searchData, $firstData, $perPage){
            $str = "SELECT * FROM kategori WHERE nama_kategori LIKE ? LIMIT ?,?";
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', 
            PDO::PARAM_STR);
            $query->bindParam(2, $firstData, PDO::PARAM_INT);
            $query->bindParam(3, $perPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function searchTotalPage($table, $search, $searchData, $perPage){// BUAT NYARI BANYAK PAGE YANG DISEARCH
            $str = "SELECT * FROM $table WHERE $search LIKE ?";
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', PDO::PARAM_STR);
            $query->execute();
            return ceil($query->rowCount()/$perPage);
        }

        // PRODUK

        public function showProduk($searchData, $firstData, $perPage){
            $str = "SELECT p.*, i.nama_image FROM produk AS p JOIN image_produk AS i ON p.image_produk_id = i.id WHERE nama_produk LIKE ? LIMIT ?,?";
            $query = $this->db->prepare($str);
            $query->bindValue(1, '%'.$searchData.'%', PDO::PARAM_STR);
            $query->bindParam(2, $firstData, PDO::PARAM_INT);
            $query->bindParam(3, $perPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function selectedProdukKate($idProduk){
            $query = $this->db->prepare('SELECT subkategori_id FROM produk_kategori WHERE produk_id=?');
            $query->bindParam(1, $idProduk, PDO::PARAM_INT);
            $query->execute();
            $select = $query->fetchAll(PDO::FETCH_ASSOC);
            $check = array();
            foreach($select as $key){
                $temp = array_filter($key, function($k){
                    return $k == 'subkategori_id';
                }, ARRAY_FILTER_USE_KEY);
                array_push($check, reset($temp));
            }
            return $check;
        }
        
        private function addProdukKate($subKateId, $produk_id){
            $query = $this->db->prepare('INSERT INTO produk_kategori(subkategori_id, produk_id) VALUES((SELECT id FROM sub_kategori WHERE id=?), (SELECT id FROM produk WHERE id=?))');
            $query->bindParam(1, $subKateId, PDO::PARAM_INT);
            $query->bindParam(2, $produk_id, PDO::PARAM_INT);
            $query->execute();
        }

        public function addImage($idProduk, $gambar, $tGambar, $sizeGambar){
            $query = $this->db->prepare('INSERT INTO image_produk(nama_image, produk_id) VALUES(?,(SELECT id FROM produk WHERE id=?))');
            $query->bindParam(2, $idProduk);
            $upload = 'image/upload/'.$gambar;
            $fileExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
            $validExtension = array("png","jpeg","jpg");
            if(in_array($fileExtension, $validExtension)){
                if($sizeGambar > 2000000){
                    return "addProduk.php?size=false";
                }
                $temp = move_uploaded_file($tGambar, $upload);
                $query->bindParam(1, $upload);
                if(!$temp){
                    return;
                }
            }else{
                return "addProduk.php?extension=false";
            }
            $query->execute();
            return 'success';
        }

        public function checkImage($imageProdId){
            $query = $this->db->prepare('SELECT * FROM image_produk WHERE id=?');
            $query->bindParam(1, $imageProdId);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function deleteProdukKate($idSubKate, $idProduk){
            $query = $this->db->prepare('DELETE FROM produk_kategori WHERE subkategori_id=? AND produk_id=?');
            $query->bindParam(1, $idSubKate, PDO::PARAM_INT);
            $query->bindParam(2, $idProduk, PDO::PARAM_INT);
            $query->execute();
        }

        public function gambarSampul($idImage, $idProduk){
            $query = $this->db->prepare('UPDATE produk SET image_produk_id=? WHERE id=?');
            $query->bindParam(1, $idImage);
            $query->bindParam(2, $idProduk);
            $query->execute();
        }

        public function addProduk($namaProduk, $desc, $harga, $stok, $kategori, $gambar, $tGambar, $sizeGambar){
            if(count($gambar) > 5 || !count($gambar)){
                return 'addProduk.php&total_image=false';
            }
            $query = $this->db->prepare('INSERT INTO produk(nama_produk, harga_produk, stok_produk, deskripsi_produk) VALUES(?,?,?,?)');
            $query->bindParam(1, $namaProduk);
            $query->bindParam(2, $harga);
            $query->bindParam(3, $stok);
            $query->bindParam(4, $desc);
            $query->execute();
            $idProd = $this->db->lastInsertId();
            $count = count($kategori);
            for($i = 0; $i < $count; $i++){
                $this->addProdukKate($kategori[$i], $idProd);
            }
            $idGambar = 0;
            $count = count($gambar);
            for($i = 0; $i < $count; $i++){
                $check = $this->addImage($idProd, $gambar[$i],$tGambar[$i], $sizeGambar[$i]);
                if(!$i){
                    $idGambar = $this->db->lastInsertId();
                }
                if(str_contains($check, 'size') || str_contains($check, 'extension')){
                    return $check.'&image='.$i+1;
                }
            }
            $this->gambarSampul($idGambar, $idProd);
            return 'manageProduk.php';
        }

        public function showImageProduk($idProduk){
            $query = $this->db->prepare('SELECT * FROM image_produk WHERE produk_id=?');
            $query->bindParam(1, $idProduk, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function editProduk($id, $name, $desc, $price, $stock, $category){
            $query = $this->db->prepare('UPDATE produk SET nama_produk=?, harga_produk=?, stok_produk=?, deskripsi_produk=? WHERE id=?');
            $query->bindParam(1, $name);
            $query->bindParam(2, $price);
            $query->bindParam(3, $stock);
            $query->bindParam(4, $desc);
            $query->bindParam(5, $id);
            $query->execute();
            $count = count($category);
            for($i = 0; $i < $count; $i++){
                $query = $this->db->prepare('SELECT * FROM produk_kategori WHERE subkategori_id=? AND produk_id=?');
                $query->bindParam(1, $category[$i], PDO::PARAM_INT);
                $query->bindParam(2, $id, PDO::PARAM_INT);
                $query->execute();
                $check = $query->fetch(PDO::FETCH_ASSOC);
                if(!$check){
                    $this->addProdukKate($category[$i], $id);
                }
            }
        }
        public function showProdukSub($idProd){
            $query = $this->db->prepare('SELECT p.*, s.nama_subkategori FROM produk_kategori AS p JOIN sub_kategori AS s ON p.subkategori_id = s.id WHERE produk_id = ?');
            $query->bindParam(1, $idProd, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
<?php 
    include('libraryUser.php');
    require_once '../faker/vendor/autoload.php';

    $lib_user = new Library();
    $faker = Faker\Factory::create();

    foreach(range(1, 400) AS $key){
        $tempSubKate = array();
        $totalKategori = $faker->numberBetween(1, 4);
        $totalImage = $faker->numberBetween(1, 5);
        $lenSentence = $faker->numberBetween(8, 24);
        $query = $lib_user->db->prepare("INSERT INTO produk(nama_produk, harga_produk, stok_produk, deskripsi_produk) VALUES('{$faker->sentence($faker->numberBetween(1, 3), true)}', '{$faker->numberBetween(16000, 10000000)}', '{$faker->numberBetween(1, 999)}', '{$faker->sentence($lenSentence, true)}')");
        $query->execute();
        $id = $lib_user->db->lastInsertId();
        for($i = 0; $i < $totalKategori; $i++){
            $tempSubKateVal;
            while(true){
                $tempSubKateVal = $faker->numberBetween(1, 48);
                if(!in_array($tempSubKateVal, $tempSubKate)){
                    array_push($tempSubKate, $tempSubKateVal);
                    break;
                }
            }
            $query = $lib_user->db->prepare("INSERT INTO produk_kategori(subkategori_id, produk_id) VALUES((SELECT id FROM sub_kategori WHERE id='{$tempSubKateVal}'), (SELECT id FROM produk WHERE id='{$id}'))");
            $query->execute();
        }
        for($i = 0; $i < $totalImage; $i++){
            $query = $lib_user->db->prepare("INSERT INTO image_produk(nama_image, produk_id) VALUES('{$faker->imageUrl(100, 100, 'cats')}',(SELECT id FROM produk WHERE id='{$id}'))");
            $query->execute();
            if(!$i){
                $imageId = $lib_user->db->lastInsertId();
            }
        }
        $query = $lib_user->db->prepare("UPDATE produk SET image_produk_id='{$imageId}' WHERE id='{$id}'");
        $query->execute();
    }

?>
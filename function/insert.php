<?php 
    include('libraryUser.php');
    require_once '../faker/vendor/autoload.php';

    $lib_user = new Library();
    $faker = Faker\Factory::create();

    foreach(range(1, 20) AS $key){
        $totalKategori = $faker->numberBetween(1, 4);
        $totalImage = $faker->numberBetween(1, 5);
        $query = $lib_user->db->prepare("INSERT INTO produk(nama_produk, harga_produk, stok_produk, deskripsi_produk) VALUES('{$faker->word}', '{$faker->randomNumber()}', '{$faker->numberBetween(1, 999)}', '{$faker->word}')");
        $query->execute();
        $id = $lib_user->db->lastInsertId();
        for($i = 0; $i < $totalKategori; $i++){
            $query = $lib_user->db->prepare("INSERT INTO produk_kategori(subkategori_id, produk_id) VALUES((SELECT id FROM sub_kategori WHERE id='{$faker->numberBetween(1, 42)}'), (SELECT id FROM produk WHERE id='{$id}'))");
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
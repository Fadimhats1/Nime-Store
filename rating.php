<div>
    <?php 
        $data = 1/6;
        $max = 5 - floor($data);
        $flagF = round($data) > $data ? true : false;
        for($i = 1; $i <= $data; $i++){ ?>
            <i class="fas fa-star"></i>
        <?php } 
        if($flagF){ ?>
            <i class="fas fa-star-half-alt"></i>
        <?php --$max;}
        if($max){
            for($i = 1; $i <= $max; $i++){ ?>
                <i class="far fa-star"></i>
            <?php };
        }
    ?>
</div>
<p><?= number_format($data, 1) ?></p>
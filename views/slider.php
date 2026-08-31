<?php
require_once "blogadmin/dblayer/sliderImageOps.php";
require_once "blogadmin/model/sliderImageModel.php";
?>
<div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php
        $count = 0;
        $isActive = "active";
        $initialValue = 'aria-current="true"';
        $designList = DBsliderImageFile::readAll();
        foreach ($designList as $design) {
             echo '<button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="' . $count . '" class="' . $isActive . '" ' . $initialValue . ' aria-label="Slide ' . $count . '"></button>';
            $isActive = "";
            $count++;
            $initialValue = "";
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php
        $isActive = "active";
        $designList = DBsliderImageFile::readAll();
        if (count($designList) > 0) {
            foreach ($designList as $design) {
                echo '<div class="carousel-item ' . $isActive . ' " data-bs-interval="">
        <img src="blogadmin/img/Slider/' . $design->getImage() . '" class="d-block w-100 img-fluid" alt="..." style="height:600px">
        <div class="carousel-caption d-none d-md-block">
            <p>' . $design->getImageFileCaption() . '</p>
        </div>
        </div>';
                $isActive = "";
            }

            echo '</div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>';
        } else {
            echo "No images to display";
        }
        ?>
    </div>
</div>
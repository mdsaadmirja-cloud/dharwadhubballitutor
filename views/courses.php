<?php
require_once("navigation.php");
?>

<style>
body{
    background:#f7f8fa;
}

.page-header{
    background:linear-gradient(135deg,#14163a,#24126A);
    color:#fff;
    padding:70px 20px;
    text-align:center;
    border-bottom:5px solid #F6BE01;
}

.page-header h1{
    font-weight:700;
}

.course-card{
    background:#fff;
    border-radius:18px;
    border:none;
    overflow:hidden;
    transition:.3s;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    height:100%;
}

.course-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 45px rgba(0,0,0,.15);
}

.course-icon{
    width:80px;
    height:80px;
    background:#F6BE01;
    color:#14163a;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:34px;
    margin:30px auto 20px;
}

.course-title{
    font-size:22px;
    font-weight:700;
    color:#14163a;
}

.course-info{
    font-size:15px;
    color:#666;
}

.course-info i{
    color:#4042e2;
    width:20px;
}

.btn-course{
    background:#14163a;
    color:#fff;
    border-radius:30px;
    padding:10px 25px;
    text-decoration:none;
    display:inline-block;
    transition:.3s;
}

.btn-course:hover{
    background:#F6BE01;
    color:#14163a;
}

.empty-box{
    background:#fff;
    border-radius:15px;
    padding:50px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
</style>

<section class="page-header">
    <div class="container">
        <h1>Our Courses</h1>
        <p>Choose the right course to build your career.</p>
    </div>
</section>

<div class="container py-5">

    <div class="row">

        <?php if(!empty($courselist)){ ?>

            <?php foreach($courselist as $course){ ?>

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="course-card">

                    <div class="course-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>

                    <div class="card-body text-center">

                        <h4 class="course-title">
                            <?php echo htmlspecialchars($course->get_cname()); ?>
                        </h4>

                        <hr>

                        <p class="course-info">
                            <i class="fa fa-book"></i>
                            <strong>Type :</strong>
                            <?php echo htmlspecialchars($course->get_ctype()); ?>
                        </p>

                        <p class="course-info">
                            <i class="fa fa-clock-o"></i>
                            <strong>Duration :</strong>
                            <?php echo htmlspecialchars($course->get_cduration()); ?>
                        </p>

                        <br>

                        <button class="btn-course"
                                data-bs-toggle="modal"
                                data-bs-target="#demomodal">
                            Enquire Now
                        </button>

                    </div>

                </div>

            </div>

            <?php } ?>

        <?php } else { ?>

            <div class="col-12">
                <div class="empty-box">
                    <h3>No Courses Available</h3>
                    <p>Please check back later.</p>
                </div>
            </div>

        <?php } ?>

    </div>

</div>

<?php require_once("footer.php"); ?>
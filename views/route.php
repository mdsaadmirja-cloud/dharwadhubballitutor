<?php

class route
{
    public static function get($routePath)
    {
        /*
        |--------------------------------------------------------------------------
        | BELAGAVI BRANCH PAGE
        |--------------------------------------------------------------------------
        */
        if ($routePath === '/belgavi/') {
            require_once("views/belgavibranch.php");
            return;
        }
        /*
|--------------------------------------------------------------------------
| ALL COURSES PAGE
|--------------------------------------------------------------------------
*/
        if ($routePath === '/courses/') {
            require_once("views/courses.php");
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | EXISTING BLOG / POST ROUTING
        |--------------------------------------------------------------------------
        */
        require_once("blogadmin/dblayer/postOps.php");

        $post = DBpost::getPostByUrl($routePath);

        require("views/navigation.php");




        $outputString = '<div class="routehero-wrap " style=" background:#ffd60a;background-size:cover;width:100%;">

 <div class="title-route text-center">
    <h1 class="fw-bold text-dark" style="text-align:center;">' . $post->getPostTitle() . '</h1>'
            .

            (
                str_contains($post->getPostTitle(), "Data")
                ? '<h2>Learn Excel, SQL, Power BI & AI tools and become job-ready in 45 days</h2>'
                : '<h2>Advanced Executive Course</h2>'
            ) .


            '<button type=button data-bs-toggle="modal" data-bs-target="#demomodal" class="btn btn-primary">Book Free Demo Now</button>
</div>
</div>

<div id="main-content" class="routeblog-page" >
<section class="bg-primary container-fluid p-5">
    <div class="row  d-flex justify-content-evenly">
        <div class="col-md-3 text-center">
            <i class="fa fa-star pe-1 " style="color:#f8c000"></i>
            <i class="fa fa-star pe-1 " style="color:#f8c000"></i>
            <i class="fa fa-star pe-1 " style="color:#f8c000"></i>
            <i class="fa fa-star pe-1 " style="color:#f8c000"></i>
            <i class="fa fa-star-half p-1 " style="color:#f8c000"></i>
            
            <p class="text-white">4.9 Google Ratings</p>
        </div>
        <div class="col-md-3">
            <h3 class="text-center fw-bold" style="color:#ffd60a">5000+</h3>
            <p class="text-center text-white">Trained Students</p>
        </div>
        <div class="col-md-3 text-center">
            <h3 class="fw-bold" style="color:#ffd60a" >Experienced Trainers</h3>
            <p class="text-white">Minimum 3+ Years of Working Experience </p>
        </div>
        <div class="col-md-3 text-center">
            <h3 class="fw-bold" style="color:#ffd60a">Guaranteed Placement</h3>
            <p class="text-white">Unlimited interview opportunities</p>
        </div>
    </div>
</section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-lg-10 col-md-12 left-box">
                    <div class="card single_post">
                        <div class="body" >
                            <div style="font-size:18px;">' . $post->getPostDescription() . '</div>
                           
                        </div>
                    </div>
                </div>
            <div class="col-lg-2 col-md-12 left-box">
                    <div class="card">
                    <div class="header">
                    <h2>Popular Posts</h2>                        
                    </div>
                <div class="body widget popular-post">
                <div class="row">';

        $postOnHomeList = DBpost::getpopularPost();

        foreach ($postOnHomeList as $post) {
            $outputString .=  '<div class="single_post" style="width:250px;margin-top: auto;">
                                <p class="m-b-0"><a href="' . $post->getPostUrl() . '">' . ucfirst(strtolower($post->getPostTitle())) . '</a></p>
                                <span>' . $post->getPostCreatedBy() . '</span>
                                <div class="img-post">
                                    <img src="/blogadmin/img/Post/' . $post->getImage() . '" alt="' . $post->getAltTextImage() . '" >                                        
                                </div>                                            
                            </div>';
        }

        $outputString .= '<button type=button data-bs-toggle="modal" data-bs-target="#demomodal" class="btn btn-primary">Book Demo</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
           

        <div class="col-lg-12 col-md-12 right-box">
        <div class="card">
            <div class="header">
                <h6 class="display-6">Categories</h6>
            </div>
            <div class="body widget">
                    <ul class="list-unstyled categories-clouds m-b-0">';

        if (!empty($post->getMappedSubCategory())) {
            foreach ($post->getMappedSubCategory() as $subcategory) {
                $outputString .= '<li><p style="color:blue;">' . $subcategory->getSubCategoryName() . '</p></li>';
            }
        }

        $outputString .=  '</ul>
            </div>
        </div>
        </div>
    </div>
</div>';

        echo $outputString;
?>

        <div class="modal fade" id="demomodal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="color:#2a0a5e">Register for Demo Class</h3>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="text-align: right;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <?php session_start(); ?>

                        <form class="modal-content" action="../Admin/Controller/newenquiry.php" method="POST" autocomplete="off">

                            <div class="container">

                                <input type="hidden"
                                    name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                                <label class="label" for="name2"><b>Name</b></label>
                                <input type="text"
                                    name="name2"
                                    class="form-control"
                                    id="name2"
                                    placeholder="Name"
                                    required />

                                <input type="hidden"
                                    name="front"
                                    class="form-control"
                                    id="demofront"
                                    value="front" />

                                <label class="label" for="email2"><b>Email</b></label>
                                <input type="email"
                                    name="email2"
                                    class="form-control"
                                    id="email2"
                                    placeholder="name@example.com" />

                                <label class="label" for="phone2"><b>Enter your number:</b></label>
                                <input type="tel"
                                    name="phone2"
                                    class="form-control"
                                    id="phone2"
                                    placeholder="Number"
                                    required
                                    pattern="^[6-9]\d{9}$" />

                                <label class="label" for="demo2"><b>Demo Class For </b></label>

                                <select class="form-select"
                                    id="demo2"
                                    name="trainings2"
                                    style="background-color:#f1f1f1">

                                    <option value="">SELECT YOUR INTEREST</option>

                                    <?php
                                    $courselist = DBcourse::selectall();

                                    foreach ($courselist as $course) {
                                        echo "<option value='" .
                                            $course->get_cname() .
                                            "'>" .
                                            $course->get_cname() .
                                            "</option>";
                                    }
                                    ?>

                                </select>

                                <br />

                                <input type="hidden"
                                    id="recaptcha-token"
                                    name="recaptcha-token">

                            </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-warning"
                            data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="submit"
                            class="btn btn-warning"
                            name="demosubmit">
                            Submit
                        </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <script src="https://www.google.com/recaptcha/api.js?render=6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg"></script>

        <script>
            function onSubmit(token) {
                document.getElementById("contactForm").submit();
            }

            function prepareRecaptcha() {
                grecaptcha.ready(function() {
                    grecaptcha.execute(
                        '6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                            action: 'submit'
                        }
                    ).then(function(token) {
                        document.getElementById('recaptcha-token').value = token;
                    });
                });
            }

            window.onload = prepareRecaptcha;
        </script>

<?php require_once("views/footer.php");
    }
}

<?php
$configs = require_once("config.php");
require_once("blogadmin/dblayer/businessOps.php");
require_once("blogadmin/dblayer/categoryOps.php");
require_once("blogadmin/dblayer/postOps.php");
require_once "blogadmin/dblayer/socialMediaHandleOps.php";
require_once "Admin/DB Operations/CoursesOps.php";
require_once "blogadmin/dblayer/termsandconditionsOps.php";
require_once ("blogadmin/dblayer/PrivacyPolicyOps.php");
require_once ("blogadmin/dblayer/testimonialsOps.php");
require_once ("blogadmin/dblayer/facebookchatOps.php");
 $request= $_SERVER['REQUEST_URI'];
 $path=substr($request,1,-1);
 $Home = preg_replace('|/|', 'DharwadHubballiTutor', $request);
 
?>
<!doctype html>
<html lang="en">
<head>
  

   <title><?php echo isset($post) ? $post->getPostTitle() : ($request=='/' || $request=='') ? $Home : ucfirst($path) ;?></title>
<?php
  $courselist = DBcourse::selectall();
  $business = DBbusiness::getBusinessDetails();
  $socialMediaHandles = DBsocialMediaHandle::read(); ?>
  <meta charset="utf-8">
  <meta name="author" content="DharwadHubballiTutor" />
  <meta name="description" content="DharwadHubballiTutor is one of the 
  best computer training institute, We have our expertise's on 
  Full stack web design and development training, Digital Media Marketing training, C Programming 
  Training, C++ Programming Training, Python Programming Training, Computer Basic Training, Advanced 
  Excel Training and lot of other computer course trainings . We also provide internships on real time 
  projects for the students who complete these computer training courses with us, which helps them in 
  gaining the industry level experience and growing their knowledge in the cutting edge technologies.">
 <meta name="keywords" content="<?php echo isset($post) ? $post->getKeywords() : "Provides:Python Programming Courses,Provides:Java,Provides:Web Designing,
    Provides:Mechanical Designing,Provides:Civil Designing,Provides:Digital Marketing,Provides:Basics of Computers,
    Provides:Advanced Excel,Provides:C Language Course,Provides:C++ Course,Provides:Cloud Computing Course"; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=5"/>
  <meta name="facebook-domain-verification" content="qqx5krln93swpggc4js7altuk5pydw" />
  <meta name="google-site-verification" content="pHsUdW-m6CQ0DRXmEYSWFLgYveQmQioX3b5R4Gfrq5U" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet " integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
  <link href="<?php echo '/css/dharwadhubballitutor.css'; ?>" rel="stylesheet">
  <link rel="stylesheet " rel="preload" as="font" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <style>
      .fb_customer_chat_bubble_animated_no_badge {
    background: none !important;
    border-radius: 50% !important;
    bottom: 50pt !important;
    display: inline !important;
    padding: 0px !important;
    position: fixed !important;
    right: 50pt !important;
    left:20pt !important;
    top: 20px !important;
}
  </style>

</head>




<body> 

<?php 
$PluginCode = DBfacebook::getPlugin();
echo $PluginCode->getPluginCode(); ?>
 <div class="contain">
 <div class="row container-fluid" style="padding-left:7px;padding-right:0px;">
    <div class="col-md-1"> <img src="<?php echo '/blogadmin/img/' . $business->getBusinessLogoImage() ?>" alt="dharwadhubballitutor" class="img-fluid" width="122px" height="123px" >
    </div>
    <div class="col-md-3">
      <a class="navbar-brand align-items-center" href="/">
        <?php echo $business->getBusinessName(); ?></a><br />
        <div class="navb">
      <div class="navbar-brands">
        <?php echo $business->getBusinessTag(); ?>
      </div>
</div> 
    </div>

    <div class="col-md-3 navbar-brands align-items-center" >
    <a class="navbar-brands align-items-center" href="tel: <?php echo $business->getBusinessContact(); ?>"> 
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-outbound-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5z" />
      </svg>
     <?php echo $business->getBusinessContact(); ?> </a>
    </div>

    <div class="col-md-3">
      <a class="navbar-brands align-items-center" href="mailto:<?php echo $business->getBusinessEmail(); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
          <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
        </svg> <?php echo $business->getBusinessEmail(); ?></a>
    </div>

    <div class="col-md-2 navbar-brands align-items-center" >
      <?php
      foreach ($socialMediaHandles as $handle) {
        echo '<a class="social-icon navbar-brand " style="  background:#1eb2a6;border-radius: 50%;width: 400px;height: 40px;font-size: medium;" href="' . $handle->getHandle() . '">' . $handle->getIcon() . '</a>';
      }
      ?>
    </div>
  </div>
 </div>
  

  <nav class="navbar navbar-expand-md navbar-light container-fluid">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="col-md-1"></div>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
          </li>
          <?php
          $categoryList = DBcategory::getAllCategory();
          sort($categoryList);
          foreach ($categoryList as $category) {
            if (empty($category->getMappedSubCategory())) {
              echo '<li class="nav-item">
                  <a class="nav-link" href="#">' . ucfirst(strtolower($category->getCategoryName())) . '</a>
                  </li>';
            } else {
              $dropdown = ' <li class="nav-item dropdown" >
            <a class="nav-link dropdown-toggle" href="#" id="' . ucfirst(strtolower($category->getCategoryName())) . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              ' . ucfirst(strtolower($category->getCategoryName())) . '
            </a>
           
            <ul class="dropdown-menu list-group-horizontal-md" aria-labelledby="navbarDropdown" style="left:-30px;">
            <div class="colcount">';
           
              foreach ($category->getMappedSubCategory($category->getCategoryId()) as $subcategory) {
                $dropdown .= '<li ><a class="dropdown-item font" href="#" >' . $subcategory->getSubCategoryName() . '</a><hr class="dropdown-divider">';
                $postList = DBpost::getPostBySubCategoryFornt($subcategory->getSubCategoryId());
                
                sort($postList);
                $dropdown .= '<ul class="dropdown-inner-menu" aria-labelledby="navbarDropdown" >';
                
                foreach ($postList as $navpost) {
                  $dropdown .= '<li class="nav-item" >
                  <a class="dropdown-item" href="' . $navpost->getPostUrl() . '">' . $navpost->getPostTitle() . '</a>
                  </li>';
                  
                }
               
                $dropdown .= '</ul></li>';
                
              }
              $dropdown .= '</div></ul></li>';
              
              echo $dropdown;
            }
          }
          ?>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo $configs['app_info']['appName']; ?>/about/">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo $configs['app_info']['appName']; ?>/contact/">Contact</a>
          </li>
         
        </ul>
      </div>
    </div>
  </nav>
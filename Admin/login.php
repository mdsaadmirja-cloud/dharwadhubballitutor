<?php
   
   include "../DB Operations/dbconnection.php";
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      $db=ConnectDb::getInstance();
      $myusername = mysqli_real_escape_string($db->getConnection(),$_POST['admin_name']);
      $mypassword = mysqli_real_escape_string($db->getConnection(),$_POST['admin_pass']); 
      
      $sql = "SELECT id FROM admin WHERE admin_name = '$myusername' and admin_pass = '$mypassword'";
      $result = mysqli_query($db->getConnection(),$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      // $active = $row['active'];
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         // echo "Validateed successfully";
         session_start();
         $_SESSION['login_user'] = $myusername;
         
         header("location: View/dashboard.php");
      }else {
         echo "Your Login Name or Password is invalid";
      }
   }
?>
<html>

    <head>
        <title>Login Page</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"
            id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #2a0a5e;
            height: 100vh;
        }

        #login #login-box {
            margin-top: 80px;
            max-width: 400px;
            height: 350px;
            border: 1px solid #9C9C9C;
            background-color: #f8c000;
            border-radius: 20px;
        }

        #login #login-box #login-form {
            padding: 20px;
        }

        #login #login-box #login-form #register-link {
            margin-top: 1px;
        }

        .label {
            color: #2a0a5e;
        }

        .btn-warning {
            background-color: #2a0a5e;
            color: #f8c000;
        }

        .btn-warning:hover {
            background-color: #2a0a5e;
            color: #f8c000;
        }
        </style>
    </head>

    <body>
        <div id="login">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div id="login-box" class="col-md-6">
                            <h2 style="color: #2a0a5e">Login</h2><br />
                            <form id="login-form" class="form" action="" method="post">

                                <div class="form-group">
                                    <label for="admin_name" class="label"><b>Admin-name:</b></label><br>
                                    <input type="text" name="admin_name" id="admin_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="admin_pass" class="label"><b>Password:</b></label><br>
                                    <input type="password" name="admin_pass" id="admin_pass" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning">Submit</button>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-6">
                            <img src="../Admin/media/admin.png" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center">
                        <h2 class=" text-white pt-5">DharwadHubballiTutor </h2>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </body>

</html>
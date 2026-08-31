
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Business Management System using PHP - Login</title>

        <!-- Custom fonts for this template-->

        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <!-- Custom styles for this template-->


        <style>
        .bg-gradient-primary {
           
            background-repeat: no-repeat;
            background-size: cover;

        }

        .p-5 {
            padding: 3rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-4,
        .my-4 {
            margin-bottom: 2.5rem !important;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: Nunito, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #858796;
            text-align: left;
            background-color: #fff;

        }

        .form-group {
            margin-bottom: 1rem;
        }

        .text-gray-900 {
            color: #3a3b45 !important;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-primary {
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }

        body {
            height: 100%;
        }

        .my-5 {
            margin-top: 7rem !important;
            margin-bottom: 3rem !important;
        }

        .h4,
        h4 {
            font-size: 2rem;
        }
        </style>
    </head>

    <body class="bg-gradient-primary">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-5 col-lg-5 col-md-6">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">CMS</h1>
                                        </div>
                                        <form method="post" id="login_form" action="../controller/login.php">
                                            <div class="form-group">
                                                <input type="text" name="user_email" id="user_email"
                                                    class="form-control" required data-parsley-type="email"
                                                    data-parsley-trigger="keyup" placeholder="Enter Email Address..." />
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="user_password" id="user_password"
                                                    class="form-control" required data-parsley-trigger="keyup"
                                                    placeholder="Password" />
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="login_button" id="login_button"
                                                    class="btn btn-primary btn-user btn-block">Login</button>
                                            </div>
                                            <footer class="sticky-footer bg-white">
                                                <div class="container my-auto">
                                                    <div class="copyright text-center my-auto">
                                                        <span>Copyright &copy; dharwadhubballitutor
                                                            <?php echo date('Y'); ?></span>
                                                    </div>
                                                </div>
                                            </footer>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
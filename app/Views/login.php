<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <!-- CSS only -->
        <link rel="stylesheet" href="<?= base_url("assets/css/bootstrap.min.css") ?>">
        <style>
        .wrapper {
            align-items: stretch;
            display: flex;
            width: 100%;
            //background: #222e3c;
        }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header text-center">Login</div>
                        <div class="card-body">
                            <form action="" method="post" class="p-4">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" required="required">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- JS, Popper.js, and jQuery -->
    <script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>" crossorigin="anonymous"></script> <!-- from https://releases.jquery.com/ -->
    <script src="<?= base_url('assets/js/popper.min.js') ?>" crossorigin="anonymous"></script> <!-- from getbootstrap.com -->
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>" crossorigin="anonymous"></script> <!-- from getbootstrap.com -->
    </body>
</html>
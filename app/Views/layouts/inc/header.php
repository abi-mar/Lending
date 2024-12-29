<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= (isset($pageTitle)) ? $pageTitle : 'Document'?></title>
        <!-- CSS only -->
        <link rel="stylesheet" href="<?= base_url("assets/css/bootstrap.min.css") ?>">
        <link rel="stylesheet" href="<?= base_url("assets/css/dataTables.bootstrap5.css") ?>">        
        <link rel="stylesheet" href="<?= base_url("assets/css/datepicker.min.css") ?>"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- chosen select -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
        
        <style>
        .wrapper {
            align-items: stretch;
            display: flex;
            width: 100%;
            //background: #222e3c;
        }

        // from copilot for sidebar
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 15px;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
        }
        .sidebar a.active {
            background-color: #007bff;
            color: #fff;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }

        .chosen-container .chosen-single {
            height: 40px;
            line-height: 40px;
        }
        .chosen-container .chosen-drop {
            width: 100% !important;
        }
        </style>
        
        <script src="<?= base_url('assets/js/jquery-3.7.1.min.js') ?>" crossorigin="anonymous"></script> <!-- from https://releases.jquery.com/ -->
        <script src="<?= base_url('assets/js/popper.min.js') ?>" crossorigin="anonymous"></script> <!-- from getbootstrap.com -->        
        <!-- datatables based from https://datatables.net/examples/styling/bootstrap5.html -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>" crossorigin="anonymous"></script>        
        <script src="<?= base_url('assets/js/dataTables.min.js') ?>" crossorigin="anonymous"></script>        
        <script src="<?= base_url('assets/js/dataTables.bootstrap5.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/js/datepicker.min.js') ?> "></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        
    </head>

<body>
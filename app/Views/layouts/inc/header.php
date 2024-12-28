<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= (isset($pageTitle)) ? $pageTitle : 'Document'?></title>
        <!-- CSS only -->
        <link rel="stylesheet" href="<?= base_url("assets/css/bootstrap.min.css") ?>">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">        
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
        </style>
        
        <script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>" crossorigin="anonymous"></script> <!-- from https://releases.jquery.com/ -->
        <script src="<?= base_url('assets/js/popper.min.js') ?>" crossorigin="anonymous"></script> <!-- from getbootstrap.com -->
        <script src="<?= base_url('assets/js/bootstrap.min.js') ?>" crossorigin="anonymous"></script> <!-- from getbootstrap.com -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    </head>

<body>
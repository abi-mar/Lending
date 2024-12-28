<!--nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="dashboardlogo">
        <div class="name"></div>
        <a class="sidebar-brand" href="index.html">
            <img alt="Company Logo" src="<?= base_url('assets/img/CLMS.png') ?>">
        </a>
    </div>
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url('lending/dashboard') ?>">
                    Dashboard 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('lending/customer') ?>">
                    Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('lending/loan') ?>">
                    Loans
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    FAQ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('lending/logout') ?>">
                    Log Out
                </a>
            </li>
        </ul>
    </div>
</nav-->

<div class="sidebar">
        <h2>Dashboard</h2>
        <a href="<?= base_url('lending/dashboard') ?>" class="active">Home</a>
        <a href="<?= base_url('lending/customer') ?>">Customers</a>
        <a href="<?= base_url('lending/loan') ?>">Loans</a>
        <a href="#">Users</a>
        <a href="<?= base_url('lending/logout') ?>">Logout</a>
    </div>

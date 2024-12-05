<?= $this->include('layouts/inc/header') ?>

<div class="container-fluid">
    <div class="wrapper">
        <?= $this->include('layouts/inc/sidebar') ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
		
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4>Customer Records</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile #</th>
                                    <th>Action</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($customers): ?>
                                    <?php foreach($customers as $row) : 
                                        $name = $row['surname'].', '.$row['firstname'].' '.$row['middlename'].' '.$row['suffix'];
                                        ?>
                                    <tr>
                                        <td><?php echo $row['custno']; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['mobileno']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div>
        </div>
        
        </main>
    </div>
</div>

<?= $this->include('layouts/inc/footer') ?>
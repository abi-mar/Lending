<?= $this->include('layouts/inc/header') ?>

<div class="container-fluid">
    <div class="wrapper">
        <?= $this->include('layouts/inc/sidebar') ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">

                <?php if (session()->getFlashdata('status')) { ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Message:</strong> <?= session()->getFlashdata('status') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php }?>                    

                    <div class="card">
                        <div class="card-header">
                            <h4>Customer Records
                                <a href="<?= base_url('lending/customer/create') ?>" class="btn btn-success btn-sm float-end">ADD</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Mobile #</th>
                                        <th>Image</th>
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
                                            <td><img src="<?= base_url('uploads/images/').$row['image']; ?>" alt="image" height="100px" width="100px" /></td>
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
        </div>
        
        </main>
    </div>
</div>

<?= $this->include('layouts/inc/footer') ?>
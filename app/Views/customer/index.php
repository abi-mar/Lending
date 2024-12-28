<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>

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
                    <table id="customerTable" class="table table-striped">
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
                                <tr id="<?php echo $row['custno']; ?>">
                                    <td><?php echo $row['custno']; ?></td>
                                    <td><?php echo $name; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['mobileno']; ?></td>                                            
                                    <td>
                                        <a href="<?= base_url('lending/customer/edit/'.$row['custno']) ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="#" onclick="return false;" class="btn btn-danger btn-sm">Delete</a>
                                        <a href="<?= base_url('lending/loan/create/'.$row['custno']) ?>" class="btn btn-warning btn-sm">Loan</a>
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
<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#customerTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<?=$this->endSection()?>
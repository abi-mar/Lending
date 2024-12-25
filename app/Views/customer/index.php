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
        
        </main>
    </div>
</div>

<script>   

    $('.btn-danger').click(function(){
        var id = $(this).parents("tr").attr("id");

        if(confirm('Are you sure to remove this record ?')){
        // send delete request
            $.ajax({
                url: '<?= base_url('lending/customer/delete/') ?>'+id,
                type: 'DELETE',
                error: function() {
                    alert('Something is wrong');
                },
                success: function(data) {
                    $("#"+id).remove();
                    alert("Record removed successfully");
                }
            });
        }
        // BootstrapDialog.show({
        //     title: 'Confirmation Message',
        //     message: 'Are you sure you want to delete this customer?',
        //     buttons: [{
        //         label: 'Yes',
        //         // no title as it is optional
        //         cssClass: 'btn-primary',
        //         action: function(){
        //             // send delete request
        //             $.ajax({
        //                 url: '<?= base_url('lending/customer/delete/') ?>'+id,
        //                 type: 'DELETE',
        //                 error: function() {
        //                     alert('Something is wrong');
        //                 },
        //                 success: function(data) {
        //                     $("#"+id).remove();
        //                     alert("Record removed successfully");
        //                 }
        //             });           
        //         }
        //     }, {                
        //         label: 'No',
        //         // no title as it is optional                
        //         cssClass: 'btn-error',
        //         action: function(dialogItself){
        //             dialogItself.close();                    
        //         }
        //     }]
        // });
    });

    
    function confirm_delete() {        
        //base_url('lending/customer/delete/'.$row['custno']) ?>
        return false;
        

    }
    
</script>

<?= $this->include('layouts/inc/footer') ?>

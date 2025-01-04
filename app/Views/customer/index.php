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
                                <th>Balance</th>
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
                                    <td><?php echo $row['balance']; ?></td>
                                    <td>
                                        <a href="<?= base_url('lending/customer/edit/'.$row['custno']) ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <!--a href="<?= base_url('lending/customer/delete/'.$row['custno']) ?>" class="btn btn-danger btn-sm">Delete</a-->
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary btn-sm" id="btnLoadPrevious" style="display:none">Load Previous 1000 records</button>
                    <button class="btn btn-primary btn-sm" id="btnLoadNext">Load Next 1000 records</button>
                </div>
            </div>    
        </div>                
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this customer record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>        
      </div>
    </div>
  </div>
</div>

<!-- Initialize DataTables -->
<script>    
    var pageCounter = 1;    
    var $overall_count = <?= $total_count; ?>;

    if (pageCounter*1000 >= $overall_count) {
        $('#btnLoadNext').hide();
    }

    $('#customerTable').DataTable({
        "order": [[0, "desc"]]
    });

    $('#customerTable').on('click', '.btn-danger', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        $('#confirmationModal').modal('show');

        $('#confirmationModal .btn-primary').off('click').on('click', function() {
            window.location.href = link;
        });
    });
    

    $('#btnLoadPrevious').click(function() {
        pageCounter--;
        $.ajax({
            url: '<?= base_url('lending/payment/getPaymentsByBatch');?>',
            type: 'GET',
            data: { offset: (pageCounter - 1)*1000},
            success: function(response) {
                $('#PaymentsTable tbody').html(response);
                
                if(pageCounter > 1) {
                    $('#btnLoadPrevious').prop('disabled', false);
                } else {
                    $('#btnLoadPrevious').prop('disabled', true);
                }

                if (pageCounter*1000 >= $overall_count) {
                    $('#btnLoadNext').prop('disabled', true);
                }
            }
        });
    });

    $('#btnLoadNext').click(function() {
        pageCounter++;
        $.ajax({
            url: '<?= base_url('lending/payment/getPaymentsByBatch');?>',
            type: 'GET',
            data: { offset: (pageCounter - 1)*1000},
            success: function(response) {
                $('#PaymentsTable tbody').html(response);

                if(pageCounter > 1) {
                    $('#btnLoadPrevious').prop('disabled', false);
                } else {
                    $('#btnLoadPrevious').prop('disabled', true);
                }
                
                if (pageCounter*1000 >= $overall_count) {
                    $('#btnLoadNext').prop('disabled', true);
                }
            }
        });
    });
</script>
<?=$this->endSection()?>
<?=$this->extend('layouts/main')?>
<?=$this->section('content')?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

        <?php if (session()->getFlashdata('status')) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Message:</strong> <?= session()->getFlashdata('status') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }?>                    

        <?php if (session()->getFlashdata('error')) { ?>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php }?>

            <div class="card">
                <div class="card-header">
                    <h4>Loan Records
                        <a href="<?= base_url('lending/loan/create') ?>" class="btn btn-success btn-sm float-end">ADD</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table id="loanTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Loan Amount</th>
                                <th>Net Proceeds</th>
                                <th>Loan Date</th>
                                <th>Weekly Amortization</th>
                                <th>Total Amount for Payment</th>
                                <th>Date Added</th>
                                <th>Added By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($loans): ?>
                                <?php foreach($loans as $row) : ?>
                                <tr>
                                <td><?= $row['row_id']; ?></td>
                                    <td><?= $row['surname'].', '.$row['firstname']; ?></td>
                                    <td><?= number_format($row['loan_amount'], 2); ?></td>
                                    <td><?= number_format($row['net_proceeds'], 2); ?></td>
                                    <td><?= $row['loan_date']; ?></td>
                                    <td><?= number_format($row['weekly_amortization'], 2); ?></td>
                                    <td><?= number_format($row['amount_topay'], 2); ?></td>
                                    <td><?= $row['date_added']; ?></td>
                                    <td><?= $row['added_by']; ?></td>
                                    
                                    <td>
                                        <a href="<?= base_url('lending/payment/perLoan/'.$row['row_id']) ?>" class="btn btn-primary btn-sm">Payments</a>                                                
                                        <!--a href="<?= base_url('lending/loan/delete/'.$row['row_id']) ?>" class="btn btn-danger btn-sm">Delete</a-->
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this loan record?
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
    $(document).ready(function() {
        $('#loanTable').DataTable({
            "order": [[0, "desc"]]
        });

        $('#loanTable').on('click', '.btn-danger', function(e) {
            e.preventDefault();
            var link = $(this).attr('href');
            $('#confirmationModal').modal('show');

            $('#confirmationModal .btn-primary').off('click').on('click', function() {
                window.location.href = link;
            });
        });
    });
</script>
<?=$this->endSection()?>
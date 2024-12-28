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

            <div class="card">
                <div class="card-header">
                    <h4>Loan Records</h4>
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
                                <td><?php echo $row['row_id']; ?></td>
                                    <td><?php echo $row['surname'].', '.$row['firstname']; ?></td>
                                    <td><?php echo $row['loan_amount']; ?></td>
                                    <td><?php echo $row['net_proceeds']; ?></td>
                                    <td><?php echo $row['loan_date']; ?></td>
                                    <td><?php echo $row['weekly_amortization']; ?></td>
                                    <td><?php echo $row['amount_topay']; ?></td>
                                    <td><?php echo $row['date_added']; ?></td>
                                    <td><?php echo $row['added_by']; ?></td>
                                    
                                    <td>                                                
                                        <a href="<?= base_url('lending/loan/delete/'.$row['row_id']) ?>" class="btn btn-danger btn-sm">Delete</a>
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
        $('#loanTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<?=$this->endSection()?>
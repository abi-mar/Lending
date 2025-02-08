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

        <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
            <?php 
            $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($daysOfWeek as $index => $day): ?>
                <li class="nav-item" role="presentation">
                    <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="<?= strtolower($day) ?>-tab" data-bs-toggle="tab" href="#<?= strtolower($day) ?>" role="tab" aria-controls="<?= strtolower($day) ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"><?= $day ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <table class="table table-striped table-bordered" id="pendingPaymentsTable">        
            <thead>
                <tr>
                    <th>Group of Client</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>                
            </tbody>
        </table>
        <!--div class="tab-content" id="paymentTabsContent">
            <?php foreach ($daysOfWeek as $index => $day): ?>
                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="<?= strtolower($day) ?>" role="tabpanel" aria-labelledby="<?= strtolower($day) ?>-tab">
               
                     hello
                </div>
            <?php endforeach; ?>
        </div-->

            
        </div>                
    </div>
</div>

<!-- Initialize DataTables -->
<script>    
    $(document).ready(function() {
        // $('#pendingPaymentsTable').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": true,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true
        // });

        $('#pendingPaymentsTable').DataTable();

        
    });
    // columns: group of client, customer name, address, amount
    // action: print
    document.addEventListener('DOMContentLoaded', function() {
        var paymentTabs = document.getElementById('paymentTabs');
        var tabs = paymentTabs.querySelectorAll('.nav-link');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(event) {
                var day = event.target.getAttribute('aria-controls');

                var url = '<?= base_url('lending/payment/getPendingPayPerDay/') ?>'+day;

                $.get(url, function(data) {
                    var pendingInfo = JSON.parse(data);
                    
                    var table = $('#pendingPaymentsTable').DataTable();
                    table.clear().draw();

                    pendingInfo.forEach(function(info) {
                        table.row.add([
                            '',
                            info.surname + ', ' + info.firstname + ' ' + info.middlename,
                            info.address,
                            info.weekly_amortization
                        ]).draw();
                    });
                });
            });
        });
    });

</script>
<?=$this->endSection()?>
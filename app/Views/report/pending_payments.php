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
                    <h4>Pending Payments
                        <button id="downloadBtn" class="btn btn-primary float-end">Download</button>
                    </h4>
                </div>

            <div class="card-body">
                <!-- <div class="alert alert-success" role="alert" id="alertMessage" style="display: none;">
                    Data Loaded Successfully !!!
                </div> -->

                <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                    <?php 
                    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $dayNum = 0;
                    foreach ($daysOfWeek as $index => $day): ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="<?= strtolower($day) ?>-tab" data-bs-toggle="tab" href="#<?= strtolower($day) ?>" role="tab" aria-controls="<?= $dayNum++;?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"><?= $day ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <strong> Date: </strong> <span id="dateNav"></span>
                </div>                

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
            </div>
        </div>
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


<script>    

$(document).ready(function() {
    var today = new Date();
    var todayDayNumber = today.getDay();
    var url = '<?= base_url('lending/report/getPendingPayPerDay/') ?>'+todayDayNumber;

    $('.nav-link').removeClass('active');
    $('a.nav-link[aria-controls="' + todayDayNumber + '"]').addClass('active');

    $('#dateNav').text(getDateForDayInWeek(todayDayNumber));

    $.get(url, function(data) {
        var pendingInfo = JSON.parse(data);

        console.log(pendingInfo);
        
        var table = $('#pendingPaymentsTable').DataTable();
        table.clear().draw();

        pendingInfo.forEach(function(info) {
            table.row.add([
                info.client_group ? info.client_group : 'N/A',
                info.surname + ', ' + info.firstname + ' ' + info.middlename,
                info.address,
                info.weekly_amortization
            ]).draw();
        });
    });
    
});

    $('#downloadBtn').on('click', function() {
        var table = $('#pendingPaymentsTable').DataTable();
        var data = table.rows().data().toArray();
        var csv = 'Group of Client,Customer Name,Address,Amount\n';

        data.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
        });

        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var url = URL.createObjectURL(blob);
        var hiddenElement = document.createElement('a');
        hiddenElement.href = url;
        hiddenElement.target = '_blank';
        hiddenElement.download = 'pending_payments.csv';
        hiddenElement.click();
        URL.revokeObjectURL(url);
    });
    /* Given a week day number, return the date for that
    * day in the current week.
    *
    * @param {number} dayNumber - number of day in week.
    *   If 0, returns Sunday at start of week
    *   If 7, returns Sunday at end of week
    *   Otherwise 1 Mon, 2 Tue, etc.
    *   If not an integer in range 0 to 7 returns undefined
    * @returns {number|undefined} date of supplied day number
    */
    function getDateForDayInWeek(dayNumber) {
        dayNumber = Number(dayNumber);
        // Validate input
        if (dayNumber < 0 ||
            dayNumber > 7 ||
            parseInt(dayNumber) != dayNumber) {
            return; // undefined
        }
        let d = new Date();
        d.setDate(d.getDate() - d.getDay() + dayNumber);
        
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return monthNames[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    // Initialize DataTables 
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
    
    // columns: group of client, customer name, address, amount
    // action: print
    document.addEventListener('DOMContentLoaded', function() {
        var paymentTabs = document.getElementById('paymentTabs');
        var tabs = paymentTabs.querySelectorAll('.nav-link');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(event) {
                var day = event.target.getAttribute('aria-controls');

                console.log(day);
                console.log(getDateForDayInWeek(day));

                $('#dateNav').text(getDateForDayInWeek(day));

                var url = '<?= base_url('lending/report/getPendingPayPerDay/') ?>'+day;

                var spinner = $('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
                $('#pendingPaymentsTable').before(spinner);

                $.get(url, function(data) {
                    spinner.remove();
                    var pendingInfo = JSON.parse(data);
                    
                    var table = $('#pendingPaymentsTable').DataTable();
                    table.clear().draw();

                    pendingInfo.forEach(function(info) {
                        table.row.add([
                            info.client_group ? info.client_group : 'N/A',
                            info.surname + ', ' + info.firstname + ' ' + info.middlename,
                            info.address,
                            info.weekly_amortization
                        ]).draw();
                    });
                });

                // $('#alertMessage').fadeIn().delay(1000).fadeOut('slow');                    
                
            });
        });
    });

</script>
<?=$this->endSection()?>
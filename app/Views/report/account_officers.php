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
                        <h4>Account Officers</h4>
                    </div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="aoTabs" role="tablist">
                    <?php 
                        // $accountOfficers = ['AO1', 'AO2', 'AO3', 'AO4', 'AO5', 'AO6', 'AO7', 'AO8', 'AO9', 'AO10'];
                        //$accountOfficers = json_encode($accountOfficers);
                        // print_r($accountOfficers);

                        foreach ($accountOfficers as $officer) {                            
                            $aoArr[] = $officer['firstname'];
                            $aoArrId[] = $officer['firstname'];
                        }

                        foreach ($accountOfficers as $index => $officer): ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="<?= $officer['firstname'] ?>-tab" data-bs-toggle="tab" href="#<?= $officer['firstname'] ?>" role="tab" aria-controls="<?= $officer['row_id'] ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"><?= $officer['firstname'] ?></a>
                            </li>
                        
                        <?php endforeach; ?>
                      
                    </ul>    

                    <div class="card-body">                    
                        <table id="customerTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile #</th>
                                    <th>Balance</th>                               
                                </tr>
                            </thead>
                            <tbody>                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>                
    </div>
</div>


<script>
    // load customers for the first (tab) Account Officer
    $(document).ready(function() {        
        var url = '<?= base_url('lending/report/getCustomersPerAo/') ?>'+1;

        $.get(url, function(data) {
            var customerInfo = JSON.parse(data);
            
            var table = $('#customerTable').DataTable();
            table.clear().draw();

            customerInfo.forEach(function(info) {
                name = info.surname + ', ' + info.firstname + ' ' + info.middlename + ' ' +  info.suffix;
                table.row.add([
                    info.custno,
                    name,                            
                    info.address,
                    info.mobileno,
                    info.balance
                ]).draw();
            });
        });
        
    });

    $('#customerTable').DataTable();

    document.addEventListener('DOMContentLoaded', function() {
        var aoTabs = document.getElementById('aoTabs');
        var tabs = aoTabs.querySelectorAll('.nav-link');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(event) {
                var aoId = event.target.getAttribute('aria-controls');

                console.log(aoId);
                var url = '<?= base_url('lending/report/getCustomersPerAo/') ?>'+aoId;

                $.get(url, function(data) {
                    var customerInfo = JSON.parse(data);
                    
                    var table = $('#customerTable').DataTable();
                    table.clear().draw();

                    customerInfo.forEach(function(info) {
                        name = info.surname + ', ' + info.firstname + ' ' + info.middlename + ' ' +  info.suffix;
                        table.row.add([
                            info.custno,
                            name,                            
                            info.address,
                            info.mobileno,
                            info.balance
                        ]).draw();
                    });
                });
            });
        });
    });

</script>
<?=$this->endSection()?>
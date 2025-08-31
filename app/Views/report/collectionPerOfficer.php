<?=$this->extend('layouts/main')?>
<?=$this->section('content')?>
<style>
    .table th, .table td {
        padding: 0.25rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    label {
        font-weight: bold;
    }
</style>
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
                    <h4>Collection Report
                        <button id="btnExport" class="btn btn-success btn-sm float-end">Export</button>
                    </h4>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="accountOfficer" class="col-sm-2 col-form-label">Account Officer:</label>
                        <div class="col-sm-10">
                            <select id="accountOfficer" class="form-select w-auto">
                                <!--option value="0">All</option-->
                                <?php foreach ($accountOfficers as $ao) { ?>
                                    <option value="<?= $ao['row_id'] ?>"><?= $ao['firstname'].' '.$ao['surname'] ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Report Type:</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="report_type" id="perDay" value="perDay" checked>
                                <label class="form-check-label" for="summary">Per Day</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="report_type" id="perWeek" value="perWeek">
                                <label class="form-check-label" for="detailed">Per Week</label>
                            </div>
                        </div>
                        <div id="perDayContainer" class="mb-3 row">
                            <label for="collectionDate" class="col-sm-2 col-form-label">Date Collection:</label>
                            <div class="col-sm-10">
                                <input type="text" id="collectionDate" name="collection_date" class="form-control datepicker w-auto" placeholder="Select Collection Date" required/>
                            </div>
                        </div>
                        <div id="perWeekDateContainer" class="mb-3 row" style="display:none;">
                            <label for="weekStartDate" class="col-sm-2 col-form-label">Week Start Date:</label>
                            <div class="col-sm-10">
                                <input type="text" id="weekStartDate" name="week_start_date" class="form-control datepicker w-auto" placeholder="Select Week Start Date"/>
                            </div>
                        </div>
                        <script>
                            $('input[name="report_type"]').on('change', function() {
                                if ($(this).val() === 'perWeek') {
                                    $('#perDayContainer').hide();
                                    $('#perWeekDateContainer').show();
                                    $('#collectionDate').prop('disabled', true);
                                } else {
                                    $('#perDayContainer').show();
                                    $('#perWeekDateContainer').hide();                                    
                                    $('#collectionDate').prop('disabled', false);
                                }
                            });
                        </script>
                    </div>
                    <div class="mb-3 row">
                        <label for="loanCyclelbl" class="col-sm-2 col-form-label">Loan Cycle:</label>
                        <div class="col-sm-10">
                            <span id="loanCyclelbl" class="form-control-plaintext"></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-2">
                            <button id="btnGenerate" class="btn btn-primary">Generate</button>
                        </div>
                    </div>

                    <div id="perDayOutput">
                        <table id="collectionsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th> <!-- for the checkbox -->
                                    <th>Client ID</th>
                                    <th>Client Name</th>
                                    <th>Savings</th>
                                    <th>Week no.</th>
                                    <th>Loan Balance</th>
                                    <th>Delinquent (DQ)</th>
                                    <th>Current</th>
                                    <th id="last_week"></th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>                           
                            </tbody>
                        </table>
                    </div>

                    <div id="perWeekOutput" style="display:none;">
                    </div>
                </div>
            </div>
            
        </div>                
    </div>
</div>

<script>
    $(document).ready(function() {

        $('input[name="collection_date"]').on('change', function() {
            var date = new Date($(this).val());
            var month = date.getMonth();
            var cycle = (month >= 0 && month <= 5) ? 'First Cycle' : 'Second Cycle';
            $('#loanCyclelbl').text(cycle);

            var lastWeekDate = new Date(date);
            lastWeekDate.setDate(lastWeekDate.getDate() - 7);
            var monthNames = ["January", "February", "March", "April", "May", "June", 
                      "July", "August", "September", "October", "November", "December"];
            var lastWeekFormatted = monthNames[lastWeekDate.getMonth()] + ' ' + ('0' + lastWeekDate.getDate()).slice(-2);
            $('#last_week').text(lastWeekFormatted);
        });

        $('#btnGenerate').on('click', function() {

            if ($('input[name="report_type"]:checked').val() === 'perDay') {
                $('#perWeekOutput').hide();
                $('#perDayOutput').show();
                $('#collectionsTable tbody').empty();
                // Validate inputs
                var accountOfficer = $('#accountOfficer').val();
                var collectionDate = $('#collectionDate').val();

                if (!accountOfficer) {
                alert('Please select an Account Officer.');
                return false;
                }

                if (!collectionDate) {
                alert('Please select a Collection Date.');
                return false;
                }

                // Proceed with generating the report
                // Add your report generation logic here
                var url = '<?= base_url('lending/report/getCollectionPerOfficer/') ?>'+accountOfficer+'/'+collectionDate;

                $.get(url, function(data) {
                    var collections = JSON.parse(data);
                    var rows = '';

                    // monitor totals
                    var totalSavings = 0;
                    var totalLoanBalance = 0;
                    var totalDQ = 0;
                    var totalCurrent = 0;
                    var totalLastWeek = 0;


                    collections.forEach(function(collection, index) {
                        rows += '<tr>';
                        rows += '<td><input type="checkbox" name="client_id[]" value="'+collection.custno+'"/></td>';
                        rows += '<td>'+collection.custno+'</td>';
                        rows += '<td>'+collection.client_name+'</td>';
                        rows += '<td>'+collection.savings+'</td>';                    
                        rows += '<td>'+collection.weekno+'</td>';
                        rows += '<td>'+collection.balance+'</td>';
                        rows += '<td>'+(collection.DQ === null || collection.DQ === 0 ? '' : collection.DQ)+'</td>';
                        rows += '<td>'+collection.remaining_debt+'</td>';
                        rows += '<td>'+collection.previous_amount+'</td>';
                        rows += '<td></td>';
                        rows += '</tr>';

                        totalSavings += parseFloat(collection.savings);
                        totalLoanBalance += parseFloat(collection.balance);
                        totalDQ += parseFloat(collection.DQ);
                        totalCurrent += parseFloat(collection.remaining_debt);
                        totalLastWeek += parseFloat(collection.previous_amount);
                    });

                    if (collections.length === 0) {
                        alert('No collections for this date.');
                        return;
                    }

                    // append another row for the total
                    rows += '<tr style="background-color: yellow; font-weight: bold;">';
                    rows += '<td colspan="3">Total</td>';
                    rows += '<td>'+totalSavings.toFixed(2)+'</td>';
                    rows += '<td></td>';
                    rows += '<td>'+totalLoanBalance.toFixed(2)+'</td>';
                    rows += '<td></td>'; // no total for DQ
                    rows += '<td>'+totalCurrent.toFixed(2)+'</td>';
                    rows += '<td>'+totalLastWeek.toFixed(2)+'</td>';
                    rows += '<td></td>';
                    rows += '</tr>';                

                    $('#collectionsTable tbody').html(rows);                    
                    // $('#collectionsTable').show();
                });
            } else { // Per Week report
                $('#perDayOutput').hide();                
                // Validate inputs
                var accountOfficer = $('#accountOfficer').val();
                var weekStartDate = $('#weekStartDate').val();

                if (!accountOfficer) {
                    alert('Please select an Account Officer.');
                    return false;
                }
                if (!weekStartDate) {
                    alert('Please select a Week Start Date.');
                    return false;
                }

                // Proceed with generating the report
                var startDate = new Date(weekStartDate);
                var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var tabsHtml = '<ul class="nav nav-tabs" id="weekTabs" role="tablist">';
                var tabContentHtml = '<div class="tab-content" id="weekTabsContent">';

                for (var i = 0; i < 7; i++) {
                    var tabDate = new Date(startDate);
                    tabDate.setDate(startDate.getDate() + i);
                    var tabId = 'tab' + i;
                    var dayName = daysOfWeek[tabDate.getDay()];
                    var tabLabel = dayName + ' (' + (tabDate.getMonth() + 1) + '/' + tabDate.getDate() + ')';

                    tabsHtml += '<li class="nav-item" role="presentation">' +
                        '<button class="nav-link' + (i === 0 ? ' active' : '') + '" id="' + tabId + '-tab" data-bs-toggle="tab" data-bs-target="#' + tabId + '" type="button" role="tab" aria-controls="' + tabId + '" aria-selected="' + (i === 0 ? 'true' : 'false') + '">' +
                        tabLabel + '</button></li>';

                    tabContentHtml += '<div class="tab-pane fade' + (i === 0 ? ' show active' : '') + '" id="' + tabId + '" role="tabpanel" aria-labelledby="' + tabId + '-tab">' +
                        '<div class="table-responsive"><table class="table table-striped"><thead><tr>' +
                        '<th></th><th>Client ID</th><th>Client Name</th><th>Savings</th><th>Week no.</th><th>Loan Balance</th><th>Delinquent (DQ)</th><th>Current</th><th id="last_week_' + i + '"></th><th>Payment</th>' +
                        '</tr></thead><tbody id="tbody_' + i + '"></tbody></table></div></div>';
                }
                tabsHtml += '</ul>';
                tabContentHtml += '</div>';

                $('#perWeekOutput').html(tabsHtml + tabContentHtml);
                $('#perWeekOutput').show();
                // $('#collectionsTable').hide();

                // Fetch collections for each day
                for (let i = 0; i < 7; i++) {
                    let tabDate = new Date(startDate);
                    tabDate.setDate(startDate.getDate() + i);
                    let formattedDate = tabDate.toISOString().slice(0,10);
                    let url = '<?= base_url('lending/report/getCollectionPerOfficer/') ?>'+accountOfficer+'/'+formattedDate;

                    $.get(url, function(data) {
                        var collections = JSON.parse(data);
                        var rows = '';
                        var totalSavings = 0;
                        var totalLoanBalance = 0;
                        var totalDQ = 0;
                        var totalCurrent = 0;
                        var totalLastWeek = 0;

                        collections.forEach(function(collection) {
                            rows += '<tr>';
                            rows += '<td><input type="checkbox" name="client_id[]" value="'+collection.custno+'"/></td>';
                            rows += '<td>'+collection.custno+'</td>';
                            rows += '<td>'+collection.client_name+'</td>';
                            rows += '<td>'+collection.savings+'</td>';                    
                            rows += '<td>'+collection.weekno+'</td>';
                            rows += '<td>'+collection.balance+'</td>';
                            rows += '<td>'+(collection.DQ === null || collection.DQ === 0 ? '' : collection.DQ)+'</td>';
                            rows += '<td>'+collection.remaining_debt+'</td>';
                            rows += '<td>'+collection.previous_amount+'</td>';
                            rows += '<td></td>';
                            rows += '</tr>';

                            totalSavings += parseFloat(collection.savings);
                            totalLoanBalance += parseFloat(collection.balance);
                            totalDQ += parseFloat(collection.DQ);
                            totalCurrent += parseFloat(collection.remaining_debt);
                            totalLastWeek += parseFloat(collection.previous_amount);
                        });

                        if (collections.length === 0) {
                            rows += '<tr><td colspan="10" class="text-center">No collections for this date.</td></tr>';
                        } else {
                            rows += '<tr style="background-color: yellow; font-weight: bold;">';
                            rows += '<td colspan="3">Total</td>';
                            rows += '<td>'+totalSavings.toFixed(2)+'</td>';
                            rows += '<td></td>';
                            rows += '<td>'+totalLoanBalance.toFixed(2)+'</td>';
                            rows += '<td></td>';
                            rows += '<td>'+totalCurrent.toFixed(2)+'</td>';
                            rows += '<td>'+totalLastWeek.toFixed(2)+'</td>';
                            rows += '<td></td>';
                            rows += '</tr>';
                        }

                        $('#tbody_' + i).html(rows);

                        // Set last week column header
                        var lastWeekDate = new Date(tabDate);
                        lastWeekDate.setDate(tabDate.getDate() - 7);
                        var monthNames = ["January", "February", "March", "April", "May", "June", 
                                  "July", "August", "September", "October", "November", "December"];
                        var lastWeekFormatted = monthNames[lastWeekDate.getMonth()] + ' ' + ('0' + lastWeekDate.getDate()).slice(-2);
                        $('#last_week_' + i).text(lastWeekFormatted);
                    });
                }
            }
        });

        $('#btnExport').on('click', function() {
            var accountOfficer = $('#accountOfficer').val();
            var collectionDate = $('#collectionDate').val();
            var loanCycle = $('#loanCyclelbl').text();

            if (!accountOfficer) {
            alert('Please select an Account Officer.');
            return false;
            }

            

            if ($('input[name="report_type"]:checked').val() === 'perDay') {
                if (!collectionDate) {
                alert('Please select a Collection Date.');
                return false;
                }

                var url = '<?= base_url('lending/report/exportCollectionPerOfficer') ?>';
                $.post(url, 
                    { 
                        account_officer: accountOfficer, 
                        collection_date: collectionDate, 
                        loan_cycle: loanCycle, 
                        account_officer_name: $('#accountOfficer option:selected').text()
                    }, 
                    function(response) {
                        // Handle the response
                        var resp = JSON.parse(response);
                        alert('Report has been exported successfully. Complete path: '+resp.file);
                    }
                );
            } else { // per week
                var weekStartDate = $('#weekStartDate').val();
                if (!weekStartDate) {   
                    alert('Please select a Week Start Date.');
                    return false;
                }

                var url = '<?= base_url('lending/report/exportCollectionPerOfficerPerWeekToExcel') ?>';
                $.post(url, 
                    { 
                        account_officer: accountOfficer, 
                        collection_date: collectionDate, 
                        loan_cycle: loanCycle, 
                        account_officer_name: $('#accountOfficer option:selected').text()
                    }, 
                    function(response) {
                        // Handle the response
                        var resp = JSON.parse(response);
                        alert('Report has been exported successfully. Complete path: '+resp.file);
                    }
                );
            }
        });
    });
</script>
<?=$this->endSection()?>




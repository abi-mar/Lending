<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>

<div class="container mt-4">
    <div class="row">        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>
                        Summary (For selected dates)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">   
                        <div class="col-sm-4">
                            <label for="dateFrom">Date From:</label>
                            <input type="text" class="form-control" id="dateFrom" name="dateFrom">
                        </div>
                        <div class="col-sm-4">
                            <label for="dateTo">Date To:</label>
                            <input type="text" class="form-control" id="dateTo" name="dateTo">
                        </div>
                        <div class="col-sm-4">
                            <label for="btnSearch">&nbsp;</label>
                            <button class="btn btn-primary form-control" id="btnSearch">Search</button>
                        </div>
                    </div>    

                    <div class="row">  
                        <div class="col-sm-12">
                            <table id="summary" class="table table-striped" style="margin-top: 20px;">
                                <thead>
                                    <tr>
                                        <th>Info</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>                        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#dateFrom, #dateTo').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#summary').DataTable({
            "columnDefs": [
                { "width": "70%", "targets": 0 },
                { "width": "30%", "targets": 1 }
            ],
            "paging": true,
            "pageLength": 15,
            "searching": false,
            "info": false
        });

        $('#btnSearch').click(function() {
            var dateFrom = $('#dateFrom').val();
            var dateTo = $('#dateTo').val();

            if (new Date(dateFrom) > new Date(dateTo)) {
                alert('Date From cannot be greater than Date To');
                return;
            }

            $.ajax({
            url: '<?= base_url('lending/report/generate');?>', // Replace with your endpoint URL
            type: 'GET',
            data: {
                dateFrom: dateFrom,
                dateTo: dateTo
            },
            success: function(response) {
                // Assuming response is an array of objects with 'info' and 'value' properties
                response = JSON.parse(response);

                if (response.info == null) {
                    alert('No data found');
                    return;
                }
                
                var table = $('#summary').DataTable();
                table.clear();                
                var info = response.info;
                for (var key in info) {
                    var formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, function(l){ return l.toUpperCase() });                    
                    table.row.add(['<b>' + formattedKey + ':</b>', info[key]]);
                }

                table.draw();
            },
            error: function(xhr, status, error) {
                alert('Error fetching data: '+ error);
                console.error('Error fetching data:', error);
            }
            });
        });
    });
</script>

<?=$this->endSection()?>            
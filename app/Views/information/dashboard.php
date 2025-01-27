<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1>Summary</h1>
            <p></p>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <?= $current_cycle; ?>
                </div>
                <div class="card-body">
                    <table id="summary" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Info</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($info as $key => $value): ?>
                            <tr><td><strong><?= ucfirst(str_replace('_', ' ', $key)) ?>:</strong></td><td> <?= is_numeric($value) ? number_format($value, 2) : $value ?> </td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
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
</script>
    
<?=$this->endSection()?>            
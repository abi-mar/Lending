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
                    <?php foreach ($info as $key => $value): ?>
                        <tr><td><strong><?= ucfirst(str_replace('_', ' ', $key)) ?>:</strong></td><td> <?= $value ?> </td></tr>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?=$this->endSection()?>            
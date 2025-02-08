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
                    <h4>Groups
                        <a href="<?= base_url('lending/group/create') ?>" class="btn btn-success btn-sm float-end">ADD</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table id="GroupsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Group No.</th>
                                <th>Name</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($groups): ?>
                                <?php foreach($groups as $row) : ?>
                                <tr>
                                    <td><?= $row['groupno']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['date_added']; ?></td>                                    
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
        $('#GroupsTable').DataTable();
    });    
</script>
<?=$this->endSection()?>
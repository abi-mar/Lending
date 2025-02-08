<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>

<?= validation_list_errors() ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h5>Add Customer
                        <a href="<?= base_url('lending/customer'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                    </h5>
                </div>
                <div class="card-body">                    
                    <?= form_open_multipart('lending/customer/add') ?>
                        <div class="form-group mb-2">
                            <label> First Name <span style="color:red">*</span></label>
                            <input type="text" name="firstname" class="form-control" placeholder="Enter first name" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Middle Name </label>
                            <input type="text" name="middlename" class="form-control" placeholder="Enter middle name"/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Last Name <span style="color:red">*</span></label>
                            <input type="text" name="surname" class="form-control" placeholder="Enter last name" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Suffix </label>
                            <input type="text" name="suffix" class="form-control" placeholder="eg. Jr/Sr/IV"/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Address <span style="color:red">*</span></label>
                            <input type="text" name="address" class="form-control" placeholder="Enter Full Address" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Mobile Number <span style="color:red">*</span></label>
                            <input type="text" name="mobileno" class="form-control" placeholder="Enter mobile no." required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Account Officer <span style="color:red">*</span></label>
                            <select name="account_officer" class="form-control" required>
                                <option value="">Select Account Officer</option>
                                <?php foreach($accountOfficers as $officer): 
                                    $name =  $officer['firstname'] . ' ' . $officer['surname'];
                                    ?>
                                    <option value="<?= $officer['row_id']; ?>"><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label> Group </label>
                            <select name="group" class="form-control">
                                <option value="">Select Group</option>
                                <?php foreach($groups as $group): ?>
                                    <option value="<?= $group['groupno']; ?>"><?= $group['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label> Picture <span style="color:red">*</span></label>
                            <input type="file" name="image" class="form-control"/>                                        
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </div>                    
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
    $('form').on('submit', function(e) {
        var imageInput = $('input[name="image"]');
        if (imageInput.get(0).files.length === 0) {
            alert('Please upload an image.');
            e.preventDefault();
        }
    });
</script>

<?=$this->endSection()?>            
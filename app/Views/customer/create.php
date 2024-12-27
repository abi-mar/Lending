<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>

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
                    <form action="<?= base_url('lending/customer/add') ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label> First Name </label>
                            <input type="text" name="firstname" class="form-control" placeholder="Enter first name" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Middle Name </label>
                            <input type="text" name="middlename" class="form-control" placeholder="Enter middle name"/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Last Name </label>
                            <input type="text" name="surname" class="form-control" placeholder="Enter last name" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Suffix </label>
                            <input type="text" name="suffix" class="form-control" placeholder="eg. Jr/Sr/IV"/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Address </label>
                            <input type="text" name="address" class="form-control" placeholder="Enter Full Address" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Mobile Number </label>
                            <input type="text" name="mobileno" class="form-control" placeholder="Enter mobile no." required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Picture </label>
                            <input type="file" name="image" class="form-control"/>                                        
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?=$this->endSection()?>            
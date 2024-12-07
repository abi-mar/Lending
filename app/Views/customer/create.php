<?= $this->include('layouts/inc/header') ?>

<div class="container-fluid">
    <div class="wrapper">
        <?= $this->include('layouts/inc/sidebar') ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">            
            <!--div class="container mt-5">
                <h2>Create Customer</h2>

                <form action="/submit" method="post">
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <input type="text" class="form-control" id="fname" name="fname">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" class="form-control" id="lname" name="lname">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone number:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                        <small class="form-text text-muted">Format: 123-456-7890</small>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>
            </div-->

            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h5>Add Student
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
			
        </main>
    </div>
</div>

<?= $this->include('layouts/inc/footer') ?>
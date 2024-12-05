<?= $this->include('layouts/inc/header') ?>

<div class="container-fluid">
    <div class="wrapper">
        <?= $this->include('layouts/inc/sidebar') ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">            
            <div class="container mt-5">
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
            </div>
			
        </main>
    </div>
</div>

<?= $this->include('layouts/inc/footer') ?>
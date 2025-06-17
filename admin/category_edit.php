<?php
require "./layouts/header.php";
?>



<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Category/</span>Create</h4>
        <div class="d-flex justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="form-group mb-4">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" />
                                <span class="text-danger">Name is required.</span>
                            </div>
                            <input type="hidden" name="form_sub" value="1">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<?php
require "./layouts/footer.php";
?>
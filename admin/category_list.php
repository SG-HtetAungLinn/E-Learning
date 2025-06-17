<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";
require "./layouts/header.php";
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Category/</span>List</h4>
            <div class="">
                <a href="<?= $admin_base_url ?>category_create.php" class="btn btn-primary">Create Category</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Name 1</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>
                                <a href="" class="btn btn-primary">Edit</a>
                                <a href="" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Name 2</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>
                                <a href="" class="btn btn-primary">Edit</a>
                                <a href="" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Name 3</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>16.5.2025 2:25 PM</td>
                            <td>
                                <a href="" class="btn btn-primary">Edit</a>
                                <a href="" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<?php
require "./layouts/footer.php";
?>
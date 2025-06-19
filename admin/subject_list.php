<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $res = deleteData('subjects', $mysqli, "`id`='$delete_id'");
    if ($res) {
        $url = $admin_base_url . "subject_list.php?success=Subject Delete Success";
        header("Location: $url");
        exit;
    }
}
$success_msg = "";
$error_msg = "";
if (isset($_GET['success'])) {
    $success_msg = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
}
$res = selectData('subjects', $mysqli, $column = "*", $where = "", $order = "ORDER BY id DESC");
require "./layouts/header.php";
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subject/</span>List</h4>
            <div class="">
                <a href="<?= $admin_base_url ?>subject_create.php" class="btn btn-primary">Create Subject</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 offset-md-7 col-12">
                <?php if ($success_msg) { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <?= $success_msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                <?php if ($error_msg) { ?>
                    <div class="alert alert-danger">
                        <?= $error_msg ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= date("Y/F/d h:i:s A", strtotime($row['created_at']))  ?></td>
                                    <td><?= date("Y/m/d h:i:s A", strtotime($row['updated_at']))  ?></td>
                                    <td>
                                        <a href="<?= $admin_base_url . "subject_edit.php?id=" . $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <button type="button" class="btn btn-sm btn-danger delete_btn" data-id="<?= $row['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">This is no data</td>
                            </tr>
                        <?php
                        } ?>

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

<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            const id = $(this).data('id')

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "subject_list.php?delete_id=" + id
                }
            });

        })
    })
</script>
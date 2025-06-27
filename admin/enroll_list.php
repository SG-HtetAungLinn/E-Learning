<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require '../requires/admin_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $res = deleteData('categories', $mysqli, "`id`='$delete_id'");
    if ($res) {
        $url = $admin_base_url . "category_list.php?success=Category Delete Success";
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
$sql = "SELECT 
            course_student.id,
            course_student.status,
            course_student.created_at,
            users.name AS user_name,
            users.email AS user_email,
            users.phone AS user_phone,
            courses.name AS course_name,
            courses.price AS course_price
        FROM `course_student` 
        LEFT JOIN users ON users.id = course_student.user_id
        LEFT JOIN courses ON courses.id = course_student.course_id
            ORDER BY course_student.id DESC
        ";
$res =  $mysqli->query($sql);
require "./layouts/header.php";
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Category/</span>List</h4>
            <div class="">
                <a href="<?= $admin_base_url ?>category_create.php" class="btn btn-primary">Create Category</a>
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
                            <th>No.</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course Name</th>
                            <th>Price</th>
                            <th>Enroll Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['user_name'] ?></td>
                                    <td><?= $row['user_email'] ?></td>
                                    <td><?= $row['user_phone'] ?></td>
                                    <td><?= $row['course_name'] ?></td>
                                    <td><?= $row['course_price'] ?></td>
                                    <td><?= date("Y/F/d", strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" value="<?= $row['status'] ?>" role="switch" id="status" data-id="<?= $row['id'] ?>" <?= $row['status'] ? 'checked' : '' ?> />
                                        </div>
                                    </td>
                                </tr>
                        <?php }
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
        $('#status').click(function() {
            const id = $(this).data('id')
            const status = $(this).val()
            Swal.fire({
                title: "Are you sure?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'enroll.php',
                        type: 'POST',
                        data: {
                            id,
                            status
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.message,
                                    icon: "success"
                                });
                                $('#status').val(res.status)
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: res.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(res, a, b) {
                            Swal.fire({
                                title: "Error!",
                                text: b,
                                icon: "error"
                            });
                        }
                    })
                }
            });

        })
    })
</script>
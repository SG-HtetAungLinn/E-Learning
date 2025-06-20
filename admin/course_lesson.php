<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";

$error = false;
$name =
    $name_error =
    $link =
    $link_error =
    $success_msg =
    $error_msg = "";
if (isset($_GET['success'])) {
    $success_msg = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    $url = $admin_base_url . "course_list.php?error=Course ID Not Found.";
    header("Location: $url");
    exit;
}
// select Lesson
$lesson_res = selectData('lessons', $mysqli, "*", "WHERE `course_id`='$id'", "ORDER BY id DESC");
if (isset($_POST['form_sub']) && $_POST['form_sub'] == 1) {
    $name = $mysqli->real_escape_string($_POST['name']);
    $link = $mysqli->real_escape_string($_POST['link']);
    // Link
    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_eror = "Please Fill Lesson Name.";
    } else if (strlen($name) < 3) {
        $error = true;
        $name_eror = "Course Name must be greather then 3.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_eror = "Course Name must be less then 100.";
    }
    // Link
    if ($link === '' || strlen($link) === 0) {
        $error = true;
        $link_eror = "Please Fill Lesson Link.";
    }
    if (!$error) {
        $url_id = explode('v=', $link);
        $url_id = explode("&", end($url_id));
        $data = [
            'course_id'         => $id,
            'name'              => $name,
            'link'              => $url_id[0]
        ];
        if (insertData('lessons', $mysqli, $data)) {
            $insert_id = $mysqli->insert_id;
            $url = $admin_base_url . "course_lesson.php?id=$id&success=Course Lesson Create Success";
            header("Location: $url");
            exit;
        }
    }
}
require "./layouts/header.php";
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Course/Lesson/</span>Create</h4>
            <div class="">
                <a href="<?= $admin_base_url . "course_list.php" ?>" class="btn btn-dark">Back</a>
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
        <div class="d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="<?= $admin_base_url . "course_lesson.php?id=" . $id ?>" method="POST">
                                            <div class="form-group mb-3">
                                                <label for="name">Lesson Name</label>
                                                <input type="text" name="name" id="name" class="form-control">
                                                <?php if ($error && $name_eror) { ?>
                                                    <span class="text-danger"><?= $name_eror ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="link">URL</label>
                                                <input type="text" name="link" id="link" class="form-control">
                                                <?php if ($error && $link_eror) { ?>
                                                    <span class="text-danger"><?= $link_eror ?></span>
                                                <?php } ?>
                                            </div>
                                            <input type="hidden" name="form_sub" value="1">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php if ($lesson_res->num_rows > 0) {
                                while ($row = $lesson_res->fetch_assoc()) { ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="ratio ratio-16x9">
                                                    <iframe src="<?= "https://www.youtube.com/embed/" . $row['link'] ?>" title="Video 1" allowfullscreen></iframe>
                                                </div>
                                                <hr />
                                                <h4>
                                                    <?= $row['name'] ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>

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
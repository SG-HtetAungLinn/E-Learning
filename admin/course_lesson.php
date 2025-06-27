<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require '../requires/admin_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";

$error = false;
$name =
    $name_error =
    $link =
    $link_error =
    $subject =
    $subject_error =
    $description =
    $description_error =
    $success_msg =
    $error_msg = "";
if (isset($_GET['success'])) {
    $success_msg = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$subject_id = $subject_id === '' ? '' : $subject_id;
if (!$id) {
    $url = $admin_base_url . "course_list.php?error=Course ID Not Found.";
    header("Location: $url");
    exit;
}

// select Lesson
// $lesson_res = selectData('lessons', $mysqli, "*", "WHERE `course_subject_id`='$id'", "ORDER BY id DESC");
$sql = "SELECT 
            lessons.id AS lesson_id,
            lessons.name AS lesson_name,
            lessons.description AS lesson_description,
            lessons.link,
            courses.name AS course_name,
            subjects.name AS subject_name
        FROM lessons
        JOIN course_subject ON lessons.course_subject_id = course_subject.id
        JOIN courses ON course_subject.course_id = courses.id
        JOIN subjects ON course_subject.subject_id = subjects.id
        WHERE course_subject.course_id = '$id' ";
if ($subject_id !== '') {
    $sql .= " AND course_subject.subject_id = '$subject_id'";
}
if ($search !== '') {
    $sql .= " AND lessons.name LIKE '%$search%'";
}
$sql .= " ORDER BY lessons.id DESC";
$lesson_res = $mysqli->query($sql);

$subject_sql  = "SELECT course_subject.*, subjects.name AS subject_name
                FROM `course_subject` 
                LEFT JOIN subjects ON subjects.id = course_subject.subject_id
                WHERE course_subject.course_id='$id'";
$subject_res = $mysqli->query($subject_sql);
$select_subject_res = $mysqli->query($subject_sql);

if (isset($_POST['form_sub']) && $_POST['form_sub'] == 1) {
    $subject = $mysqli->real_escape_string($_POST['subject']);
    $name = $mysqli->real_escape_string($_POST['name']);
    $link = $mysqli->real_escape_string($_POST['link']);
    $description = $mysqli->real_escape_string($_POST['description']);
    // Subject
    if ($subject === '' || strlen($subject) === 0) {
        $error = true;
        $subject_error = "Please Choose Subject";
    }
    // Name
    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_error = "Please Fill Lesson Name.";
    } else if (strlen($name) < 3) {
        $error = true;
        $name_error = "Course Name must be greather then 3.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_error = "Course Name must be less then 100.";
    }
    // Link
    if ($link === '' || strlen($link) === 0) {
        $error = true;
        $link_error = "Please Fill Lesson Link.";
    }
    // Description
    if ($description !== '' && strlen($description) < 3) {
        $error = true;
        $description_error = "Description must be greather then 3.";
    } else if ($description !== '' && strlen($description) > 255) {
        $error = true;
        $description_error = "Description must be less then 255.";
    }
    if (!$error) {
        $url_id = explode('v=', $link);
        $url_id = explode("&", end($url_id));
        $data = [
            'course_subject_id'     => $subject,
            'name'                  => $name,
            'link'                  => $url_id[0],
            'description'           => $description
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
        <div class="row">
            <div class="col-md-12 mb-3 d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="<?= $admin_base_url . "course_lesson.php?id=" . $id ?>" method="POST">
                                <div class="form-group mb-3">
                                    <label for="subject">Subject</label>
                                    <select name="subject" id="subject" class="form-select">
                                        <option value="">Please Choose Subject</option>
                                        <?php if ($subject_res->num_rows > 0) {
                                            while ($row = $subject_res->fetch_assoc()) { ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['subject_name'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php if ($error && $subject_error) { ?>
                                        <span class="text-danger"><?= $subject_error ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="name">Lesson Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                    <?php if ($error && $name_error) { ?>
                                        <span class="text-danger"><?= $name_error ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="link">URL</label>
                                    <input type="text" name="link" id="link" class="form-control">
                                    <?php if ($error && $link_error) { ?>
                                        <span class="text-danger"><?= $link_error ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control"><?= $description ?></textarea>
                                    <?php if ($error && $description_error) { ?>
                                        <span class="text-danger"><?= $description_error ?></span>
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
            </div>
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-lg-4 col-md-6 col-12">
                            <select name="select_subject" class="form-select" id="select_subject">
                                <option value="">All Subject</option>
                                <?php
                                if ($select_subject_res->num_rows > 0) {
                                    while ($row = $select_subject_res->fetch_assoc()) { ?>
                                        <option value="<?= $row['subject_id'] ?>" <?= $subject_id === $row['subject_id'] ? 'selected' : '' ?>><?= $row['subject_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <input type="hidden" class="course_id" value="<?= $id ?>">
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <form action="" method="get">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search" value="<?= $search ?>">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php if ($lesson_res->num_rows > 0) {
                                while ($row = $lesson_res->fetch_assoc()) { ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="ratio ratio-16x9 mb-3">
                                                    <iframe src="<?= "https://www.youtube.com/embed/" . $row['link'] ?>" title="Video 1" allowfullscreen></iframe>
                                                </div>
                                                <small><?= $row['subject_name'] ?></small>
                                                <h4>
                                                    <?= $row['lesson_name'] ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } else { ?>
                                <h2 class="m-0 text-center text-danger">This is no lesson</h2>
                            <?php } ?>
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
<script>
    $(document).ready(function() {
        $('#select_subject').change(function() {
            const course_id = $('.course_id').val()
            const subject_id = $(this).val()
            window.location.href =
                `course_lesson.php?id=${course_id}&subject_id=${subject_id}`
        })
    })
</script>
<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require '../requires/admin_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";

$error = false;
$name =
    $name_eror =
    $category =
    $category_error =
    $description =
    $description_error =
    $duration =
    $duration_error =
    $price =
    $price_error =
    $subject_error = '';
$subject = [];
$category_res = selectData('categories', $mysqli);
$subject_res = selectData('subjects', $mysqli);
if (isset($_POST['form_sub']) && $_POST['form_sub'] == 1) {
    $name = $mysqli->real_escape_string($_POST['name']);
    $category = $mysqli->real_escape_string($_POST['category']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $duration = $mysqli->real_escape_string($_POST['duration']);
    $price = $mysqli->real_escape_string($_POST['price']);
    $subject = isset($_POST['subject']) ? $_POST['subject'] : [];

    // Name
    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_eror = "Please Fill Course Name.";
    } else if (strlen($name) < 3) {
        $error = true;
        $name_eror = "Course Name must be greather then 3.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_eror = "Course Name must be less then 100.";
    }
    // Category
    if ($category === '' || strlen($category) === 0) {
        $error = true;
        $category_error = "Please choose category";
    }
    // Description
    if ($description === '' || strlen($description) === 0) {
        $error = true;
        $description_error = "Please Fill Course Description.";
    } else if (strlen($description) < 3) {
        $error = true;
        $description_error = "Course Description must be greather then 3.";
    } else if (strlen($description) > 1000) {
        $error = true;
        $description_error = "Course Description must be less then 1000.";
    }
    // Duration
    if ($duration === '' || strlen($duration) === 0) {
        $error = true;
        $duration_error = "Please Fill Course Duration.";
    } else if (strlen($duration) < 3) {
        $error = true;
        $duration_error = "Course Duration must be greather then 3.";
    } else if (strlen($duration) > 50) {
        $error = true;
        $duration_error = "Course Duration must be less then 50.";
    }
    // Price
    if ($price === '' || strlen($price) === 0) {
        $error = true;
        $price_error = "Please Fill Course Price.";
    } else if (!is_numeric($price)) {
        $error = true;
        $price_error = "Course Price must be number.";
    } else if (strlen($price) < 3) {
        $error = true;
        $price_error = "Course Price must be greather then 3.";
    } else if (strlen($price) > 10) {
        $error = true;
        $price_error = "Course Price must be less then 10.";
    }
    // Subject
    if (empty($subject)) {
        $error = true;
        $subject_error = "Please Choose Subject.";
    }
    if (!$error) {
        $data = [
            'name'          => $name,
            'category_id'   => $category,
            'description'   => $description,
            'duration'      => $duration,
            'price'         => $price
        ];
        if (insertData('courses', $mysqli, $data)) {
            $insert_id = $mysqli->insert_id;
            foreach ($subject as $sub) {
                $subject_data = [
                    'course_id' => $insert_id,
                    'subject_id' => $sub
                ];
                $res = insertData('course_subject', $mysqli, $subject_data);
            }
            if ($res) {
                $url = $admin_base_url . "course_list.php?success=Course Create Success";
                header("Location: $url");
                exit;
            }
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Course/</span>Create</h4>
            <div class="">
                <a href="<?= $admin_base_url . "course_list.php" ?>" class="btn btn-dark">Back</a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= $admin_base_url ?>course_create.php" method="POST">
                            <div class="form-group mb-4">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Course Name" value="<?= $name ?>" />
                                <?php if ($error && $name_eror) { ?>
                                    <span class="text-danger"><?= $name_eror ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-4">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Please choose Category</option>
                                    <?php
                                    if ($category_res->num_rows > 0) {
                                        while ($row = $category_res->fetch_assoc()) {
                                    ?>
                                            <option value="<?= $row['id'] ?>" <?= $row['id'] === $category ? "selected" : "" ?>><?= $row['name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <?php if ($error && $category_error) { ?>
                                    <span class="text-danger"><?= $category_error ?></span>
                                <?php } ?>
                            </div>

                            <div class="form-group mb-4">
                                <label for="description">Subject</label>
                                <div class="row">
                                    <?php if ($subject_res->num_rows > 0) {
                                        while ($row = $subject_res->fetch_assoc()) { ?>
                                            <div class="col-md-4 col-sm-6">
                                                <label>
                                                    <?= $row['name'] ?>
                                                    <input type="checkbox" name="subject[]" <?= in_array($row['id'], $subject) ? "checked" : "" ?> class="form-check-input" value="<?= $row['id'] ?>">
                                                </label>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                                <?php if ($error && $subject_error) { ?>
                                    <span class="text-danger"><?= $subject_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-4">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" placeholder="Enter Course Description"><?= $description ?></textarea>
                                <?php if ($error && $description_error) { ?>
                                    <span class="text-danger"><?= $description_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-4">
                                <label for="duration">Duration</label>
                                <input type="text" name="duration" id="duration" placeholder="Enter Course Duration" class="form-control" value="<?= $duration ?>" />
                                <?php if ($error && $duration_error) { ?>
                                    <span class="text-danger"><?= $duration_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-4">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" placeholder="Enter Course Price" class="form-control" value="<?= $price ?>" />
                                <?php if ($error && $price_error) { ?>
                                    <span class="text-danger"><?= $price_error ?></span>
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

    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<?php
require "./layouts/footer.php";
?>
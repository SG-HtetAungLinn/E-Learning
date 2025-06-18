<?php
require '../requires/common.php';
require '../requires/check_auth.php';
require "../requires/common_function.php";
require "../requires/db.php";
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    $url = $admin_base_url . "category_list.php?error=ID not found.";
    header("Location: $url");
    exit;
}
$error = false;
$name =
    $name_eror = '';
$res = selectData('categories', $mysqli, "name", "WHERE `id`='$id'");
if ($res->num_rows === 0) {
    $url = $admin_base_url . "category_list.php?error=ID not found.";
    header("Location: $url");
    exit;
}
$data = $res->fetch_assoc();
$name = $data['name'];
if (isset($_POST['form_sub']) && $_POST['form_sub'] == 1) {
    $name = $mysqli->real_escape_string($_POST['name']);
    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_eror = "Please Fill Category Name.";
    } else if (strlen($name) < 3) {
        $error = true;
        $name_eror = "Category Name must be greather then 3.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_eror = "Category Name must be less then 100.";
    }
    if (!$error) {
        $data = [
            'name' => $name
        ];
        $data = [
            'name' => $name,
        ];
        $where = [
            'id' => $id,
        ];
        $res = updateData('categories', $mysqli, $data, $where);
        if ($res) {
            $url = $admin_base_url . "category_list.php?success=Category Update Success";
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Category/</span>Edit</h4>
            <div class="">
                <a href="<?= $admin_base_url . "category_list.php" ?>" class="btn btn-dark">Back</a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= $admin_base_url . "category_edit.php?id=" . $id ?>" method="POST">
                            <div class="form-group mb-4">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?= $name ?>" />
                                <?php if ($error && $name_eror) { ?>
                                    <span class="text-danger"><?= $name_eror ?></span>
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
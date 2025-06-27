<?php
require './requires/common.php';
require "./requires/check_auth.php";
require "./requires/user_auth.php";
require "./requires/common_function.php";
require "./requires/db.php";
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    header("Location: $base_url");
    exit;
}
$sql  = "SELECT 
            courses.*,
            categories.name AS category_name
        FROM `courses`
        LEFT JOIN `categories` ON categories.id = courses.category_id
        WHERE courses.id = '$id'";
$course_res =  $mysqli->query($sql);
if ($course_res->num_rows > 0) {
    $courseData =  $course_res->fetch_assoc();
}
$subject_sql = "SELECT subjects.name
                FROM `course_subject` 
                LEFT JOIN subjects ON subjects.id = course_subject.subject_id
                WHERE course_subject.course_id = '$id'";
$subject_res = $mysqli->query($subject_sql);
$user_id = $_SESSION['id'];

$is_enroll_sql = "SELECT * 
                    FROM `course_student`
                    WHERE user_id=$user_id 
                        AND course_id=$id";
$is_enroll_res = $mysqli->query($is_enroll_sql);
$is_enroll = false;
if ($is_enroll_res->num_rows > 0) {
    $enroll_data = $is_enroll_res->fetch_assoc();
    $is_enroll = true;
}
require "./layouts/header.php";
?>
<section class="bg-secondary py-5" style="min-height: 100vh;">
    <h6 class="text-center mb-1 text-warning"><?= $courseData['category_name'] ?></h6>
    <h1 class="text-center mb-3 text-light"><?= $courseData['name'] ?></h1>
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">

                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-dark">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fa-solid fa-calendar-days text-warning fs-4 me-3"></i>
                            <span><?= $courseData['duration'] ?></span>
                        </div>
                        <div class="mb-3">
                            <i class="fa-regular fa-credit-card text-warning fs-4 me-3"></i>
                            <span><?= $courseData['price'] ?> MMK</span>
                        </div>
                        <div class="mb-3 d-flex gap-2 flex-wrap">
                            <?php if ($subject_res->num_rows > 0) {
                                while ($row = $subject_res->fetch_assoc()) { ?>
                                    <div class="btn btn-light"><?= $row['name'] ?></div>
                            <?php }
                            } ?>

                        </div>
                        <div class="mb-3">
                            Student
                            <strong class="text-primary">0</strong>
                        </div>
                        <div class="mb-3">
                            Course Details :
                            <p><?= $courseData['description'] ?></p>
                        </div>
                        <?php if (!$is_enroll) { ?>
                            <div class="">
                                <button class="btn btn-warning w-100 enroll_btn">Enroll</button>
                            </div>
                        <?php } elseif ($is_enroll && !$enroll_data['status']) { ?>
                            <div class="">
                                <button class="btn btn-warning w-100" disabled>Waiting...</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($is_enroll && $enroll_data['status']) { ?>
                <div class="col-12 row">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Accordion Item #1
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            a
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php } ?>
        </div>
    </div>
    <input type="hidden" class="course_id" value="<?= $id ?>">
    <input type="hidden" class="user_id" value="<?= $user_id ?>">
</section>

<?php
require "./layouts/footer.php";
?>
<script>
    $(document).ready(function() {
        getLesson()

        function getLesson() {
            $.ajax({
                url: "get_lesson.php",
                method: "POST",
                data: {
                    id: $('.course_id').val()
                },
                success: function(res) {
                    $('.accordion').html('');
                    res.forEach((item, index) => {
                        let html = `<div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${item.subject_id}" aria-expanded="true" aria-controls="collapse${item.subject_id}">
                                            ${item.subject_name}
                                        </button>
                                    </h2>
                                    <div id="collapse${item.subject_id}" class="accordion-collapse collapse " data-bs-parent="#accordionExample">
                                        ${lessons(item.lessons)}
                                    </div>
                                </div>`;
                        $('.accordion').append(html);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            })
        }

        function lessons(lesson) {
            let html = '';
            lesson.forEach(item => {
                html += `<div class="col-12 my-1">
                               <div class="card">
                                   <div class="card-body row">
                                        <div class="col-8">
                                            <div class="ratio ratio-16x9 mb-3">
                                                    <iframe src="https://www.youtube.com/embed/${item.link}" title="Video 1" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <p>${item.lesson_name}</p>
                                            <p>${item.lesson_description}</p>
                                        </div>
                                        
                                   </div>
                               </div>
                           </div>`;
            })
            return html;
        }
        $('.enroll_btn').click(function() {
            Swal.fire({
                title: "Are you sure enroll?",
                text: "You won't be able to revert this!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "enroll.php",
                        type: "POST",
                        data: {
                            'user_id': $('.user_id').val(),
                            'course_id': $('.course_id').val(),
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.message,
                                    icon: "success"
                                }).then(function() {
                                    location.reload();
                                });

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
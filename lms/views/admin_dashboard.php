<?php
// lms/views/admin_dashboard.php

// --- NO PHP CODE WAS CHANGED HERE ---

require "session.php"; // Must call session_start()
require_once __DIR__ . '/../controller/LessonController.php';
require_once "../../Admin/DB Operations/CoursesOps.php";

// Check authentication
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LessonController::addLesson(
        $_POST['title'],
        $_POST['description'],
        $_POST['video_url'],
        $_SESSION['user'],
        category: $_POST['category'] ?? '',
        courseId: $_POST['coursesopted'] ?? 0
    );

    // Set success message and redirect
    $_SESSION['success_message'] = 'Lesson added successfully!';
    header('Location: admin_dashboard.php');
    exit(); // This will now work because no HTML has been sent
}

// Handle displaying the success message (for the GET request after redirect)
$successMsg = '';
if (isset($_SESSION['success_message'])) {
    $successMsg = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Fetch lessons for display and count
$lessons = LessonController::getAllLessons();


// --- NOW, START SENDING HTML OUTPUT ---

// Include the header *after* all logic is done
require_once "header.php";

?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="icon" type="image/x-icon" href="/img/favicon.png">
<style>
    :root {
        --primary-color: #6a5af9;
        --primary-hover: #5a4af7;
        --secondary-color: #7b86a2;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --body-bg: #eef1f5;
        --card-bg: rgba(255, 255, 255, 0.6);
        --card-border-color: rgba(255, 255, 255, 0.4);
        --border-radius-lg: 1rem;
        --border-radius-md: 0.5rem;
        --shadow-light: 0 4px 10px rgba(0, 0, 0, 0.05);
        --shadow-medium: 0 8px 30px rgba(0,0,0,0.1);
        --font-family: 'Be Vietnam Pro', sans-serif;
    }

    body {
        background-color: var(--body-bg);
        background-image:
            radial-gradient(circle at 10% 20%, rgb(222, 222, 255) 0%, rgba(222, 222, 255, 0) 50%),
            radial-gradient(circle at 80% 90%, rgb(250, 215, 225) 0%, rgba(250, 215, 225, 0) 40%);
        font-family: var(--font-family);
        color: var(--dark-color);
        min-height: 100vh;
    }

    .dashboard-container {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .dashboard-card {
        background: var(--card-bg);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-medium);
        padding: 2.5rem;
        border: 1px solid var(--card-border-color);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .dashboard-header h2 {
        font-weight: 700;
        font-size: 2.25rem;
        background: linear-gradient(90deg, #6a5af9, #d66bff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .logout-link {
        font-weight: 600;
        color: var(--danger-color);
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        border: 1px solid transparent;
    }

    .logout-link:hover {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.2);
        color: #b02a37;
    }

    /* Accordion Styles */
    .admin-accordion {
        --bs-accordion-border-width: 0;
        --bs-accordion-border-radius: var(--border-radius-md);
        --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(106, 90, 249, 0.2);
        --bs-accordion-active-bg: var(--primary-color);
        --bs-accordion-active-color: #fff;
    }
    .admin-accordion .accordion-item {
        background-color: transparent;
        border: 1px solid var(--card-border-color);
        border-radius: var(--border-radius-md) !important;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-light);
    }
    .admin-accordion .accordion-button {
        background-color: #fff;
        color: var(--dark-color);
        font-size: 1.25rem;
        font-weight: 600;
        padding: 1.25rem 1.75rem;
    }
    .admin-accordion .accordion-button:not(.collapsed) {
        box-shadow: inset 0 -3px 0 var(--primary-hover);
    }
    .admin-accordion .accordion-body {
        padding: 1.5rem 2rem 2rem;
        background-color: #fff;
    }
    .count-badge {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        background-color: #e9ecef;
        color: var(--secondary-color);
    }
    .accordion-button:not(.collapsed) .count-badge {
        background-color: rgba(255,255,255,0.2);
        color: #fff;
    }
    
    /* Form Styles */
    .form-label { font-weight: 600; color: #495057; }
    .form-control, .form-select {
        border-radius: var(--border-radius-md);
        border: 1px solid #ced4da;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(106, 90, 249, 0.2);
        background-color: #fff;
    }
    .btn-primary {
        background: linear-gradient(90deg, var(--primary-color), #8e44ad);
        border: none;
        font-weight: 600;
        padding: 0.85rem 1.5rem;
        border-radius: var(--border-radius-md);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(106, 90, 249, 0.3);
        color: #fff;
    }
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(106, 90, 249, 0.4);
    }

    /* === NEW PLAYLIST STYLES === */
    .lesson-playlist-container {
        background: #fff;
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-light);
        overflow: hidden;
    }
    .lesson-playlist {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 450px;
        overflow-y: auto;
    }

    .playlist-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.3s ease;
    }
    .playlist-item:last-child {
        border-bottom: none;
    }
    .playlist-item:hover {
        background-color: #f8f7ff;
    }

    .playlist-item .play-button {
        font-size: 1.25rem;
        color: var(--primary-color);
        margin-right: 1.25rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .playlist-item .play-button:hover {
        color: var(--primary-hover);
    }

    .lesson-details {
        flex-grow: 1;
    }

    .lesson-details .title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.1rem;
    }

    .lesson-details .course-badge {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.2em 0.6em;
        border-radius: var(--border-radius-md);
        background-color: #e9ecef;
        color: #495057;
    }

    .lesson-actions {
        display: flex;
        gap: 0.5rem;
    }
    .lesson-actions a {
        color: var(--secondary-color);
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .lesson-actions a:hover {
       background-color: rgba(108, 117, 125, 0.1);
       color: var(--dark-color);
    }
    .lesson-actions a.btn-delete:hover {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }
    
    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: var(--border-radius-md);
    }
    .modal-header {
        background: var(--primary-color);
        color: #fff;
        border-bottom: none;
    }
    #videoModalLabel {
        font-weight: 600;
    }
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

</style>

<div class="container dashboard-container">
    <div class="dashboard-card animate__animated animate__fadeInUp">

        <div class="dashboard-header">
            <h2>Admin Panel</h2>
            <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <p class="lead mb-4" style="border-bottom: 1px solid #dee2e6; padding-bottom: 1.5rem;">Welcome back, <strong style="color:var(--primary-color);"><?php echo htmlspecialchars($_SESSION['user']); ?></strong>! Manage your content efficiently.</p>

        <?php if ($successMsg): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $successMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="accordion admin-accordion" id="adminAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-plus-circle me-3"></i> Add a New Lesson
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <form method="post" enctype="multipart/form-data" class="row g-4" novalidate>
                            <div class="col-md-6">
                                <label for="title" class="form-label">Lesson Title</label>
                                <input type="text" id="title" name="title" class="form-control" required placeholder="e.g., Introduction to CSS Flexbox">
                            </div>
                            <div class="col-md-6">
                                <label for="coursesopted" class="form-label">Course</label>
                                <select class="form-select" id="coursesopted" name="coursesopted" required>
                                    <option value="" disabled selected>Select a course</option>
                                    <?php
                                    $courselist = DBcourse::selectall();
                                    foreach ($courselist as $course) {
                                        echo "<option value='" . $course->get_id() . "'>" . htmlspecialchars($course->get_cname()) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="A brief summary of what this lesson covers..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                     <option value="" disabled selected>Select Category</option>
                                     <option value="HTML">HTML</option>
                                     <option value="CSS">CSS</option>
                                     <option value="JavaScript">JavaScript</option>
                                     <option value="ReactJS">ReactJS</option>
                                     <option value="NodeJS">NodeJS</option>
                                     <option value="PHP">PHP</option>
                                     <option value="Python">Python</option>
                                     <option value="Java">Java</option>
                                     <option value="C++">C++</option>
                                     <option value="C#">C#</option>
                                     <option value="C">C</option>
                                     <option value="AdvancedExcel">Advanced Excel</option>
                                     <option value="SQL">SQL</option>
                                     <option value="PowerBI">Power BI</option>
                                     <option value="Setup">Setup</option>
                                     <option value="Canva">Canva</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="video_url" class="form-label">Video URL</label>
                                <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-primary w-50"><i class="fas fa-plus-circle me-2"></i>Add Lesson</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-tasks me-3"></i> Manage Existing Lessons
                        <span class="ms-auto count-badge"><?php echo count($lessons); ?> Lessons</span>
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <?php if (empty($lessons)): ?>
                            <div class="text-center p-5 bg-light rounded-3">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <p class="mb-0 h5">No lessons found.</p>
                                <p class="text-muted">Use the form above to add your first lesson.</p>
                            </div>
                        <?php else: ?>
                            <div class="lesson-playlist-container">
                                <ul class="lesson-playlist">
                                    <?php foreach ($lessons as $lesson): ?>
                                        <li class="playlist-item">
                                            <i class="fas fa-play-circle play-button" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#videoModal"
                                               data-video-url="<?php echo htmlspecialchars($lesson['video_url']); ?>"
                                               data-lesson-title="<?php echo htmlspecialchars($lesson['title']); ?>"
                                               title="Play Video"></i>
                                            
                                            <div class="lesson-details">
                                                <div class="title"><?php echo htmlspecialchars($lesson['title']); ?></div>
                                                <?php if (!empty($lesson['course_name'])): ?>
                                                    <span class="badge course-badge"><?php echo htmlspecialchars($lesson['course_name']); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="lesson-actions">
                                                <a href="edit_lesson.php?id=<?php echo $lesson['id']; ?>" class="btn-edit" title="Edit Lesson"><i class="fas fa-pencil-alt"></i></a>
                                                <a href="delete_lesson.php?id=<?php echo $lesson['id']; ?>" class="btn-delete" title="Delete Lesson" onclick="return confirm('Are you sure you want to delete this lesson?');"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalLabel">Lesson Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
            <iframe id="lessonVideoIframe" src="" title="Lesson Video Player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        const iframe = document.getElementById('lessonVideoIframe');
        const modalTitle = document.getElementById('videoModalLabel');

        // Event listener for when the modal is about to be shown
        videoModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            const button = event.relatedTarget; 

            // Extract info from data-* attributes
            const videoUrl = button.getAttribute('data-video-url');
            const lessonTitle = button.getAttribute('data-lesson-title');

            // Function to convert YouTube URL to embed URL
            function getEmbedUrl(url) {
    if (!url) return "";
    let videoId = '';
    try {
        const ytRegex = /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(ytRegex);
        if (match && match[1]) {
            videoId = match[1];
        }
    } catch (e) {
        console.error("Invalid URL for parsing:", url, e);
        return "";
    }
    return videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1` : '';
}

videoModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget; 
    const videoUrl = button.getAttribute('data-video-url') || '';
    const lessonTitle = button.getAttribute('data-lesson-title') || 'Lesson Video';

    const embedUrl = getEmbedUrl(videoUrl);
    
    if (embedUrl) {
        modalTitle.textContent = lessonTitle;
        iframe.src = embedUrl;
    } else {
        modalTitle.textContent = "Invalid or Missing Video URL";
        iframe.src = "";
        videoModal.querySelector('.modal-body').innerHTML = `<div class="p-4 text-center text-danger fw-bold">No valid YouTube link provided for this lesson.</div>`;
    }
});

            
            // Update the modal's content
            if (embedUrl) {
                modalTitle.textContent = lessonTitle;
                iframe.src = embedUrl;
            } else {
                modalTitle.textContent = "Error";
                iframe.src = "";
                // Optionally, you can display an error message in the modal body here
            }
        });

        // Event listener for when the modal is hidden
        videoModal.addEventListener('hidden.bs.modal', function() {
            // Stop the video from playing in the background by clearing the src
            iframe.setAttribute('src', '');
            modalTitle.textContent = 'Lesson Video';
        });
    }
});
</script>

<?php require_once("footer.php") ?>
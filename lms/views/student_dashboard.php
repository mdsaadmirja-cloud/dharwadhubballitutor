<?php
// lms/views/student_dashboard.php
require_once 'session.php';
session_start();
require_once '../controller/LessonController.php';
require_once 'student_header.php';

$userEmail = $_SESSION['email'] ?? 'default_user@example.com';
$userArr = $_SESSION['user'] ?? null;
$userId = is_array($userArr) ? ($userArr['id'] ?? null) : $userArr;
if($_SESSION['role']=='student'){
    $courses = LessonController::getLessonByUserEmailId($userEmail);
}else if($_SESSION['role']=='admin'){
    $courses=LessonController::getAllLessons();
}

$coursesByCategory = [];
if (!empty($courses)) {
    foreach ($courses as $course) {
        $category = $course['category'] ?? 'General Courses';
        if (!isset($coursesByCategory[$category])) {
            $coursesByCategory[$category] = [];
        }
        $coursesByCategory[$category][] = $course;
    }
}

// Helper function to extract YouTube Video ID from various URL formats
function getYouTubeVideoId($url) {
    preg_match('/(v=|\/v\/|youtu\.be\/|embed\/|\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
    return $matches[2] ?? '';
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="icon" type="image/x-icon" href="/img/favicon.png">

<div class="student-dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">My Learning Journey</h1>
        <p class="dashboard-subtitle">Select a category to view your enrolled courses.</p>
    </div>

    <?php if (empty($coursesByCategory)): ?>
    <div class="col-12">
        <div class="alert alert-info text-center shadow-sm p-4 rounded-4">
            <i class="fas fa-info-circle me-2"></i>
            You are not yet enrolled in any courses. Explore our catalog to get started!
        </div>
    </div>
<?php else: ?>
    <div class="accordion" id="courseAccordion">
        <?php 
        foreach ($coursesByCategory as $category => $categoryCourses):
            $categoryId = 'category-' . preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($category));
        ?>
            <div class="accordion-item mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                <h2 class="accordion-header" id="heading-<?php echo $categoryId; ?>">
                    <button class="accordion-button fw-bold collapsed" 
                        type="button" 
                        data-target="#collapse-<?php echo $categoryId; ?>" 
                        aria-expanded="false" 
                        aria-controls="collapse-<?php echo $categoryId; ?>">


                        <i class="fas fa-layer-group me-2"></i>
                        <?php echo htmlspecialchars($category); ?>
                        <span class="ms-auto d-flex align-items-center">
                            <span class="badge rounded-pill bg-gradient-primary px-3">
                                <?php echo count($categoryCourses); ?>
                            </span>
                            <i class="ms-3 fas fa-chevron-right arrow-icon"></i>
                        </span>
                    </button>
                </h2>
                <div id="collapse-<?php echo $categoryId; ?>" 
                     class="accordion-collapse collapse" 
                     aria-labelledby="heading-<?php echo $categoryId; ?>">
                    <div class="accordion-body p-0">
                        <ul class="course-list list-unstyled mb-0">
                            <?php foreach ($categoryCourses as $index => $course): ?>
                                <li class="course-row d-flex align-items-center px-4 py-3">
                                    <div class="me-3">
                                        <?php
                                        $videoId = getYouTubeVideoId($course['video_url'] ?? '');
                                        if ($videoId):
                                            $embedUrl = "https://www.youtube.com/embed/" . $videoId;
                                        ?>
                                            <a href="#" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#videoModal" 
                                               data-video-src="<?php echo htmlspecialchars($embedUrl); ?>" 
                                               data-video-title="<?php echo htmlspecialchars($course['title']); ?>"
                                               class="text-decoration-none course-play-btn">
                                                <i class="fas fa-play-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <i class="fas fa-book text-secondary fs-5"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold"><?php echo htmlspecialchars($course['title']); ?></h6>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalLabel">Course Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ratio ratio-16x9">
            <iframe id="courseVideoIframe" src="" title="Course video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    :root {
        --primary-color: #2b6cb0;
        --secondary-color: #4fd1c5;
        --accent-color: #3182ce;
        --background-color: #f7fafc;
        --card-bg: #ffffff;
        --text-color-primary: #2d3748;
        --text-color-secondary: #718096;
        --border-color: #e2e8f0;
        --shadow-light: rgba(0, 0, 0, 0.05);
        --shadow-medium: rgba(0, 0, 0, 0.1);
        --shadow-bold: rgba(0, 0, 0, 0.15);
        --font-family-primary: 'Poppins', sans-serif;
        --font-family-secondary: 'Roboto', sans-serif;
    }

    body {
        font-family: var(--font-family-secondary);
        background-color: var(--background-color);
        position: relative;
    }

    /* === UTILITIES === */
    .bg-gradient-primary {
        background-image: linear-gradient(45deg, var(--primary-color), var(--accent-color));
    }
    .rounded-5 {
        border-radius: 1.5rem !important;
    }

    /* === DASHBOARD LAYOUT === */
    .student-dashboard-container {
        padding: 2rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* === HEADER === */
    .dashboard-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .dashboard-title {
        font-family: var(--font-family-primary);
        font-weight: 700;
        font-size: 2.8rem;
        color: var(--text-color-primary);
        margin-bottom: 0.5rem;
    }
    .dashboard-subtitle {
        font-size: 1.1rem;
        color: var(--text-color-secondary);
    }

    /* === ACCORDION === */
    .accordion-item {
        border-radius: 1.5rem !important;
        background-color: transparent;
        transition: all 0.3s ease;
    }

    /* Accordion Header */
    .accordion-button {
        background-color: var(--card-bg);
        color: var(--text-color-primary);
        border: 1px solid var(--border-color);
        border-radius: 1.5rem !important;
        font-family: var(--font-family-primary);
        font-size: 1.1rem;
        font-weight: 600;
        padding: 1.2rem 1.8rem;
        box-shadow: 0 5px 15px var(--shadow-light);
        transition: all 0.3s ease;
    }
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 10px 20px var(--shadow-medium);
    }
    .accordion-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px var(--shadow-medium);
    }
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(43, 108, 176, 0.25);
    }

    .accordion-button::after {
        display: none;
    }

    /* Chevron rotation */
    .accordion-button .arrow-icon {
        transition: transform 0.3s ease;
    }
    .accordion-button:not(.collapsed) .arrow-icon {
        transform: rotate(90deg);
        color: #fff;
    }

    /* Badge */
    .badge.bg-gradient-primary {
        background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .accordion-button:not(.collapsed) .badge {
        background: rgba(255, 255, 255, 0.25) !important;
    }
    .badge {
        font-family: var(--font-family-secondary);
    }

    /* === COURSE LIST === */
    .accordion-collapse {
        background-color: var(--card-bg);
        border-bottom-left-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        border: 1px solid var(--border-color);
        border-top: none;
        padding-top: 1rem;
    }

    .course-list {
        padding: 0;
        margin: 0;
    }

    .course-row {
        border-bottom: 1px solid var(--border-color);
        background: transparent;
        transition: all 0.3s ease;
        padding: 1rem 1.5rem;
        cursor: pointer;
    }
    .course-row:last-child {
        border-bottom: none;
    }
    .course-row:hover {
        background: #f0f4f8;
        transform: translateX(5px);
        box-shadow: inset 4px 0 0 var(--accent-color);
    }

    /* Play button */
    .course-play-btn i {
        font-size: 2rem;
        color: var(--primary-color);
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }
    .course-play-btn:hover i {
        color: var(--accent-color);
        text-shadow: 0 0 10px rgba(49, 130, 206, 0.4);
        transform: scale(1.1);
        animation: none;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Book icon fallback */
    .fa-book-open {
        color: var(--text-color-secondary);
        font-size: 1.7rem !important;
    }

    /* Course text */
    .course-row h6 {
        color: var(--text-color-primary);
        font-weight: 600;
        margin: 0;
        font-family: var(--font-family-primary);
    }

    /* === VIDEO MODAL === */
    .modal-content {
        border-radius: 1.5rem;
        box-shadow: 0 15px 45px var(--shadow-bold);
        background-color: var(--card-bg);
    }
    .modal-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem 2rem;
    }
    .modal-title {
        font-family: var(--font-family-primary);
        font-size: 1.25rem;
        color: var(--primary-color);
        font-weight: 600;
    }
    .modal-header .btn-close {
        filter: invert(0.4) sepia(1) saturate(5) hue-rotate(180deg);
        transition: transform 0.2s ease;
    }
    .modal-header .btn-close:hover {
        transform: scale(1.1);
    }
    .modal-body {
        padding: 0;
    }
    .modal-body .ratio {
        border-bottom-left-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        overflow: hidden;
    }
</style><script>
document.addEventListener("DOMContentLoaded", function () {
    const accordions = document.querySelectorAll("#courseAccordion .accordion-button");

    accordions.forEach(button => {
        button.addEventListener("click", function () {
            const target = document.querySelector(this.getAttribute("data-target"));
            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(target, { toggle: false });

            if (target.classList.contains("show")) {
                // If already open → close it
                bsCollapse.hide();
                this.classList.add("collapsed");
                this.setAttribute("aria-expanded", "false");
            } else {
                // Close all other open accordions first
                document.querySelectorAll("#courseAccordion .accordion-collapse.show").forEach(openItem => {
                    const openCollapse = bootstrap.Collapse.getInstance(openItem);
                    if (openCollapse) openCollapse.hide();
                });

                // Then open the clicked one
                bsCollapse.show();
                this.classList.remove("collapsed");
                this.setAttribute("aria-expanded", "true");
            }
        });
    });

    // === Video modal script (unchanged) ===
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        videoModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const videoSrc = button.getAttribute('data-video-src');
            const videoTitle = button.getAttribute('data-video-title');
            const modalTitle = videoModal.querySelector('.modal-title');
            const iframe = videoModal.querySelector('#courseVideoIframe');
            modalTitle.textContent = videoTitle;
            iframe.setAttribute('src', videoSrc + "?autoplay=1&rel=0"); 
        });

        videoModal.addEventListener('hide.bs.modal', event => {
            const iframe = videoModal.querySelector('#courseVideoIframe');
            iframe.setAttribute('src', ''); 
        });
    }
});

// Prompt profile completion if college missing
$(function() {
    $.ajax({
        url: '../controller/ProfileController.php?action=get',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if (resp && resp.success) {
                const profile = resp.profile || {};
                const needsCollege = !profile.college || String(profile.college).trim() === '';
                if (needsCollege) {
                    // Build and show modal
                    const modal = `
                    <div class="modal fade" id="completeProfileModal" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Complete Your Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Please update your profile. Which college do you belong to?</p>
                            <div class="mb-3">
                              <label class="form-label" for="modalCollege">College</label>
                              <input type="text" id="modalCollege" class="form-control" placeholder="Enter college name" required />
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
                            <button type="button" class="btn btn-primary" id="saveCollegeBtn">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>`;
                    $('body').append(modal);
                    const modalEl = document.getElementById('completeProfileModal');
                    const bsModal = new bootstrap.Modal(modalEl);
                    bsModal.show();
                    $(document).on('click', '#saveCollegeBtn', function() {
                        const college = $('#modalCollege').val();
                        if (!college || !college.trim()) { alert('Please enter your college'); return; }
                        $.ajax({
                            url: '../controller/ProfileController.php?action=update',
                            type: 'POST',
                            dataType: 'json',
                            data: { college: college.trim() },
                            success: function(r) {
                                if (r && r.success) {
                                    alert('Thanks! Your college has been saved.');
                                    bsModal.hide();
                                } else {
                                    alert('Failed to save college: ' + (r.message || 'Unknown error'));
                                }
                            },
                            error: function() { alert('Error saving college'); }
                        });
                    });
                }
            }
        }
    });
});

</script>


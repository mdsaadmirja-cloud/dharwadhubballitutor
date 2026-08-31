<?php
// Admin/View/Work Upload/work-upload.php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Work Upload</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .emp-quicknav {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: .5rem 1rem;
        }

        .emp-quicknav a {
            text-decoration: none;
            font-size: .9rem;
            padding: .4rem .8rem;
            border-radius: 6px;
            color: #495057;
        }

        .emp-quicknav a.active {
            background: #0d6efd;
            color: #fff;
        }

        .emp-quicknav a:not(.active):hover {
            background: #e9ecef;
        }

    </style>

</head>


<body>


<div class="emp-quicknav">

    <a href="../Task Management System/my-tasks.php">
        <i class="bi bi-person-check"></i>
        My Tasks
    </a>

    <a
        href="work-upload.php"
        class="active"
    >
        <i class="bi bi-cloud-arrow-up"></i>
        Work Upload
    </a>

    <a href="submission-history.php">
        <i class="bi bi-clock-history"></i>
        Submission History
    </a>

</div>


<div
    class="container py-4"
    style="max-width:720px"
>

    <h4 class="mb-3">

        <i class="bi bi-cloud-arrow-up"></i>

        <span id="pageTitle">
            Upload Work
        </span>

    </h4>


    <!-- Revision message -->

    <div id="revisionAlert"></div>


    <div class="card">

        <div class="card-body">

            <form
                id="uploadForm"
                enctype="multipart/form-data"
            >

                <!-- TITLE -->

                <div class="mb-3">

                    <label class="form-label">
                        Title *
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="title"
                        required
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        name="description"
                        rows="3"
                    ></textarea>

                </div>


                <!-- CATEGORY + HOURS -->

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            class="form-select"
                            name="category_id"
                            id="category_id"
                        >

                            <option value="">
                                -- Select --
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Hours Worked
                        </label>

                        <input
                            type="number"
                            step="0.5"
                            class="form-control"
                            name="hours_worked"
                        >

                    </div>

                </div>


                <!-- GITHUB -->

                <div class="mb-3">

                    <label class="form-label">
                        GitHub Link
                    </label>

                    <input
                        type="url"
                        class="form-control"
                        name="github_link"
                        placeholder="https://github.com/..."
                    >

                </div>


                <!-- LIVE URL -->

                <div class="mb-3">

                    <label class="form-label">
                        Live URL
                    </label>

                    <input
                        type="url"
                        class="form-control"
                        name="live_url"
                        placeholder="https://..."
                    >

                </div>


                <!-- DRIVE -->

                <div class="mb-3">

                    <label class="form-label">
                        Drive Link
                    </label>

                    <input
                        type="url"
                        class="form-control"
                        name="drive_link"
                        placeholder="https://drive.google.com/..."
                    >

                </div>


                <!-- NEXT PLAN -->

                <div class="mb-3">

                    <label class="form-label">
                        Next Plan (optional)
                    </label>

                    <textarea
                        class="form-control"
                        name="next_plan"
                        rows="2"
                    ></textarea>

                </div>


                <!-- ATTACHMENTS -->

                <div class="mb-3">

                    <label class="form-label">
                        Attachments
                    </label>

                    <input
                        type="file"
                        class="form-control"
                        name="attachments[]"
                        multiple
                    >

                    <div class="form-text">
                        For revision, select any new files you want to add.
                    </div>

                </div>


                <!-- SUBMIT -->

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >

                    <i class="bi bi-upload"></i>

                    <span id="submitButtonText">
                        Submit Work
                    </span>

                </button>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

const CONTROLLER =
    '../../Controller/employeeworkuploadcontroller.php';

const TASK_CONTROLLER =
    '../../Controller/TaskController.php';


// ============================================================
// EDIT ID
// ============================================================

const editId =
    new URLSearchParams(
        window.location.search
    ).get('edit');


// ============================================================
// ESCAPE HTML
// ============================================================

function escapeHtml(str)
{
    const div =
        document.createElement('div');

    div.textContent =
        str ?? '';

    return div.innerHTML;
}


// ============================================================
// LOAD CATEGORIES
// ============================================================

async function loadCategories()
{
    try {

        const res =
            await fetch(
                `${TASK_CONTROLLER}?action=lookups`
            );

        const json =
            await res.json();

        if (!json.success) {
            return;
        }

        document.getElementById(
            'category_id'
        ).innerHTML =
            '<option value="">-- Select --</option>' +

            json.categories
                .map(c =>
                    `<option value="${c.id}">
                        ${escapeHtml(c.name)}
                    </option>`
                )
                .join('');

    } catch (error) {

        console.error(
            'Category loading error:',
            error
        );
    }
}


// ============================================================
// LOAD REVISION DATA
// ============================================================

async function loadRevision()
{
    if (!editId) {
        return;
    }

    try {

        const res =
            await fetch(
                `${CONTROLLER}?action=employee_get&id=${encodeURIComponent(editId)}`
            );

        const json =
            await res.json();


        if (!json.success) {

            alert(
                json.message ||
                'Unable to load submission'
            );

            return;
        }


        const u =
            json.data;


        // Page title

        document.getElementById(
            'pageTitle'
        ).textContent =
            'Update Work';


        // Button

        document.getElementById(
            'submitButtonText'
        ).textContent =
            'Update & Resubmit';


        // Admin review message

        document.getElementById(
            'revisionAlert'
        ).innerHTML = `

            <div class="alert alert-warning">

                <strong>

                    <i class="bi bi-exclamation-triangle"></i>

                    Needs Revision

                </strong>

                <br><br>

                ${escapeHtml(
                    u.review_comment ||
                    'Please update your submission and resubmit it.'
                )}

            </div>

        `;


        // Existing fields

        document.querySelector(
            '[name="title"]'
        ).value =
            u.title ?? '';


        document.querySelector(
            '[name="description"]'
        ).value =
            u.description ?? '';


        document.querySelector(
            '[name="hours_worked"]'
        ).value =
            u.hours_worked ?? '';


        document.querySelector(
            '[name="github_link"]'
        ).value =
            u.github_link ?? '';


        document.querySelector(
            '[name="live_url"]'
        ).value =
            u.live_url ?? '';


        document.querySelector(
            '[name="drive_link"]'
        ).value =
            u.drive_link ?? '';


        document.querySelector(
            '[name="next_plan"]'
        ).value =
            u.next_plan ?? '';


        // Category

        if (u.category_id) {

            document.getElementById(
                'category_id'
            ).value =
                u.category_id;
        }


    } catch (error) {

        console.error(
            'Revision loading error:',
            error
        );

        alert(
            'Unable to load submission.'
        );
    }
}


// ============================================================
// FORM SUBMIT
// ============================================================

document.getElementById(
    'uploadForm'
).addEventListener(
    'submit',
    async function(e)
    {

        e.preventDefault();


        const formData =
            new FormData(this);


        // --------------------------------------------------------
        // REVISION
        // --------------------------------------------------------

        if (editId) {

            formData.set(
                'action',
                'update_submission'
            );

            formData.set(
                'id',
                editId
            );

        }

        // --------------------------------------------------------
        // NEW UPLOAD
        // --------------------------------------------------------

        else {

            formData.set(
                'action',
                'upload'
            );
        }


        try {

            const res =
                await fetch(
                    CONTROLLER,
                    {
                        method: 'POST',
                        body: formData
                    }
                );


            const json =
                await res.json();


            if (json.success) {

                alert(
                    editId

                    ? 'Work updated and resubmitted successfully!'

                    : 'Work submitted! Your admin has been notified.'
                );


                window.location.href =
                    'submission-history.php';


            } else {

                alert(
                    json.message ||
                    'Something went wrong'
                );
            }


        } catch (error) {

            console.error(
                'Upload error:',
                error
            );

            alert(
                'Unable to submit work.'
            );
        }

    }
);


// ============================================================
// INITIAL LOAD
// ============================================================

document.addEventListener(
    'DOMContentLoaded',
    async function()
    {

        await loadCategories();


        if (editId) {

            await loadRevision();

        }

    }
);

</script>


</body>

</html>
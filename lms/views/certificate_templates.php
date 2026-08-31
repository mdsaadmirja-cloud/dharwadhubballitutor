<?php
// lms/views/certificate_templates.php

session_start();

require_once "../model/CertificateTemplate.php";

$templateModel = new CertificateTemplate();
$templates = $templateModel->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Certificate Templates</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            color: #34495e;
            font-size: 26px;
            font-weight: 600;
            margin: 0;
        }

        .template-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            transition: 0.2s;
        }

        .template-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .template-header {
            padding: 14px 16px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .template-name {
            font-size: 17px;
            font-weight: 600;
            color: #34495e;
        }

        .template-body {
            padding: 18px;
        }

        .template-file {
            color: #667085;
            font-size: 14px;
            margin-bottom: 10px;
            word-break: break-word;
        }

        .template-description {
            color: #667085;
            font-size: 14px;
            min-height: 45px;
        }

        .template-footer {
            padding: 12px 16px;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 5px;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed #ced4da;
            border-radius: 8px;
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
        }

        .upload-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
        }

        .selected-file {
            margin-top: 8px;
            font-size: 13px;
            color: #198754;
            word-break: break-word;
        }

        .current-file {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            word-break: break-word;
        }

    </style>

</head>


<body>


<div class="container-fluid p-4">


    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="page-header">

        <h1 class="page-title">
            Certificate Templates
        </h1>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#createTemplateModal"
        >
            + Create New Template
        </button>

    </div>


    <!-- ========================================================= -->
    <!-- TEMPLATE LIST -->
    <!-- ========================================================= -->

    <div class="row g-4">

        <?php if (!empty($templates)): ?>

            <?php foreach ($templates as $template): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="template-card">

                        <!-- HEADER -->

                        <div class="template-header">

                            <div class="template-name">
                                <?= htmlspecialchars($template['name']) ?>
                            </div>

                            <?php if ($template['status'] === 'active'): ?>

                                <span class="badge bg-success">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- BODY -->

                        <div class="template-body">

                            <div class="template-file">

                                <strong>
                                    Template File:
                                </strong>

                                <br>

                                <?= htmlspecialchars($template['template_file']) ?>

                            </div>


                            <div class="template-description">

                                <?php

                                $description = trim(
                                    $template['description'] ?? ''
                                );

                                if ($description !== '') {

                                    echo htmlspecialchars($description);

                                } else {

                                    echo 'No description available.';

                                }

                                ?>

                            </div>

                        </div>


                        <!-- FOOTER -->

                        <div class="template-footer">

                            <button
                                type="button"
                                class="btn btn-warning btn-sm"
                                onclick='editTemplate(
                                    <?= (int)$template['id'] ?>,
                                    <?= json_encode($template['name']) ?>,
                                    <?= json_encode($template['template_file']) ?>,
                                    <?= json_encode($template['description'] ?? '') ?>,
                                    <?= json_encode($template['status']) ?>
                                )'
                            >
                                Edit
                            </button>


                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="deleteTemplate(<?= (int)$template['id'] ?>)"
                            >
                                Delete
                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php else: ?>

            <div class="col-12">

                <div class="empty-state">

                    <h5>
                        No Certificate Templates
                    </h5>

                    <p class="mb-0">
                        Create your first certificate template.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>



<!-- ========================================================= -->
<!-- CREATE TEMPLATE MODAL -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="createTemplateModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Create Certificate Template
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <!-- IMPORTANT: enctype -->
            <form
                id="createTemplateForm"
                enctype="multipart/form-data"
            >

                <div class="modal-body">


                    <!-- TEMPLATE NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Template Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Example: Course Completion Certificate"
                            required
                        >

                    </div>


                    <!-- TEMPLATE FILE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Template File *
                        </label>

                        <input
                            type="file"
                            name="template_file"
                            id="create_template_file"
                            class="form-control"
                            required
                            onchange="showSelectedCreateFile(this)"
                        >

                        <div class="upload-info">
                            Select the certificate template file from your computer.
                        </div>

                        <div
                            id="create_selected_file"
                            class="selected-file"
                        ></div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="Enter template description"
                        ></textarea>

                    </div>


                    <!-- STATUS -->

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Create Template
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- EDIT TEMPLATE MODAL -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="editTemplateModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Certificate Template
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                id="editTemplateForm"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    name="id"
                    id="edit_template_id"
                >


                <div class="modal-body">


                    <!-- TEMPLATE NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Template Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="edit_template_name"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- CURRENT FILE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Current Template File
                        </label>

                        <div
                            id="current_template_file"
                            class="current-file"
                        >
                            No file selected
                        </div>

                    </div>


                    <!-- REPLACE FILE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Replace Template File
                        </label>

                        <input
                            type="file"
                            name="template_file"
                            id="edit_template_file"
                            class="form-control"
                            onchange="showSelectedEditFile(this)"
                        >

                        <div class="upload-info">
                            Leave empty if you do not want to replace the current file.
                        </div>

                        <div
                            id="edit_selected_file"
                            class="selected-file"
                        ></div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="edit_template_description"
                            class="form-control"
                            rows="3"
                        ></textarea>

                    </div>


                    <!-- STATUS -->

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            id="edit_template_status"
                            class="form-select"
                        >

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Template
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

/*
|--------------------------------------------------------------------------
| SHOW SELECTED CREATE FILE
|--------------------------------------------------------------------------
*/

function showSelectedCreateFile(input)
{
    const output =
        document.getElementById('create_selected_file');

    if (input.files && input.files.length > 0) {

        output.textContent =
            'Selected: ' + input.files[0].name;

    } else {

        output.textContent = '';

    }
}



/*
|--------------------------------------------------------------------------
| SHOW SELECTED EDIT FILE
|--------------------------------------------------------------------------
*/

function showSelectedEditFile(input)
{
    const output =
        document.getElementById('edit_selected_file');

    if (input.files && input.files.length > 0) {

        output.textContent =
            'New file selected: ' + input.files[0].name;

    } else {

        output.textContent = '';

    }
}



/*
|--------------------------------------------------------------------------
| CREATE TEMPLATE
|--------------------------------------------------------------------------
*/

document
    .getElementById('createTemplateForm')
    .addEventListener('submit', function(e) {

        e.preventDefault();

        const formData =
            new FormData(this);

        formData.append(
            'action',
            'create'
        );


        fetch(
            '../controller/certificate_template_actions.php',
            {
                method: 'POST',
                body: formData
            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                alert(data.message);

                location.reload();

            } else {

                alert(data.message);

            }

        })

        .catch(error => {

            console.error(error);

            alert(
                'Something went wrong while creating the template.'
            );

        });

    });



/*
|--------------------------------------------------------------------------
| EDIT TEMPLATE
|--------------------------------------------------------------------------
*/

function editTemplate(
    id,
    name,
    file,
    description,
    status
)
{

    document.getElementById(
        'edit_template_id'
    ).value = id;


    document.getElementById(
        'edit_template_name'
    ).value = name;


    document.getElementById(
        'current_template_file'
    ).textContent =
        file || 'No file selected';


    document.getElementById(
        'edit_template_file'
    ).value = '';


    document.getElementById(
        'edit_selected_file'
    ).textContent = '';


    document.getElementById(
        'edit_template_description'
    ).value = description;


    document.getElementById(
        'edit_template_status'
    ).value = status;


    const modal =
        new bootstrap.Modal(
            document.getElementById(
                'editTemplateModal'
            )
        );


    modal.show();

}



/*
|--------------------------------------------------------------------------
| UPDATE TEMPLATE
|--------------------------------------------------------------------------
*/

document
    .getElementById('editTemplateForm')
    .addEventListener('submit', function(e) {

        e.preventDefault();

        const formData =
            new FormData(this);

        formData.append(
            'action',
            'update'
        );


        fetch(
            '../controller/certificate_template_actions.php',
            {
                method: 'POST',
                body: formData
            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                alert(data.message);

                location.reload();

            } else {

                alert(data.message);

            }

        })

        .catch(error => {

            console.error(error);

            alert(
                'Something went wrong while updating the template.'
            );

        });

    });



/*
|--------------------------------------------------------------------------
| DELETE TEMPLATE
|--------------------------------------------------------------------------
*/

function deleteTemplate(id)
{

    if (
        !confirm(
            'Are you sure you want to delete this certificate template?'
        )
    ) {

        return;

    }


    const formData =
        new FormData();


    formData.append(
        'action',
        'delete'
    );


    formData.append(
        'id',
        id
    );


    fetch(
        '../controller/certificate_template_actions.php',
        {
            method: 'POST',
            body: formData
        }
    )

    .then(response => response.json())

    .then(data => {

        if (data.success) {

            alert(data.message);

            location.reload();

        } else {

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(error);

        alert(
            'Something went wrong while deleting the template.'
        );

    });

}

</script>


</body>

</html>
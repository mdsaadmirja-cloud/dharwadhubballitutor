<?php
// Admin/View/Work Upload/submission-history.php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Submission History</title>

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


    <a href="work-upload.php">

        <i class="bi bi-cloud-arrow-up"></i>

        Work Upload

    </a>


    <a
        href="submission-history.php"
        class="active"
    >

        <i class="bi bi-clock-history"></i>

        Submission History

    </a>

</div>


<div class="container py-4">

    <h4 class="mb-3">

        <i class="bi bi-clock-history"></i>

        My Submission History

    </h4>


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                >

                    <thead class="table-light">

                        <tr>

                            <th>Title</th>

                            <th>Hours</th>

                            <th>Status</th>

                            <th>Review</th>

                            <th>Review Comment</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody id="historyTable">

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4 text-muted"
                            >
                                Loading...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

const CONTROLLER =
    '../../Controller/employeeworkuploadcontroller.php';


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
// REVIEW BADGE
// ============================================================

function reviewBadgeClass(status)
{
    return status === 'Approved'
        ? 'bg-success'

        : status === 'Needs Revision'
        ? 'bg-warning text-dark'

        : status === 'Rejected'
        ? 'bg-danger'

        : 'bg-secondary';
}


// ============================================================
// LOAD HISTORY
// ============================================================

async function loadHistory()
{
    const tbody =
        document.getElementById(
            'historyTable'
        );


    try {

        const res =
            await fetch(
                `${CONTROLLER}?action=my_uploads`
            );


        const json =
            await res.json();


        if (
            !json.success ||
            !Array.isArray(json.data) ||
            json.data.length === 0
        ) {

            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-4 text-muted"
                    >
                        No submissions yet
                    </td>

                </tr>

            `;

            return;
        }


        tbody.innerHTML =
            json.data.map(item => `

                <tr>

                    <td>

                        ${escapeHtml(
                            item.title
                        )}

                    </td>


                    <td>

                        ${item.hours_worked ?? '-'}

                    </td>


                    <td>

                        ${escapeHtml(
                            item.status
                        )}

                    </td>


                    <td>

                        <span
                            class="badge ${reviewBadgeClass(
                                item.review_status
                            )}"
                        >

                            ${escapeHtml(
                                item.review_status
                            )}

                        </span>

                    </td>


                    <td>

                        ${escapeHtml(
                            item.review_comment ?? '-'
                        )}

                    </td>


                    <td>

                        ${escapeHtml(
                            item.created_at
                        )}

                    </td>


                    <td>

                        ${
                            item.review_status ===
                            'Needs Revision'

                            ? `

                                <a
                                    href="work-upload.php?edit=${Number(item.id)}"
                                    class="btn btn-warning btn-sm"
                                    title="Update Submission"
                                >

                                    <i class="bi bi-pencil-square"></i>

                                    Update

                                </a>

                            `

                            : `

                                <span class="text-muted">
                                    -
                                </span>

                            `
                        }

                    </td>

                </tr>

            `).join('');


    } catch (error) {

        console.error(
            'Submission history error:',
            error
        );


        tbody.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="text-center py-4"
                >

                    <div class="alert alert-danger mb-0">

                        Unable to load submission history.

                    </div>

                </td>

            </tr>

        `;
    }
}


// ============================================================
// INITIAL LOAD
// ============================================================

document.addEventListener(
    'DOMContentLoaded',
    loadHistory
);

</script>


</body>

</html>
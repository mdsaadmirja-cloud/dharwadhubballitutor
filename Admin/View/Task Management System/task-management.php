<?php
// Admin/View/Task Management System/task-management.php
// Adjust this include to wherever your header/sidebar partial actually lives.
// require_once __DIR__ . '/../partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Task Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background: #f4f6f9;
    }

    .priority-High,
    .priority-Urgent {
      color: #dc3545;
      font-weight: 600;
    }

    .priority-Medium {
      color: #fd7e14;
      font-weight: 600;
    }

    .priority-Low {
      color: #198754;
      font-weight: 600;
    }

    .badge-status {
      font-size: .75rem;
    }

    .progress {
      height: 8px;
    }

    .avatar-stack img {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 2px solid #fff;
      margin-left: -10px;
      object-fit: cover;
    }

    /* Force the modal body to scroll no matter what other CSS is loaded on the page */
    #taskModal .modal-dialog {
      max-height: 90vh !important;
    }

    #taskModal .modal-content {
      max-height: 90vh !important;
      display: flex !important;
      flex-direction: column !important;
    }

    #taskModal .modal-body {
      overflow-y: auto !important;
      max-height: calc(90vh - 130px) !important;
      /* leaves room for header + footer */
    }

    /* Fix Task View Bootstrap modal appearing behind backdrop */
    #reviewModal {
      z-index: 1060 !important;
    }

    #reviewModal .modal-dialog {
      z-index: 1061 !important;
    }

    .modal-backdrop {
      z-index: 1050 !important;
    }

    #reviewModal {
      z-index: 1060 !important;
    }

    #reviewModal .modal-dialog {
      z-index: 1061 !important;
    }

    .modal-backdrop {
      z-index: 1050 !important;
    }
  </style>
</head>

<body>

  <div class="container-fluid py-4"> <!-- OPEN #1: container-fluid (page wrapper) -->

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">
        <i class="bi bi-list-task"></i> Task Management
      </h4>

      <div>
        <a href="../dashboard.php" class="btn btn-outline-secondary me-2">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

        <button class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#taskModal"
          onclick="openAddModal()">
          <i class="bi bi-plus-lg"></i> Assign Task
        </button>
      </div>
    </div> <!-- CLOSE: page header (d-flex) -->

    <ul class="nav nav-tabs" id="taskTabs">

      <li class="nav-item">
        <button
          class="nav-link active"
          data-bs-toggle="tab"
          data-bs-target="#assignedTasks">

          📋 Assigned Tasks

        </button>
      </li>

      <li class="nav-item">
        <button
          class="nav-link"
          data-bs-toggle="tab"
          data-bs-target="#workUploads">

          📤 Employee Work Uploads

        </button>
      </li>

    </ul>

    <div class="tab-content mt-3"> <!-- OPEN #2: tab-content (holds both tab-panes) -->

      <div class="tab-pane fade show active" id="assignedTasks"> <!-- OPEN #3: tab-pane #1 = Assigned Tasks -->

        <!-- Filters -->
        <div class="card mb-3">
          <div class="card-body row g-2">
            <div class="col-md-3">
              <input type="text" id="filterSearch" class="form-control" placeholder="Search by title...">
            </div>
            <div class="col-md-2">
              <select id="filterStatus" class="form-select">
                <option value="">All Status</option>
                <option>Pending</option>
                <option>Running</option>
                <option>Review</option>
                <option>Completed</option>
                <option>Cancelled</option>
              </select>
            </div>
            <div class="col-md-2">
              <select id="filterPriority" class="form-select">
                <option value="">All Priority</option>
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Urgent</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" onclick="loadTasks()"><i class="bi bi-funnel"></i> Filter</button>
            </div>
          </div>
        </div>

        <!-- Task table -->
        <div class="card">
          <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Task</th>
                  <th>Category</th>
                  <th>Priority</th>
                  <th>Assignees</th>
                  <th>Progress</th>
                  <th>Status</th>
                  <th>Deadline</th>
                  <th width="180">Actions</th>
                </tr>
              </thead>
              <tbody id="taskTableBody">
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">Loading...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div> <!-- CLOSE #3: tab-pane #1 = Assigned Tasks (id="assignedTasks") -->

      <div class="tab-pane fade" id="workUploads"> <!-- OPEN #4: tab-pane #2 = Employee Work Uploads -->

        <!-- Filters (mirrors the Assigned Tasks filter card) -->
        <div class="card mb-3">
          <div class="card-body row g-2">
            <div class="col-md-4">
              <input
                type="text"
                id="uploadSearch"
                class="form-control"
                placeholder="Search Employee / Work Title">
            </div>
            <div class="col-md-3">
              <select
                id="reviewFilter"
                class="form-select">
                <option value="">All Reviews</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Needs Revision">Needs Revision</option>
                <option value="Rejected">Rejected</option>
              </select>
            </div>
            <div class="col-md-2">
              <button
                class="btn btn-outline-secondary w-100"
                onclick="loadWorkUploads()">
                <i class="bi bi-funnel"></i> Filter
              </button>
            </div>
          </div>
        </div>

        <!-- Work upload table (mirrors the Task table card) -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📤 Employee Work Uploads</h5>
            <span class="badge bg-danger" id="pendingUploadsBadge">0 Pending</span>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Employee</th>
                  <th>Title</th>
                  <th>Hours</th>
                  <th>Status</th>
                  <th>Review</th>
                  <th>Date</th>
                  <th width="120">Actions</th>
                </tr>
              </thead>
              <tbody id="workUploadTable">
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Loading...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div> <!-- CLOSE #4: tab-pane #2 = Employee Work Uploads (id="workUploads") -->

    </div> <!-- CLOSE #2: tab-content -->

  </div> <!-- CLOSE #1: container-fluid (page wrapper) -->

  <!-- ============================================================ -->
  <!-- ADD / VIEW TASK MODAL -->
  <!-- ============================================================ -->
  <div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="taskForm">
          <div class="modal-header">
            <h5 class="modal-title" id="taskModalTitle">Assign Task</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <input type="hidden" name="id" id="task_id">

            <div class="mb-3">
              <label class="form-label">Task Title *</label>
              <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Description *</label>
              <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Priority *</label>
                <select class="form-select" name="priority" id="priority" required>
                  <option>Low</option>
                  <option selected>Medium</option>
                  <option>High</option>
                  <option>Urgent</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id" id="category_id">
                  <option value="">-- Select --</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Employees *</label>
              <div id="employee_ids" class="border rounded p-2" style="max-height:220px; overflow-y:auto;"></div>
              <div class="form-text">Check one or more employees. Details (branch, shift, checkout time) are pulled automatically.</div>
            </div>

            <!--
<div class="mb-3">
  <label class="form-label">Checklist Items (optional)</label>
  <div id="checklistWrap">
    <input type="text" class="form-control mb-2" name="checklist[]" placeholder="e.g. Upload final draft">
  </div>

  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addChecklistRow()">
    <i class="bi bi-plus"></i> Add item
  </button>
</div>
-->

            <hr>
            <h6>Deadline</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" id="start_date">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date" id="end_date">
              </div>
              <!--
<div class="col-md-4 mb-3">
  <label class="form-label">Estimated Hours</label>
  <input type="number" step="0.5" class="form-control" name="estimated_hours" id="estimated_hours">
</div>
-->
            </div>

            <hr>
            <h6>Reminder Settings</h6>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Reminder Before Deadline</label>
                <select class="form-select" name="reminder_before_minutes">
                  <option value="15">15 Minutes</option>
                  <option value="30" selected>30 Minutes</option>
                  <option value="60">60 Minutes</option>
                </select>
              </div>

              <!--
<div class="col-md-6 mb-3">
  <label class="form-label">Submission Window</label>
  <select class="form-select" name="submission_window_minutes">
    <option value="5">5 Minutes</option>
    <option value="10" selected>10 Minutes</option>
    <option value="20">20 Minutes</option>
  </select>
</div>
-->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="send_reminder_email" id="send_reminder_email" checked>
                <label class="form-check-label" for="send_reminder_email">Send Reminder Email</label>
              </div>
              <!--
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="lock_after_window" id="lock_after_window" checked>
              <label class="form-check-label" for="lock_after_window">Lock Task After Window</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="send_thankyou_email" id="send_thankyou_email" checked>
              <label class="form-check-label" for="send_thankyou_email">Send Thank You Email</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="notify_admin_if_missed" id="notify_admin_if_missed" checked>
              <label class="form-check-label" for="notify_admin_if_missed">Notify Admin if Missed</label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="auto_escalate" id="auto_escalate">
              <label class="form-check-label" for="auto_escalate">Auto Escalate</label>
            </div>
-->

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Assign Task</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- REVIEW MODAL — shared by Assigned Tasks (viewTask) and        -->
  <!-- Employee Work Uploads (viewWorkUpload). Same modal, same      -->
  <!-- Bootstrap markup; each function fills #reviewBody with its    -->
  <!-- own content, mirroring the Task review layout.                -->
  <!-- ============================================================ -->
  <div class="modal fade" id="reviewModal">

    <div class="modal-dialog modal-xl">

      <div class="modal-content">

        <div class="modal-header">

          <h5>Employee Submission</h5>

          <button class="btn-close"
            data-bs-dismiss="modal"></button>

        </div>

        <div class="modal-body"
          id="reviewBody">

          Loading...

        </div>

      </div>

    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const CONTROLLER = '../../Controller/TaskController.php'; // adjust relative path to match your folder depth
    function escapeHtml(value) {
      if (value === null || value === undefined) {
        return '';
      }

      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
    // ------------------------------------------------------------------
    // LOAD TASK LIST
    // ------------------------------------------------------------------
    async function loadTasks() {

      const tbody = document.getElementById('taskTableBody');

      tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center py-4 text-muted">
                Loading...
            </td>
        </tr>
    `;

      try {

        const params = new URLSearchParams({
          action: 'list',
          search: document.getElementById('filterSearch').value,
          status: document.getElementById('filterStatus').value,
          priority: document.getElementById('filterPriority').value
        });

        const res = await fetch(`${CONTROLLER}?${params}`);

        const text = await res.text();

        let json;

        try {
          json = JSON.parse(text);
        } catch (e) {

          console.error('Invalid JSON from TaskController:', text);

          tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="alert alert-danger mb-0">
                            Task controller returned an invalid response.
                            Check PHP error log.
                        </div>
                    </td>
                </tr>
            `;

          return;
        }

        if (!json.success) {

          tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="alert alert-danger mb-0">
                            ${escapeHtml(json.message || 'Unable to load tasks')}
                        </div>
                    </td>
                </tr>
            `;

          return;
        }

        if (!Array.isArray(json.data) || json.data.length === 0) {

          tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        No tasks found
                    </td>
                </tr>
            `;

          return;
        }

        tbody.innerHTML = json.data.map((item) => `
    <tr>

        <td>
            <a href="#"
               onclick="viewTask(${Number(item.id)}); return false;"
               class="fw-semibold text-decoration-none">

                ${escapeHtml(item.title)}

            </a>

            <div class="text-muted small">
                ${escapeHtml(item.BranchName ?? '')}
            </div>
        </td>

        <td>
            ${escapeHtml(item.category_name ?? '')}
        </td>

        <td>
            ${escapeHtml(item.priority ?? '')}
        </td>

        <td>
            ${escapeHtml(item.assignees ?? '')}
        </td>

        <td>
            <div class="progress">
                <div
                    class="progress-bar"
                    style="width:${Number(item.progress_percent || 0)}%">
                </div>
            </div>

            <small>
                ${Number(item.progress_percent || 0)}%
            </small>
        </td>

        <td>
            ${escapeHtml(item.status ?? '')}
        </td>

        <td>
            ${escapeHtml(item.end_date ?? '')}
        </td>

        <td>
            <div class="btn-group">

                <button
                    type="button"
                    class="btn btn-info btn-sm"
                    onclick="viewTask(${Number(item.id)})"
                    title="View">
                    <i class="bi bi-eye"></i>
                </button>

                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    onclick="approveTask(${Number(item.id)})"
                    title="Mark as Completed">
                    <i class="bi bi-check-lg"></i>
                </button>

                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="deleteTask(${Number(item.id)})"
                    title="Delete">
                    <i class="bi bi-trash"></i>
                </button>

            </div>
        </td>

    </tr>
`).join('');

      } catch (error) {

        console.error('loadTasks error:', error);

        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="alert alert-danger mb-0">
                        Failed to load tasks.
                        Open browser Console (F12) for details.
                    </div>
                </td>
            </tr>
        `;
      }
    }

    // ------------------------------------------------------------------
    // LOOKUPS (categories + employees) — loaded once when modal opens
    // ------------------------------------------------------------------
    async function loadLookups() {
      const res = await fetch(`${CONTROLLER}?action=lookups`);
      const json = await res.json();
      if (!json.success) return;

      const catSelect = document.getElementById('category_id');
      catSelect.innerHTML = '<option value="">-- Select --</option>' +
        json.categories.map(c => `<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');

      const empWrap = document.getElementById('employee_ids');
      empWrap.innerHTML = json.employees.map(e => `
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="employee_ids[]" value="${e.id}" id="emp_${e.id}">
      <label class="form-check-label" for="emp_${e.id}">
        ${escapeHtml(e.Name)} — ${escapeHtml(e.Designation ?? '')} (${escapeHtml(e.BranchName ?? '')})
      </label>
    </div>
  `).join('');
    }

    function openAddModal() {
      document.getElementById('taskForm').reset();
      document.getElementById('task_id').value = '';
      document.getElementById('taskModalTitle').textContent = 'Assign Task';
      loadLookups();
    }

    // ------------------------------------------------------------------
    // SUBMIT (Assign Task)
    // ------------------------------------------------------------------
    document.getElementById('taskForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const checkedEmployees = document.querySelectorAll('#employee_ids input[type=checkbox]:checked');
      if (checkedEmployees.length === 0) {
        alert('Select at least one employee');
        return;
      }

      const formData = new FormData(e.target);
      const isEdit = !!formData.get('id');
      formData.set('action', isEdit ? 'edit' : 'add');

      const res = await fetch(CONTROLLER, {
        method: 'POST',
        body: formData
      });
      const json = await res.json();

      if (json.success) {
        bootstrap.Modal.getInstance(document.getElementById('taskModal')).hide();
        loadTasks();
      } else {
        alert(json.message || 'Something went wrong');
      }
    });

    // ------------------------------------------------------------------
    // VIEW / DELETE
    // ------------------------------------------------------------------
    function escapeHtml(value) {
      if (value === null || value === undefined) {
        return '';
      }

      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
    async function viewTask(id) {

      try {

        const modalElement = document.getElementById('reviewModal');

        if (!modalElement) {
          alert('Review modal not found.');
          return;
        }

        // IMPORTANT:
        // Move modal directly under <body>.
        // This prevents parent containers / CSS transforms
        // from hiding the Bootstrap modal behind the backdrop.
        if (modalElement.parentElement !== document.body) {
          document.body.appendChild(modalElement);
        }

        // Force Bootstrap modal above the backdrop
        modalElement.style.zIndex = '1060';

        const reviewBody = document.getElementById('reviewBody');

        if (!reviewBody) {
          alert('Review body not found.');
          return;
        }

        reviewBody.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2">Loading task...</div>
            </div>
        `;

        // Open modal
        const modal = bootstrap.Modal.getOrCreateInstance(
          modalElement, {
            backdrop: true,
            keyboard: true
          }
        );

        modal.show();


        // ============================================================
        // GET TASK DATA
        // ============================================================

        const response = await fetch(
          `${CONTROLLER}?action=view_review&id=${encodeURIComponent(id)}`, {
            method: 'GET',
            cache: 'no-store'
          }
        );

        const text = await response.text();

        let json;

        try {

          json = JSON.parse(text);

        } catch (e) {

          console.error('View Task server response:', text);

          reviewBody.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Server returned an invalid response.</strong>
                    <br><br>
                    Please check the server response.
                </div>
            `;

          return;
        }


        // ============================================================
        // SERVER ERROR
        // ============================================================

        if (!json.success) {

          reviewBody.innerHTML = `
                <div class="alert alert-danger">
                    ${escapeHtml(
                        json.message || 'Unable to load task'
                    )}
                </div>
            `;

          return;
        }


        // ============================================================
        // TASK DATA
        // ============================================================

        const task = json.task || {};

        const assignments = Array.isArray(json.assignments) ?
          json.assignments : [];

        const assignment = assignments.length > 0 ?
          assignments[0] : {};


        // ============================================================
        // MAIN TASK INFORMATION
        // ============================================================

        let html = `

            <div class="row">

                <div class="col-md-8">

                    <h4 class="mb-3">
                        ${escapeHtml(task.title || '')}
                    </h4>

                    <p>
                        ${escapeHtml(task.description || '')}
                    </p>

                    <hr>

                    <h5 class="mb-3">
                        Employee
                    </h5>

                    ${
                        assignments.length === 0
                        ? `
                            <div class="alert alert-warning">
                                No employee has been assigned to this task.
                            </div>
                        `
                        : `
                            <div class="table-responsive">

                                <table class="table table-bordered">

                                    <tr>
                                        <th width="200">
                                            Name
                                        </th>

                                        <td>
                                            ${escapeHtml(
                                                assignment.Name || '-'
                                            )}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Email
                                        </th>

                                        <td>
                                            ${escapeHtml(
                                                assignment.Email || '-'
                                            )}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Progress
                                        </th>

                                        <td>
                                            ${
                                                assignment.progress_percent || 0
                                            }%
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Status
                                        </th>

                                        <td>
                                            ${escapeHtml(
                                                assignment.status || '-'
                                            )}
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        `
                    }

                </div>


                <!-- PROGRESS CARD -->

                <div class="col-md-4">

                    <div class="card shadow-sm">

                        <div class="card-header">
                            <strong>
                                Progress
                            </strong>
                        </div>

                        <div class="card-body">

                            <div class="progress"
                                 style="height: 25px;">

                                <div
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    style="
                                        width:${assignment.progress_percent || 0}%;
                                    "
                                >
                                    ${assignment.progress_percent || 0}%
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <h5 class="mb-3">
                Submission History
            </h5>

        `;


        // ============================================================
        // PROGRESS / SUBMISSION HISTORY
        // ============================================================

        if (
          Array.isArray(json.progress) &&
          json.progress.length > 0
        ) {

          json.progress.forEach(function(item) {

            html += `

                    <div class="card mb-3 shadow-sm">

                        <div class="card-header">

                            <strong>
                                ${escapeHtml(
                                    item.created_at || ''
                                )}
                            </strong>

                        </div>

                        <div class="card-body">

                            <p>
                                <strong>
                                    Progress:
                                </strong>

                                ${item.progress_percent || 0}%
                            </p>

                            <hr>

                            <p>
                                <strong>
                                    Today's Work:
                                </strong>
                            </p>

                            <p>
                                ${escapeHtml(
                                    item.work_summary || '-'
                                )}
                            </p>

                            <hr>

                            <p>
                                <strong>
                                    Issues:
                                </strong>
                            </p>

                            <p>
                                ${escapeHtml(
                                    item.issues || '-'
                                )}
                            </p>

                            <hr>

                            <p class="mb-0">

                                <strong>
                                    Hours Worked:
                                </strong>

                                ${item.hours_worked ?? '-'}

                            </p>

                        </div>

                    </div>

                `;

          });

        } else {

          html += `

                <div class="alert alert-info">

                    No progress updates submitted yet.

                </div>

            `;

        }


        // ============================================================
        // ADMIN REVIEW
        // ============================================================

        html += `

            <hr>

            <h5 class="mb-3">
                Admin Review
            </h5>

            <textarea
                id="reviewText"
                class="form-control"
                rows="5"
                placeholder="Write review..."
            ></textarea>

            <div class="mt-3 text-end">

                <button
                    type="button"
                    class="btn btn-warning me-2"
                    onclick="sendReview(${id})"
                >
                    🔄 Send Back
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    onclick="approveTask(${id})"
                >
                    ✅ Mark Completed
                </button>

            </div>

        `;


        // ============================================================
        // DISPLAY DATA
        // ============================================================

        reviewBody.innerHTML = html;


      } catch (error) {

        console.error('View Task Error:', error);

        const reviewBody =
          document.getElementById('reviewBody');

        if (reviewBody) {

          reviewBody.innerHTML = `

                <div class="alert alert-danger">

                    ${escapeHtml(
                        error.message ||
                        'Unable to load task'
                    )}

                </div>

            `;

        }

      }

    }

    async function deleteTask(id) {

      if (!confirm(
          'Delete this task?\n\nThis cannot be undone.'
        )) {
        return;
      }

      try {

        const formData = new FormData();

        formData.append('action', 'delete');
        formData.append('id', id);

        const response = await fetch(CONTROLLER, {
          method: 'POST',
          body: formData
        });

        const text = await response.text();

        console.log("Delete response:", text);

        let json;

        try {
          json = JSON.parse(text);
        } catch (e) {

          alert(
            'Server returned an invalid response. Check Console.'
          );

          console.error(text);

          return;
        }

        if (json.success) {

          alert('Task deleted successfully.');

          loadTasks();

        } else {

          alert(
            json.message ||
            'Unable to delete task.'
          );

        }

      } catch (error) {

        console.error("deleteTask error:", error);

        alert(
          "Delete failed: " +
          error.message
        );
      }
    }

    async function approveTask(taskId) {

      if (!confirm("Approve this task and mark it completed?")) {
        return;
      }

      try {

        const fd = new FormData();

        fd.append("action", "approve_task");
        fd.append("task_id", taskId);

        const response = await fetch(CONTROLLER, {
          method: "POST",
          body: fd
        });

        const text = await response.text();

        console.log("Approve response:", text);

        let json;

        try {
          json = JSON.parse(text);
        } catch (e) {

          alert("Server returned an invalid response. Check Console.");
          console.error(text);

          return;
        }

        if (json.success) {

          alert("Task Approved Successfully");

          const modalElement =
            document.getElementById("reviewModal");

          const modal =
            bootstrap.Modal.getInstance(modalElement);

          if (modal) {
            modal.hide();
          }

          loadTasks();

        } else {

          alert(
            json.message ||
            "Unable to approve task."
          );

        }

      } catch (error) {

        console.error("approveTask error:", error);

        alert(
          "Approve failed: " +
          error.message
        );
      }
    }
    async function sendReview(taskId) {
      let review = document.getElementById("reviewText").value.trim();

      if (review == "") {
        alert("Enter review");

        return;
      }

      let fd = new FormData();

      fd.append("action", "send_review");

      fd.append("task_id", taskId);

      fd.append("review", review);

      let res = await fetch(CONTROLLER, {
        method: "POST",
        body: fd
      });

      let json = await res.json();

      if (json.success) {

        alert("Review Sent");

        bootstrap.Modal.getInstance(
          document.getElementById("reviewModal")
        ).hide();

        loadTasks();

      }

    }

    // ==================================================================
    // EMPLOYEE WORK UPLOADS
    // Same AJAX pattern, same table/modal markup conventions, and the
    // same "view -> review" flow as Assigned Tasks above, just pointed
    // at employeeworkuploadcontroller.php and its own fields/actions.
    // ==================================================================
    const WORK_CONTROLLER = '../../Controller/employeeworkuploadcontroller.php'; // adjust relative path to match your folder depth
    let currentUploadId = null;

    function reviewBadgeClass(status) {
      return status === 'Approved' ? 'bg-success' :
        status === 'Needs Revision' ? 'bg-warning text-dark' :
        status === 'Rejected' ? 'bg-danger' :
        'bg-secondary'; // Pending
    }

    // ------------------------------------------------------------------
    // LOAD WORK UPLOAD LIST
    // ------------------------------------------------------------------
    async function loadWorkUploads() {

      try {

        const search =
          document.getElementById("uploadSearch")?.value || "";

        const review =
          document.getElementById("reviewFilter")?.value || "";

        const params = new URLSearchParams({
          action: "admin_list",
          search: search,
          review_status: review
        });

        console.log("========== WORK UPLOAD LIST ==========");
        console.log("Request URL:", `${WORK_CONTROLLER}?${params}`);

        const res = await fetch(`${WORK_CONTROLLER}?${params}`, {
          method: "GET",
          cache: "no-store"
        });

        console.log("HTTP Status:", res.status);

        const text = await res.text();

        console.log("Raw Response:", text);

        let json;

        try {

          json = JSON.parse(text);

        } catch (e) {

          console.error("Invalid JSON:", text);

          const tbody = document.getElementById("workUploadTable");

          tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        Server returned an invalid response.
                        Check browser console.
                    </td>
                </tr>
            `;

          return;
        }

        console.log("Parsed Response:", json);

        const tbody =
          document.getElementById("workUploadTable");

        if (!json.success) {

          console.error(
            "Work upload list error:",
            json.message
          );

          tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        ${escapeHtml(
                            json.message || "Unable to load uploads"
                        )}
                    </td>
                </tr>
            `;

          return;
        }

        if (!Array.isArray(json.data) || json.data.length === 0) {

          tbody.innerHTML = `
                <tr>
                    <td colspan="7"
                        class="text-center py-4 text-muted">

                        No uploads found

                    </td>
                </tr>
            `;

          updatePendingUploadsBadge();

          return;
        }

        tbody.innerHTML = json.data.map(item => {

          /*
           * IMPORTANT:
           * This is a WORK UPLOAD.
           *
           * Therefore View MUST call:
           * viewWorkUpload(item.id)
           *
           * NOT:
           * viewTask(item.id)
           */

          return `
                <tr>

                    <!-- Employee -->
                    <td>
                        <a href="#"
                           class="fw-semibold text-primary">
                            ${escapeHtml(
                                item.employee_name || "-"
                            )}
                        </a>

                        <div class="small text-muted">
                            ${escapeHtml(
                                item.StaffCode || ""
                            )}
                        </div>
                    </td>


                    <!-- Title -->
                    <td>
                        ${escapeHtml(
                            item.title || "-"
                        )}
                    </td>


                    <!-- Hours -->
                    <td>
                        ${item.hours_worked ?? "-"}
                    </td>


                    <!-- Status -->
                    <td>
                        ${escapeHtml(
                            item.status || "-"
                        )}
                    </td>


                    <!-- Review -->
                    <td>

                        <span class="badge ${
                            reviewBadgeClass(
                                item.review_status || "Pending"
                            )
                        }">

                            ${escapeHtml(
                                item.review_status || "Pending"
                            )}

                        </span>

                    </td>


                    <!-- Date -->
                    <td>
                        ${escapeHtml(
                            item.created_at || "-"
                        )}
                    </td>


                    <!-- Actions -->
                    <td>

                        <div
                            class="btn-group"
                            role="group"
                        >

                            <!-- =========================
                                 VIEW WORK UPLOAD
                                 ========================= -->

                            <button
                                type="button"
                                class="btn btn-info btn-sm"
                                onclick="viewWorkUpload(${Number(item.id)})"
                                title="View">

                                <i class="bi bi-eye"></i>

                            </button>


                            <!-- =========================
                                 APPROVE
                                 ========================= -->

                            <button
                                type="button"
                                class="btn btn-success btn-sm"
                                onclick="approveWorkUpload(${Number(item.id)})"
                                title="Mark as Completed">

                                <i class="bi bi-check-lg"></i>

                            </button>


                            <!-- =========================
                                 DELETE
                                 ========================= -->

                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="deleteWorkUpload(${Number(item.id)})"
                                title="Delete">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>
            `;

        }).join("");


        updatePendingUploadsBadge();

        console.log("✅ Work uploads loaded successfully");


      } catch (error) {

        console.error(
          "========== WORK UPLOAD LIST ERROR =========="
        );

        console.error(error);

        const tbody =
          document.getElementById("workUploadTable");

        if (tbody) {

          tbody.innerHTML = `
                <tr>
                    <td colspan="7"
                        class="text-center text-danger py-4">

                        ${escapeHtml(
                            error.message ||
                            "Unable to load work uploads"
                        )}

                    </td>
                </tr>
            `;

        }

      }
    }

    async function approveWorkUpload(id) {

      if (!confirm("Mark this work upload as Completed?")) {
        return;
      }

      const formData = new FormData();
      formData.append("action", "approve_upload");
      formData.append("id", id);

      const response = await fetch(WORK_CONTROLLER, {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {

        alert("Work marked as completed successfully.");

        loadWorkUploads();

      } else {

        alert(result.message);

      }

    }
    async function deleteWorkUpload(id) {

      if (!confirm("Are you sure you want to delete this work upload?\n\nThis action cannot be undone.")) {
        return;
      }

      const formData = new FormData();
      formData.append("action", "delete_upload");
      formData.append("id", id);

      const response = await fetch(WORK_CONTROLLER, {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {

        alert("Work upload deleted successfully.");

        loadWorkUploads();

      } else {

        alert(result.message);

      }

    }

    async function updatePendingUploadsBadge() {
      const res = await fetch(`${WORK_CONTROLLER}?action=admin_pending_count`);
      const json = await res.json();
      if (json.success) {
        document.getElementById('pendingUploadsBadge').textContent = `${json.count} Pending`;
      }
    }

    // ------------------------------------------------------------------
    // VIEW — mirrors viewTask(): fills the shared #reviewBody, then the
    // Admin Review section offers Approve / Need Revision / Reject
    // (same footer position/style as Task's Mark Completed / Send Back).
    // ------------------------------------------------------------------
    async function viewWorkUpload(id) {

      try {

        currentUploadId = id;

        const modalElement = document.getElementById('reviewModal');

        if (!modalElement) {
          alert('Review modal not found.');
          return;
        }

        const reviewBody = document.getElementById('reviewBody');

        if (!reviewBody) {
          alert('Review body not found.');
          return;
        }

        /*
         * IMPORTANT:
         * Move modal directly under BODY.
         * This prevents parent containers, overflow,
         * transform and z-index from hiding the modal.
         */
        if (modalElement.parentElement !== document.body) {
          document.body.appendChild(modalElement);
        }

        /*
         * Make sure modal is above backdrop
         */
        modalElement.style.zIndex = '1060';

        /*
         * Show loading content first
         */
        reviewBody.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2">
                    Loading work upload...
                </div>
            </div>
        `;

        /*
         * Open Bootstrap modal
         */
        const modal = bootstrap.Modal.getOrCreateInstance(
          modalElement, {
            backdrop: true,
            keyboard: true
          }
        );

        modal.show();


        // ============================================================
        // FETCH WORK UPLOAD
        // ============================================================

        const res = await fetch(
          `${WORK_CONTROLLER}?action=admin_get&id=${encodeURIComponent(id)}`, {
            method: 'GET',
            cache: 'no-store'
          }
        );

        const text = await res.text();

        let json;

        try {

          json = JSON.parse(text);

        } catch (error) {

          console.error(
            'Work Upload Server Response:',
            text
          );

          reviewBody.innerHTML = `
                <div class="alert alert-danger">

                    <strong>
                        Server returned an invalid response.
                    </strong>

                    <br><br>

                    Check the browser console for details.

                </div>
            `;

          return;
        }


        console.log('Work Upload View Response:', json);


        // ============================================================
        // SERVER ERROR
        // ============================================================

        if (!json.success) {

          reviewBody.innerHTML = `
                <div class="alert alert-danger">
                    ${escapeHtml(
                        json.message || 'Unable to load work upload.'
                    )}
                </div>
            `;

          return;
        }


        // ============================================================
        // DATA
        // ============================================================

        const u = json.data || {};


        // ============================================================
        // FILES
        // ============================================================

        let filesHtml = `
            <div class="text-muted small">
                No files attached.
            </div>
        `;

        if (
          Array.isArray(u.files) &&
          u.files.length > 0
        ) {

          filesHtml = u.files.map(function(f) {

            return `
                    <a
                        href="../../${escapeHtml(f.file_path)}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-sm btn-outline-secondary me-2 mb-2"
                    >
                        <i class="bi bi-paperclip"></i>

                        ${escapeHtml(
                            f.file_name || 'Attachment'
                        )}

                    </a>
                `;

          }).join('');

        }


        // ============================================================
        // LINKS
        // ============================================================

        let linksHtml = `
            <div class="text-muted small">
                No links provided.
            </div>
        `;

        if (
          u.github_link ||
          u.live_url ||
          u.drive_link
        ) {

          linksHtml = `

                ${
                    u.github_link
                    ? `
                        <a
                            href="${escapeHtml(u.github_link)}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-dark me-2 mb-2"
                        >
                            <i class="bi bi-github"></i>
                            GitHub
                        </a>
                    `
                    : ''
                }

                ${
                    u.live_url
                    ? `
                        <a
                            href="${escapeHtml(u.live_url)}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-primary me-2 mb-2"
                        >
                            <i class="bi bi-box-arrow-up-right"></i>
                            Live URL
                        </a>
                    `
                    : ''
                }

                ${
                    u.drive_link
                    ? `
                        <a
                            href="${escapeHtml(u.drive_link)}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-success me-2 mb-2"
                        >
                            <i class="bi bi-folder2-open"></i>
                            Drive
                        </a>
                    `
                    : ''
                }

            `;

        }


        // ============================================================
        // MAIN HTML
        // ============================================================

        let html = `

            <div class="row">

                <div class="col-md-8">

                    <h4 class="mb-3">
                        ${escapeHtml(u.title || '')}
                    </h4>

                    <p>
                        ${escapeHtml(
                            u.description || ''
                        )}
                    </p>

                    <hr>

                    <h5 class="mb-3">
                        Employee
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <tr>
                                <th width="200">
                                    Name
                                </th>

                                <td>
                                    ${escapeHtml(
                                        u.employee_name || '-'
                                    )}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Staff Code
                                </th>

                                <td>
                                    ${escapeHtml(
                                        u.StaffCode || '-'
                                    )}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Email
                                </th>

                                <td>
                                    ${escapeHtml(
                                        u.employee_email || '-'
                                    )}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Category
                                </th>

                                <td>
                                    ${escapeHtml(
                                        u.category || '-'
                                    )}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Status
                                </th>

                                <td>
                                    ${escapeHtml(
                                        u.status || '-'
                                    )}
                                </td>
                            </tr>

                        </table>

                    </div>


                    <!-- EVIDENCE LINKS -->

                    <h6 class="mt-4">
                        Evidence Links
                    </h6>

                    <div class="mb-3">
                        ${linksHtml}
                    </div>


                    <!-- ATTACHMENTS -->

                    <h6 class="mt-4">
                        Attachments
                    </h6>

                    <div class="mb-3">
                        ${filesHtml}
                    </div>


                    <!-- NEXT PLAN -->

                    ${
                        u.next_plan
                        ? `
                            <h6 class="mt-4">
                                Next Plan
                            </h6>

                            <p>
                                ${escapeHtml(
                                    u.next_plan
                                )}
                            </p>
                        `
                        : ''
                    }

                </div>


                <!-- RIGHT SIDE -->

                <div class="col-md-4">

                    <div class="card shadow-sm">

                        <div class="card-header">

                            <strong>
                                Review Status
                            </strong>

                        </div>

                        <div class="card-body">

                            <span
                                class="badge ${reviewBadgeClass(
                                    u.review_status || 'Pending'
                                )}"
                            >
                                ${escapeHtml(
                                    u.review_status || 'Pending'
                                )}
                            </span>

                            <hr>

                            <small class="text-muted">
                                Hours Worked:
                                ${u.hours_worked ?? '-'}
                            </small>

                            <br>

                            <small class="text-muted">
                                Submitted:
                                ${u.created_at ?? '-'}
                            </small>

                            <br>

                            <small class="text-muted">
                                Updated:
                                ${u.updated_at ?? '-'}
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            <hr>


            <!-- ADMIN REVIEW -->

<h5 class="mb-3">
    Admin Review
</h5>

<textarea
    id="workReviewText"
    class="form-control"
    rows="5"
    placeholder="Write review..."
>${escapeHtml(u.review_comment || '')}</textarea>


<div class="mt-3 text-end">

    <!-- REJECT -->
    


    <!-- NEED REVISION -->
    <button
        type="button"
        class="btn btn-warning me-2"
        onclick="reviewWorkUpload(${Number(id)}, 'Needs Revision')"
    >
        🔄 Need Revision
    </button>


    <!-- APPROVE -->
    <button
        type="button"
        class="btn btn-success"
        onclick="approveWorkUpload(${Number(id)})"
    >
        ✅ Approve
    </button>

</div>

        `;


        // ============================================================
        // DISPLAY
        // ============================================================

        reviewBody.innerHTML = html;


        console.log('✅ Work Upload View loaded successfully');


      } catch (error) {

        console.error(
          '========== VIEW WORK UPLOAD ERROR =========='
        );

        console.error(error);

        const reviewBody =
          document.getElementById('reviewBody');

        if (reviewBody) {

          reviewBody.innerHTML = `
                <div class="alert alert-danger">

                    ${escapeHtml(
                        error.message ||
                        'Unable to load work upload.'
                    )}

                </div>
            `;

        }

      }

    }

    // ------------------------------------------------------------------
    // REVIEW — mirrors approveTask()/sendReview(), collapsed into one
    // call since EmployeeWorkUploadModel::reviewUpload() already takes
    // the target status (Approved / Needs Revision / Rejected).
    // ------------------------------------------------------------------
    async function reviewWorkUpload(id, status) {

      const comment = document.getElementById("workReviewText").value.trim();

      if (status !== "Approved" && comment === "") {
        alert("Enter review comment");
        return;
      }

      if (!confirm(`Mark this submission as "${status}"?`)) return;

      let fd = new FormData();

      fd.append("action", "admin_review");
      fd.append("id", id);
      fd.append("review_status", status);
      fd.append("review_comment", comment);

      let res = await fetch(WORK_CONTROLLER, {
        method: "POST",
        body: fd
      });

      let json = await res.json();

      if (json.success) {

        alert("Review Submitted");

        bootstrap.Modal.getInstance(
          document.getElementById("reviewModal")
        ).hide();

        loadWorkUploads();

      } else {
        alert(json.message || 'Something went wrong');
      }
    }

    // ------------------------------------------------------------------
    document.addEventListener("DOMContentLoaded", function() {

      loadTasks();

      loadWorkUploads();

    });
  </script>

</body>

</html>
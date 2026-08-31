<?php
// Admin/View/Task Management System/my-tasks.php
// Employee-facing page. Adjust this include to wherever your employee header/sidebar lives.
// require_once __DIR__ . '/../partials/employee_header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>My Tasks</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background: #f4f6f9; }

    .priority-High, .priority-Urgent { color: #dc3545; font-weight: 600; }
    .priority-Medium { color: #fd7e14; font-weight: 600; }
    .priority-Low { color: #198754; font-weight: 600; }

    .task-card { border-left: 4px solid #dee2e6; }
    .task-card.completed { border-left-color: #198754; }
    .task-card.needs-revision { border-left-color: #dc3545; }
    .task-card.running { border-left-color: #0d6efd; }

    .progress { height: 8px; }

    .revision-banner { background: #fff3f3; color: #dc3545; border: 1px solid #f5c2c7; }
    .history-card { background: #f8f9fa; border: 1px solid #e9ecef; }

    #taskModal .modal-dialog { max-height: 90vh !important; }
    #taskModal .modal-content { max-height: 90vh !important; display: flex !important; flex-direction: column !important; }
    #taskModal .modal-body { overflow-y: auto !important; max-height: calc(90vh - 130px) !important; }

    /* Temporary employee nav - remove once your real header/sidebar is wired in */
    .emp-quicknav { background: #fff; border-bottom: 1px solid #dee2e6; padding: .5rem 1rem; }
    .emp-quicknav a { text-decoration: none; font-size: .9rem; padding: .4rem .8rem; border-radius: 6px; color: #495057; }
    .emp-quicknav a.active { background: #0d6efd; color: #fff; }
    .emp-quicknav a:not(.active):hover { background: #e9ecef; }
  </style>
</head>

<body>

  <div class="emp-quicknav">
    <a href="my-tasks.php" class="active"><i class="bi bi-person-check"></i> My Tasks</a>
    <a href="../Work Upload/work-upload.php"><i class="bi bi-cloud-arrow-up"></i> Work Upload</a>
    <a href="../Work Upload/submission-history.php"><i class="bi bi-clock-history"></i> Submission History</a>
  </div>

  <div class="container py-4">
    <h4 class="mb-3"><i class="bi bi-person-check"></i> My Tasks</h4>

    <div id="taskList" class="row g-3">
      <div class="col-12 text-center text-muted py-4">Loading...</div>
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- UPDATE TASK MODAL -->
  <!-- ============================================================ -->
  <div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalBody">
          Loading...
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const CONTROLLER = '../../Controller/mytaskcontroller.php'; // adjust to match your folder depth
    let currentAssignmentId = null;
    let currentTaskId = null;

    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str ?? '';
      return div.innerHTML;
    }

    function fmtDateTime(dtStr) {
      if (!dtStr) return '-';
      const d = new Date(dtStr.replace(' ', 'T'));
      return d.toLocaleString([], { hour: '2-digit', minute: '2-digit', month: 'short', day: 'numeric' });
    }

    // ------------------------------------------------------------------
    // LOAD MY TASKS
    // ------------------------------------------------------------------
    async function loadMyTasks() {
      const res = await fetch(`${CONTROLLER}?action=my_list`);
      const json = await res.json();
      const wrap = document.getElementById('taskList');

      if (!json.success) {
        wrap.innerHTML = `<div class="col-12 alert alert-danger">${escapeHtml(json.message || 'Failed to load tasks')}</div>`;
        return;
      }
      if (json.data.length === 0) {
        wrap.innerHTML = `<div class="col-12 text-center text-muted py-4">No tasks assigned right now.</div>`;
        return;
      }

      wrap.innerHTML = json.data.map(a => {
        const isCompleted = a.status === 'Completed';
        const needsRevision = a.status === 'Needs Revision';
        const cardClass = isCompleted ? 'completed' : (needsRevision ? 'needs-revision' : (a.status === 'Running' ? 'running' : ''));

        let actionHtml = '';
        if (isCompleted) {
          actionHtml = `<div class="text-success small mt-2"><i class="bi bi-check-circle-fill"></i> Completed — thank you!</div>`;
        } else {
          actionHtml = `<button class="btn btn-sm btn-primary mt-2" onclick="openUpdateModal(${a.assignment_id})">
                      <i class="bi bi-pencil-square"></i> ${needsRevision ? 'Update Again' : 'Update'}
                    </button>`;
        }

        return `
      <div class="col-md-6">
        <div class="card task-card ${cardClass} h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h6 class="card-title mb-1">${escapeHtml(a.title)}</h6>
              <span class="priority-${a.priority} small">${a.priority}</span>
            </div>
            ${a.category_name ? `<span class="badge mb-2" style="background:${a.color_code}">${escapeHtml(a.category_name)}</span>` : ''}
            ${needsRevision ? `<div class="revision-banner rounded p-2 mb-2 small"><i class="bi bi-arrow-repeat"></i> Needs revision — see details when you update.</div>` : ''}
            <div class="progress mb-1">
              <div class="progress-bar" style="width:${a.progress_percent}%"></div>
            </div>
            <small class="text-muted">${a.progress_percent}% complete • Start: ${a.start_date ?? '-'} • Deadline: ${a.end_date ?? '-'}</small>
            ${actionHtml}
          </div>
        </div>
      </div>`;
      }).join('');
    }

    // ------------------------------------------------------------------
    // OPEN UPDATE MODAL
    // ------------------------------------------------------------------
    async function openUpdateModal(assignmentId) {
      currentAssignmentId = assignmentId;
      const modalBody = document.getElementById('modalBody');
      modalBody.innerHTML = 'Loading...';
      new bootstrap.Modal(document.getElementById('taskModal')).show();

      const res = await fetch(`${CONTROLLER}?action=my_get&assignment_id=${assignmentId}`);
      const json = await res.json();
      if (!json.success) {
        modalBody.innerHTML = `<div class="alert alert-danger">${escapeHtml(json.message)}</div>`;
        return;
      }

      const a = json.data;
      currentTaskId = a.task_id;

      let reviewHtml = '';
      if (a.review && a.review.review_text) {
        reviewHtml = `
    <div class="alert alert-warning">
        <h5 class="mb-2"><i class="bi bi-exclamation-triangle-fill"></i> Admin Review</h5>
        <p class="mb-1">${escapeHtml(a.review.review_text)}</p>
        <small class="text-muted">Status: ${a.review.review_status} &nbsp; | &nbsp; Reviewed On: ${a.review.reviewed_on}</small>
    </div>`;
      }

      // "What I did yesterday" — most recent submitted update, shown for context
      // before the employee fills in today's progress.
      let historyHtml = '';
      if (a.last_update) {
        historyHtml = `
    <div class="history-card rounded p-3 mb-3">
      <div class="small text-muted mb-1"><i class="bi bi-clock-history"></i> Your last update — ${fmtDateTime(a.last_update.created_at)}</div>
      <div class="small"><strong>Work done:</strong> ${escapeHtml(a.last_update.work_summary)}</div>
      ${a.last_update.issues ? `<div class="small"><strong>Issues:</strong> ${escapeHtml(a.last_update.issues)}</div>` : ''}
      <div class="small"><strong>Hours:</strong> ${a.last_update.hours_worked ?? '-'} &nbsp; <strong>Progress at the time:</strong> ${a.last_update.progress_percent}%</div>
    </div>`;
      }

      const checklistHtml = a.checklist.length ?
        a.checklist.map(c => `
        <div class="form-check">
          <input class="form-check-input" type="checkbox" disabled ${c.is_completed ? 'checked' : ''}>
          <label class="form-check-label ${c.is_completed ? 'text-decoration-line-through text-muted' : ''}">${escapeHtml(c.item_text)}</label>
        </div>`).join('') :
        '<div class="text-muted small">No checklist items.</div>';

      modalBody.innerHTML = `
    ${reviewHtml}
    <h6>${escapeHtml(a.title)}</h6>
    <p class="text-muted">${escapeHtml(a.description)}</p>

    ${historyHtml}

    <div class="mb-3">
      <strong class="small">Checklist</strong>
      ${checklistHtml}
    </div>

    <form id="progressForm">
      <div class="mb-3">
        <label class="form-label">Progress: <span id="progressLabel">${a.progress_percent}%</span></label>
        <input type="range" class="form-range" min="0" max="100" step="5" name="progress_percent" id="progressRange" value="${a.progress_percent}">
      </div>
      <div class="mb-3">
        <label class="form-label">Today's Work</label>
        <textarea class="form-control" name="work_summary" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Issues (optional)</label>
        <textarea class="form-control" name="issues" rows="2"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Hours Worked</label>
        <input type="number" step="0.5" class="form-control" name="hours_worked">
      </div>
      <div class="mb-3">
        <label class="form-label">Attachment (optional)</label>
        <input type="file" class="form-control" name="attachment">
      </div>
      <button type="submit" class="btn btn-primary w-100">
        ${a.status === 'Needs Revision' ? 'Update Again' : 'Submit Update'}
      </button>
    </form>
  `;

      document.getElementById('progressRange').addEventListener('input', (e) => {
        document.getElementById('progressLabel').textContent = e.target.value + '%';
      });

      document.getElementById('progressForm').addEventListener('submit', submitProgress);
    }

    async function submitProgress(e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      formData.set('action', 'update_progress');
      formData.set('assignment_id', currentAssignmentId);
      formData.set('task_id', currentTaskId);

      const res = await fetch(CONTROLLER, { method: 'POST', body: formData });
      const json = await res.json();

      if (json.success) {
        bootstrap.Modal.getInstance(document.getElementById('taskModal')).hide();
        loadMyTasks();
      } else {
        alert(json.message || 'Something went wrong');
      }
    }

    document.addEventListener('DOMContentLoaded', loadMyTasks);
  </script>

</body>

</html>

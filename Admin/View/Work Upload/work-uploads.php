<?php
// Admin/View/Work Upload/work-uploads.php
// require_once __DIR__ . '/../partials/header.php';
//
// Suggested admin menu (add alongside existing items, do not remove any):
//   Task Management | Work Uploads
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Work Uploads</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background: #f4f6f9; }
    .badge-status { font-size: .75rem; }
    #reviewModal .modal-dialog { max-height: 90vh !important; }
    #reviewModal .modal-content { max-height: 90vh !important; display: flex !important; flex-direction: column !important; }
    #reviewModal .modal-body { overflow-y: auto !important; max-height: calc(90vh - 130px) !important; }

    /* Temporary admin nav - remove once your real header/sidebar is wired in */
    .admin-quicknav {
      background: #fff;
      border-bottom: 1px solid #dee2e6;
      padding: .5rem 1rem;
    }
    .admin-quicknav a {
      text-decoration: none;
      font-size: .9rem;
      padding: .4rem .8rem;
      border-radius: 6px;
      color: #495057;
    }
    .admin-quicknav a.active { background: #0d6efd; color: #fff; }
    .admin-quicknav a:not(.active):hover { background: #e9ecef; }
  </style>
</head>

<body>

  <!-- ============================================================ -->
  <!-- TEMPORARY ADMIN NAV - remove once your real header is wired -->
  <!-- ============================================================ -->
  <div class="admin-quicknav">
    <a href="../Task Management System/task-management.php"><i class="bi bi-list-task"></i> Task Management</a>
    <a href="work-uploads.php" class="active"><i class="bi bi-upload"></i> Work Uploads</a>
  </div>

  <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">
        <i class="bi bi-upload"></i> Work Uploads
        <span id="pendingBadge" class="badge bg-danger d-none ms-2">0 New</span>
      </h4>
    </div>

    <div class="card mb-3">
      <div class="card-body row g-2">
        <div class="col-md-4">
          <input type="text" id="filterSearch" class="form-control" placeholder="Search by title...">
        </div>
        <div class="col-md-3">
          <select id="filterReviewStatus" class="form-select">
            <option value="">All Review Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Needs Revision">Needs Revision</option>
            <option value="Rejected">Rejected</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-secondary w-100" onclick="loadUploads()"><i class="bi bi-search"></i> Filter</button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Employee</th>
              <th>Work Title</th>
              <th>Hours</th>
              <th>Submission Date</th>
              <th>Review Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="uploadsBody">
            <tr><td colspan="6" class="text-center text-muted py-4">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- REVIEW MODAL -->
  <!-- ============================================================ -->
  <div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Review Work Upload</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="reviewBody">Loading...</div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Adjust relative path to match your folder depth
    const CONTROLLER = '../../Controller/employeeworkuploadcontroller.php';

    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str ?? '';
      return div.innerHTML;
    }

    function fmtDate(dtStr) {
      if (!dtStr) return '-';
      const d = new Date(dtStr.replace(' ', 'T'));
      return d.toLocaleString([], { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function reviewBadgeClass(status) {
      switch (status) {
        case 'Approved': return 'bg-success';
        case 'Needs Revision': return 'bg-warning text-dark';
        case 'Rejected': return 'bg-danger';
        default: return 'bg-secondary';
      }
    }

    async function loadPendingBadge() {
      const res = await fetch(`${CONTROLLER}?action=admin_pending_count`);
      const json = await res.json();
      const badge = document.getElementById('pendingBadge');
      if (json.success && json.count > 0) {
        badge.textContent = `${json.count} New Work Upload${json.count > 1 ? 's' : ''} Pending`;
        badge.classList.remove('d-none');
      } else {
        badge.classList.add('d-none');
      }
    }

    async function loadUploads() {
      const params = new URLSearchParams({ action: 'admin_list' });
      const search = document.getElementById('filterSearch').value.trim();
      const reviewStatus = document.getElementById('filterReviewStatus').value;
      if (search) params.set('search', search);
      if (reviewStatus) params.set('review_status', reviewStatus);

      const res = await fetch(`${CONTROLLER}?${params}`);
      const json = await res.json();
      const body = document.getElementById('uploadsBody');

      if (!json.success) {
        body.innerHTML = `<tr><td colspan="6" class="text-danger text-center py-4">${escapeHtml(json.message || 'Failed to load')}</td></tr>`;
        return;
      }
      if (json.data.length === 0) {
        body.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">No work uploads found.</td></tr>`;
        return;
      }

      body.innerHTML = json.data.map(u => `
        <tr>
          <td>${escapeHtml(u.employee_name || '-')}</td>
          <td>${escapeHtml(u.title)}</td>
          <td>${u.hours_worked ?? '-'}</td>
          <td>${fmtDate(u.created_at)}</td>
          <td><span class="badge badge-status ${reviewBadgeClass(u.review_status)}">${escapeHtml(u.review_status)}</span></td>
          <td><button class="btn btn-sm btn-primary" onclick="openReview(${u.id})"><i class="bi bi-eye"></i> Review</button></td>
        </tr>
      `).join('');

      loadPendingBadge();
    }

    async function openReview(id) {
      const modalBody = document.getElementById('reviewBody');
      modalBody.innerHTML = 'Loading...';
      new bootstrap.Modal(document.getElementById('reviewModal')).show();

      const res = await fetch(`${CONTROLLER}?action=admin_get&id=${id}`);
      const json = await res.json();
      if (!json.success) {
        modalBody.innerHTML = `<div class="alert alert-danger">${escapeHtml(json.message)}</div>`;
        return;
      }

      const s = json.data;
      const filesHtml = s.files.length
        ? s.files.map(f => `<a href="/${escapeHtml(f.file_path)}" target="_blank" class="d-block"><i class="bi bi-paperclip"></i> ${escapeHtml(f.file_name)}</a>`).join('')
        : '<span class="text-muted small">No files attached.</span>';

      modalBody.innerHTML = `
        <dl class="row small">
          <dt class="col-sm-3">Employee</dt><dd class="col-sm-9">${escapeHtml(s.employee_name || '-')} ${s.staff_code ? `(${escapeHtml(s.StaffCode)})` : ''}</dd>
          <dt class="col-sm-3">Title</dt><dd class="col-sm-9">${escapeHtml(s.title)}</dd>
          <dt class="col-sm-3">Description</dt><dd class="col-sm-9">${escapeHtml(s.description || '-')}</dd>
          <dt class="col-sm-3">Hours</dt><dd class="col-sm-9">${s.hours_worked ?? '-'}</dd>
          <dt class="col-sm-3">Work Status</dt><dd class="col-sm-9">${escapeHtml(s.status)}</dd>
          <dt class="col-sm-3">GitHub</dt><dd class="col-sm-9">${s.github_link ? `<a href="${escapeHtml(s.github_link)}" target="_blank">${escapeHtml(s.github_link)}</a>` : '-'}</dd>
          <dt class="col-sm-3">Live URL</dt><dd class="col-sm-9">${s.live_url ? `<a href="${escapeHtml(s.live_url)}" target="_blank">${escapeHtml(s.live_url)}</a>` : '-'}</dd>
          <dt class="col-sm-3">Drive Link</dt><dd class="col-sm-9">${s.drive_link ? `<a href="${escapeHtml(s.drive_link)}" target="_blank">${escapeHtml(s.drive_link)}</a>` : '-'}</dd>
          <dt class="col-sm-3">Next Plan</dt><dd class="col-sm-9">${escapeHtml(s.next_plan || '-')}</dd>
          <dt class="col-sm-3">Files</dt><dd class="col-sm-9">${filesHtml}</dd>
          ${s.review_status !== 'Pending' ? `<dt class="col-sm-3">Last Review</dt><dd class="col-sm-9">${escapeHtml(s.review_comment || '-')} <br><small class="text-muted">${escapeHtml(s.review_status)} • ${fmtDate(s.reviewed_at)}</small></dd>` : ''}
        </dl>

        <form id="reviewForm">
          <div class="mb-3">
            <label class="form-label">Review Comment</label>
            <textarea class="form-control" name="review_comment" rows="3" placeholder="Optional comment for the employee"></textarea>
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-success flex-fill" onclick="submitReview(${s.id}, 'Approved')"><i class="bi bi-check-circle"></i> Approve</button>
            <button type="button" class="btn btn-warning flex-fill" onclick="submitReview(${s.id}, 'Needs Revision')"><i class="bi bi-arrow-repeat"></i> Need Revision</button>
            <button type="button" class="btn btn-danger flex-fill" onclick="submitReview(${s.id}, 'Rejected')"><i class="bi bi-x-circle"></i> Reject</button>
          </div>
        </form>
      `;
    }

    async function submitReview(id, reviewStatus) {
      const comment = document.querySelector('#reviewForm textarea[name="review_comment"]').value.trim();
      const formData = new FormData();
      formData.set('action', 'admin_review');
      formData.set('id', id);
      formData.set('review_status', reviewStatus);
      formData.set('review_comment', comment);

      const res = await fetch(CONTROLLER, { method: 'POST', body: formData });
      const json = await res.json();
      if (json.success) {
        bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
        loadUploads();
      } else {
        alert(json.message || 'Something went wrong');
      }
    }

    document.getElementById('filterSearch').addEventListener('keyup', (e) => { if (e.key === 'Enter') loadUploads(); });

    document.addEventListener('DOMContentLoaded', () => {
      loadUploads();
      loadPendingBadge();
    });
  </script>

</body>

</html>
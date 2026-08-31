<?php
// lms/views/student_group_management.php
session_start();
require_once '../model/StudentGroup.php';
require_once '../model/User.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$groupModel = new StudentGroup();
$userModel = new User();
$groups = $groupModel->getAll($_SESSION['user_id']);
$allStudents = $userModel->getAllStudents();
?>



    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Student Group Management</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                        <i class="fas fa-plus"></i> Create New Group
                    </button>
                </div>
                
                <!-- Groups Grid -->
                <div class="row" id="groupsGrid">
                    <?php foreach ($groups as $group): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card group-card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($group['name']); ?></h6>
                                <span class="badge badge-info"><?php echo $group['member_count']; ?> members</span>
                            </div>
                            <div class="card-body">
                                <?php if ($group['description']): ?>
                                <p class="card-text"><?php echo htmlspecialchars($group['description']); ?></p>
                                <?php endif; ?>
                                
                                <p class="card-text">
                                    <strong>Created:</strong> <?php echo date('M d, Y', strtotime($group['created_at'])); ?><br>
                                    <strong>Created by:</strong> <?php echo htmlspecialchars($group['created_by_name'] ?? 'Unknown'); ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-info" onclick="viewGroup(<?php echo $group['id']; ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editGroup(<?php echo $group['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="manageMembers(<?php echo $group['id']; ?>)">
                                        <i class="fas fa-users"></i> Members
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Group Modal -->
    <div class="modal fade" id="createGroupModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createGroupForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="group_name">Group Name *</label>
                            <input type="text" class="form-control" id="group_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="group_description">Description</label>
                            <textarea class="form-control" id="group_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Select Students:</label>
                            <input type="text" class="form-control mb-2" id="studentSearchInput" placeholder="Search students by name or email...">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select_all" onchange="toggleAllStudents()">
                                <label class="form-check-label" for="select_all">
                                    <strong>Select All Students</strong>
                                </label>
                            </div>
                            <div id="studentsList" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($allStudents as $student): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input student-checkbox" name="student_ids[]" value="<?php echo $student['id']; ?>" id="student_<?php echo $student['id']; ?>">
                                    <label class="form-check-label" for="student_<?php echo $student['id']; ?>">
								<?php echo htmlspecialchars($student['name']); ?> 
								<span class="text-muted">(<?php echo htmlspecialchars($student['email']); ?>)</span>
								<?php if (!empty($student['college'])): ?>
									<small class="text-muted ml-1">• <?php echo htmlspecialchars($student['college']); ?></small>
								<?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Manage Members Modal -->
    <div class="modal fade" id="manageMembersModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Group Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="manage_group_id">
                    
                    <div class="row">
                        <div class="col-md-6">
							<h6>Current Members</h6>
							<input type="text" class="form-control mb-2" id="currentMemberSearchInput" placeholder="Search current members...">
							<div id="currentMembers" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                <!-- Current members will be loaded here -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Available Students</h6>
                            <input type="text" class="form-control mb-2" id="availableStudentSearchInput" placeholder="Search available students...">
                            <div id="availableStudents" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                <!-- Available students will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     <?php include 'footer.php'; ?>

    
    <script>
        // Create Group
        $('#createGroupForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '../controller/StudentGroupController.php?action=create&ajax=1',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Group created successfully!');
                        $('#createGroupModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while creating the group.');
                }
            });
        });
        
        // Toggle all students
        function toggleAllStudents() {
            const selectAll = document.getElementById('select_all');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }
        
        // View Group
        function viewGroup(groupId) {
            // This would open a detailed view of the group
            alert('View group functionality will be implemented.');
        }
        
        // Edit Group
        function editGroup(groupId) {
            // This would open an edit form for the group
            alert('Edit group functionality will be implemented.');
        }
        
        // Manage Members
        function manageMembers(groupId) {
            $('#manage_group_id').val(groupId);
            loadGroupMembers(groupId);
            $('#manageMembersModal').modal('show');
        }
        
        // Load group members
        function loadGroupMembers(groupId) {
            $.ajax({
                url: '../controller/StudentGroupController.php?action=get_members&group_id=' + groupId + '&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        displayCurrentMembers(response.members);
                        loadAvailableStudents(groupId, response.members);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while loading group members.');
                }
            });
        }
        
        // Display current members
		function displayCurrentMembers(members) {
            const container = $('#currentMembers');
            container.empty();
            
            if (members.length === 0) {
                container.html('<p class="text-muted">No members in this group.</p>');
                return;
            }
            
			members.forEach(member => {
				const collegeText = member.college && String(member.college).trim() !== '' ? member.college : '—';
				const searchBlob = `${member.name} ${member.email} ${collegeText}`;
				const memberHtml = `
					<div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded current-member-item" data-search="${searchBlob}">
						<div class="current-member-text">
							<strong>${member.name}</strong><br>
							<small class="text-muted">${member.email}</small>
							<small class="text-muted ml-1">• ${collegeText}</small>
						</div>
						<button class="btn btn-sm btn-danger" onclick="removeMember(${member.id})">
							<i class="fas fa-times"></i>
						</button>
					</div>
				`;
				container.append(memberHtml);
			});
			// Reset filter on load
			$('#currentMemberSearchInput').trigger('input');
        }
        
        // Load available students
        function loadAvailableStudents(groupId, currentMembers) {
            const currentMemberIds = currentMembers.map(member => member.id);
            
            $.ajax({
                url: '../controller/StudentGroupController.php?action=get_available_students&group_id=' + groupId + '&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        displayAvailableStudents(response.students);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while loading available students.');
                }
            });
        }
        
        // Display available students
		function displayAvailableStudents(students) {
            const container = $('#availableStudents');
            container.empty();
            
            if (students.length === 0) {
                container.html('<p class="text-muted">No available students.</p>');
                return;
            }
            
			students.forEach(student => {
				const collegeText = student.college && String(student.college).trim() !== '' ? student.college : '—';
				const searchBlob = `${student.name} ${student.email} ${collegeText}`;
				const studentHtml = `
					<div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded available-student-item" data-search="${searchBlob}">
						<div class="available-student-text">
							<strong>${student.name}</strong><br>
							<small class="text-muted">${student.email}</small>
							<small class="text-muted ml-1">• ${collegeText}</small>
						</div>
						<button class="btn btn-sm btn-success" onclick="addMember(${student.id})">
							<i class="fas fa-plus"></i>
						</button>
					</div>
				`;
				container.append(studentHtml);
			});
            // Reset filter on load
            $('#availableStudentSearchInput').trigger('input');
        }

        // Search in create group student list
        $(document).on('input', '#studentSearchInput', function() {
            const query = $(this).val().toLowerCase();
            $('#studentsList .form-check').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(query) !== -1);
            });
        });

let allStudentsCache = [];

// STORE students when loading
function displayAvailableStudents(students) {
    allStudentsCache = students; // cache full list

    renderAvailableStudents(students);
}

// RENDER function (reusable)
function renderAvailableStudents(students) {

    const container = $('#availableStudents');
    container.empty();

    if (students.length === 0) {
        container.html('<p class="text-muted">No students found.</p>');
        return;
    }

    students.forEach(student => {

        const collegeText = student.college && String(student.college).trim() !== '' 
            ? student.college 
            : '—';

        const searchBlob = `${student.name} ${student.email} ${collegeText}`;

        const html = `
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded available-student-item">
                <div class="available-student-text">
                    <strong>${student.name}</strong><br>
                    <small class="text-muted">${student.email}</small>
                    <small class="text-muted ml-1">• ${collegeText}</small>
                </div>
                <button class="btn btn-sm btn-success" onclick="addMember(${student.id})">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        `;

        container.append(html);
    });
}

// SEARCH (REAL FIX)
$(document).on('input', '#availableStudentSearchInput', function () {

    const query = $(this).val().toLowerCase().trim();

    if (query === '') {
        renderAvailableStudents(allStudentsCache);
        return;
    }

    const filtered = allStudentsCache.filter(student => {

        const text = `${student.name} ${student.email} ${student.college || ''}`
            .toLowerCase();

        return text.includes(query);
    });

    renderAvailableStudents(filtered);
});
		// Search in current members (manage members)
		$(document).on('input keyup', '#currentMemberSearchInput', function() {
			const query = String($(this).val() || '').toLowerCase();
			$('#currentMembers .current-member-item').each(function() {
				const blob = String($(this).attr('data-search') || $(this).find('.current-member-text').text()).toLowerCase();
				$(this).toggle(blob.indexOf(query) !== -1);
			});
		});
        
        // Add member to group
        function addMember(studentId) {
            const groupId = $('#manage_group_id').val();
            
            $.ajax({
                url: '../controller/StudentGroupController.php?action=add_member&ajax=1',
                type: 'POST',
                data: {
                    group_id: groupId,
                    user_id: studentId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadGroupMembers(groupId);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while adding the member.');
                }
            });
        }
        
        // Remove member from group
        function removeMember(userId) {
            const groupId = $('#manage_group_id').val();
            
            if (confirm('Are you sure you want to remove this member from the group?')) {
                $.ajax({
                    url: '../controller/StudentGroupController.php?action=remove_member&ajax=1',
                    type: 'POST',
                    data: {
                        group_id: groupId,
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            loadGroupMembers(groupId);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while removing the member.');
                    }
                });
            }
        }
        
        // Delete Group
        function deleteGroup(groupId) {
            if (confirm('Are you sure you want to delete this group? This action cannot be undone.')) {
                $.ajax({
                    url: '../controller/StudentGroupController.php?action=delete&ajax=1',
                    type: 'POST',
                    data: {group_id: groupId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Group deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the group.');
                    }
                });
            }
        }
    </script>


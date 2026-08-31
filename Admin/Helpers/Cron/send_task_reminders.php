<?php
// Cron/send_task_reminders.php
//
// Run this on a schedule via Windows Task Scheduler (WAMP has no built-in cron).
// Recommended: every 15 minutes, e.g.
//   C:\wamp64\bin\php\php8.0.30\php.exe C:\wamp64\www\Admin\Cron\send_task_reminders.php
//
// Behaviour:
//  - Daily reminder: once per day, for every active (non-completed) assignment
//    whose task's start_date..end_date window includes today.
//  - Deadline reminder: sent once, within `reminder_before_minutes` of the
//    task's end_date (treated as end_date 23:59:59) — the "urgent, due today" nudge.
//  - Reminders automatically stop once the assignment is Completed, or once
//    end_date has passed (the queries simply stop matching those rows).

require_once __DIR__ . '/../../model/TaskModel.php';

$daily = TaskModel::getAssignmentsNeedingDailyReminder();
foreach ($daily as $row) {
    $sent = Mailer::taskReminder($row['Email'], $row['Name'], $row['title'], $row['end_date']);
    if ($sent) {
        TaskModel::markReminderSent((int)$row['assignment_id'], 'daily');
    }
}

$deadline = TaskModel::getAssignmentsNeedingDeadlineReminder();
foreach ($deadline as $row) {
    $sent = Mailer::taskDeadlineApproaching($row['Email'], $row['Name'], $row['title'], $row['end_date']);
    if ($sent) {
        TaskModel::markReminderSent((int)$row['assignment_id'], 'deadline');
    }
}

echo "Daily reminders sent: " . count($daily) . "\n";
echo "Deadline reminders sent: " . count($deadline) . "\n";

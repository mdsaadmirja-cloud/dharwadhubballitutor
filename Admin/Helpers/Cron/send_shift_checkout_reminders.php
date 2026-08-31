<?php
// Cron/send_shift_checkout_reminders.php
//
// Run every 5-10 minutes via Windows Task Scheduler:
//   C:\wamp64\bin\php\php8.0.30\php.exe C:\wamp64\www\Admin\Cron\send_shift_checkout_reminders.php
//
// Sends one "your shift ends soon" email per employee per day, `LEAD_MINUTES`
// before their shift's end_time (from the `shifts` table via employees.ShiftId).
//
// ADJUST the table/column names below if your schema differs
// (this assumes: employees(id, Name, Email, ShiftId), shifts(id, end_time TIME)).

require_once __DIR__ . '/../../../DB Operations/dbconnection.php';
require_once __DIR__ . '/../Mailer.php';

const LEAD_MINUTES = 15;

$conn = db();

$rows = $conn->query("
    SELECT e.id AS employee_id, e.Name, e.Email, s.end_time
    FROM employees e
    JOIN shifts s ON s.id = e.ShiftId
    WHERE TIME_TO_SEC(TIMEDIFF(s.end_time, CURTIME())) BETWEEN 0 AND (" . LEAD_MINUTES . " * 60)
      AND NOT EXISTS (
          SELECT 1 FROM shift_checkout_reminder_log l
          WHERE l.employee_id = e.id AND l.log_date = CURDATE()
      )
")->fetch_all(MYSQLI_ASSOC);

$sentCount = 0;
foreach ($rows as $row) {
    $sent = Mailer::shiftCheckoutReminder($row['Email'], $row['Name'], $row['end_time']);
    if ($sent) {
        $stmt = $conn->prepare("INSERT IGNORE INTO shift_checkout_reminder_log (employee_id, log_date) VALUES (?, CURDATE())");
        $stmt->bind_param('i', $row['employee_id']);
        $stmt->execute();
        $sentCount++;
    }
}

echo "Shift checkout reminders sent: {$sentCount}\n";

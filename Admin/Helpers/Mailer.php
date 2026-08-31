<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    // TODO: reuse your existing SMTP settings (e.g. pull from a `task_settings`
    // table like the rest of the Task Management module does) instead of
    // hardcoding here.
    private static array $smtp = [
        'host'       => 'smtp.gmail.com',
        'username'   => 'mrsaadmirjanavar@gmail.com',
        'password'   => 'qmfwxeccegrhfnvo',
        'port'       => 587,
        'from_email' => 'mrsaadmirjanavar@gmail.com',
        'from_name'  => 'DHT Task Management',
    ];

    // Where admin-facing notifications go (task updates, new uploads).
    public const ADMIN_EMAIL = 'sammirja83@gmail.com';
    public const ADMIN_NAME  = 'Admin';

    public static function send(string $toEmail, string $toName, string $subject, string $bodyHtml): bool
    {
        if (empty($toEmail)) return false;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = self::$smtp['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$smtp['username'];
            $mail->Password   = self::$smtp['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = self::$smtp['port'];

            $mail->setFrom(self::$smtp['from_email'], self::$smtp['from_name']);
            $mail->addAddress($toEmail, $toName ?: $toEmail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;
            $mail->AltBody = strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log('Mailer error: ' . $e->getMessage());
            return false;
        }
    }

    private static function wrap(string $title, string $bodyHtml, string $barColor = '#0d6efd'): string
    {
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden'>
            <div style='background:{$barColor};color:#fff;padding:16px 20px;font-size:18px;font-weight:600'>{$title}</div>
            <div style='padding:20px;color:#333;line-height:1.6'>{$bodyHtml}</div>
            <div style='padding:12px 20px;background:#f8f9fa;color:#888;font-size:12px'>DHT Task Management System — automated notification</div>
        </div>";
    }

    // ---------------- Task assignment lifecycle ----------------

    public static function taskAssigned(string $email, string $name, string $title, string $desc, ?string $start, ?string $end): bool
    {
        $body = self::wrap('New Task Assigned', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>You have been assigned a new task:</p>
            <p><strong>" . htmlspecialchars($title) . "</strong></p>
            <p>" . nl2br(htmlspecialchars($desc)) . "</p>
            <p><strong>Start:</strong> " . htmlspecialchars($start ?: '-') . " &nbsp; <strong>Deadline:</strong> " . htmlspecialchars($end ?: '-') . "</p>
            <p>Please log in to Task Management and start updating your progress.</p>
        ");
        return self::send($email, $name, "New Task Assigned: {$title}", $body);
    }

    public static function taskUpdatedNotifyAdmin(string $employeeName, string $title, string $summary): bool
    {
        $body = self::wrap('Task Update Submitted', "
            <p><strong>" . htmlspecialchars($employeeName) . "</strong> submitted an update for task:</p>
            <p><strong>" . htmlspecialchars($title) . "</strong></p>
            <p><em>" . nl2br(htmlspecialchars($summary)) . "</em></p>
            <p>Please review it in the Task Management panel.</p>
        ");
        return self::send(self::ADMIN_EMAIL, self::ADMIN_NAME, "Task Update: {$title}", $body);
    }

    public static function taskApproved(string $email, string $name, string $title): bool
    {
        $body = self::wrap('Task Approved 🎉', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Great work! Your task <strong>" . htmlspecialchars($title) . "</strong> has been reviewed and marked <strong>Completed</strong>.</p>
            <p>Thank you for your effort.</p>
        ", '#198754');
        return self::send($email, $name, "Task Completed: {$title}", $body);
    }

    public static function taskNeedsRevision(string $email, string $name, string $title, string $review): bool
    {
        $body = self::wrap('Task Needs Revision', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Your submission for <strong>" . htmlspecialchars($title) . "</strong> needs some changes before it can be approved.</p>
            <p><strong>Admin review:</strong></p>
            <p style='background:#fff3cd;padding:10px;border-radius:6px'>" . nl2br(htmlspecialchars($review)) . "</p>
            <p>Please make the changes and resubmit.</p>
        ", '#fd7e14');
        return self::send($email, $name, "Revision Needed: {$title}", $body);
    }

    // ---------------- Manual work upload lifecycle ----------------

    public static function uploadNotifyAdmin(string $employeeName, string $title): bool
    {
        $body = self::wrap('New Work Upload', "
            <p><strong>" . htmlspecialchars($employeeName) . "</strong> uploaded new work:</p>
            <p><strong>" . htmlspecialchars($title) . "</strong></p>
            <p>Please review it in the Employee Work Uploads tab.</p>
        ");
        return self::send(self::ADMIN_EMAIL, self::ADMIN_NAME, "New Work Upload: {$title}", $body);
    }

    public static function uploadApproved(string $email, string $name, string $title): bool
    {
        $body = self::wrap('Work Approved 🎉', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Your uploaded work <strong>" . htmlspecialchars($title) . "</strong> has been approved. Thank you!</p>
        ", '#198754');
        return self::send($email, $name, "Work Approved: {$title}", $body);
    }

    public static function uploadReview(string $email, string $name, string $title, string $status, string $comment): bool
    {
        $color = $status === 'Rejected' ? '#dc3545' : '#fd7e14';
        $body = self::wrap("Work {$status}", "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Your uploaded work <strong>" . htmlspecialchars($title) . "</strong> was marked <strong>{$status}</strong>.</p>
            <p><strong>Admin comment:</strong></p>
            <p style='background:#f8d7da;padding:10px;border-radius:6px'>" . nl2br(htmlspecialchars($comment)) . "</p>
        ", $color);
        return self::send($email, $name, "Work {$status}: {$title}", $body);
    }

    // ---------------- Reminders ----------------

    public static function taskReminder(string $email, string $name, string $title, ?string $end): bool
    {
        $body = self::wrap('Task Reminder ⏰', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Just a reminder that your task <strong>" . htmlspecialchars($title) . "</strong> is due on <strong>" . htmlspecialchars($end ?: '-') . "</strong>.</p>
            <p>Please submit today's progress update if you haven't already.</p>
        ");
        return self::send($email, $name, "Reminder: {$title}", $body);
    }

    public static function taskDeadlineApproaching(string $email, string $name, string $title, ?string $end): bool
    {
        $body = self::wrap('Deadline Approaching ⚠️', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Your task <strong>" . htmlspecialchars($title) . "</strong> is due very soon (<strong>" . htmlspecialchars($end ?: '-') . "</strong>).</p>
            <p>Please finish up and submit your update.</p>
        ", '#dc3545');
        return self::send($email, $name, "Deadline Approaching: {$title}", $body);
    }

    public static function shiftCheckoutReminder(string $email, string $name, string $checkoutTime): bool
    {
        $body = self::wrap('Shift Ending Soon', "
            <p>Hi " . htmlspecialchars($name) . ",</p>
            <p>Your shift ends at <strong>" . htmlspecialchars($checkoutTime) . "</strong>. Please submit any pending task updates before you check out.</p>
        ");
        return self::send($email, $name, 'Shift Ending Soon', $body);
    }
}

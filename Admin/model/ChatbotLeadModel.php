<?php

/**
 * ChatbotLead
 * POJO model for a single row in the `chatbot_leads` table.
 * Follows the same get_/set_ convention as Coursesmodel / Enquiry model.
 */
class ChatbotLead
{
    private $id;
    private $name;
    private $phone;
    private $interest;
    private $source;
    private $createdOn;
    private $status;
    private $notes;

    public function __construct(
        $id = null,
        $name = null,
        $phone = null,
        $interest = null,
        $source = null,
        $createdOn = null,
        $status = 'new',
        $notes = ''
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->phone     = $phone;
        $this->interest  = $interest;
        $this->source    = $source;
        $this->createdOn = $createdOn;
        $this->status    = $status ?: 'new';
        $this->notes     = $notes;
    }

    // ---- Getters ----
    public function get_id()
    {
        return $this->id;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_phone()
    {
        return $this->phone;
    }
    public function get_interest()
    {
        return $this->interest;
    }
    public function get_Source()
    {
        return $this->source;
    }
    public function get_createdOn()
    {
        return $this->createdOn;
    }
    public function get_Status()
    {
        return $this->status;
    }
    public function get_notes()
    {
        return $this->notes;
    }

    // ---- Setters ----
    public function set_id($id)
    {
        $this->id = $id;
    }
    public function set_name($name)
    {
        $this->name = $name;
    }
    public function set_phone($phone)
    {
        $this->phone = $phone;
    }
    public function set_interest($interest)
    {
        $this->interest = $interest;
    }
    public function set_Source($source)
    {
        $this->source = $source;
    }
    public function set_createdOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }
    public function set_Status($status)
    {
        $this->status = $status;
    }
    public function set_notes($notes)
    {
        $this->notes = $notes;
    }

    // ---- Small helpers used by the view ----
    public function get_initials()
    {
        if (!$this->name) {
            return '?';
        }
        $parts = preg_split('/\s+/', trim($this->name));
        $s = strtoupper(substr($parts[0], 0, 1));
        if (count($parts) > 1) {
            $s .= strtoupper(substr($parts[count($parts) - 1], 0, 1));
        }
        return $s;
    }

    public function get_phoneDigits()
    {
        return preg_replace('/\D/', '', (string) $this->phone);
    }
}
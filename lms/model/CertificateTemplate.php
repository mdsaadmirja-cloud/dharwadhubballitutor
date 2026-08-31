<?php
// lms/model/CertificateTemplate.php

require_once "../../DB Operations/dbconnection.php";

class CertificateTemplate
{
    private $db;

    public function __construct()
    {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    // Create certificate template
    public function create($data)
    {
        $name = $this->db->real_escape_string($data['name']);
        $template_file = $this->db->real_escape_string($data['template_file']);
        $description = $this->db->real_escape_string($data['description'] ?? '');
        $status = $this->db->real_escape_string($data['status'] ?? 'active');
        $created_by = (int)$data['created_by'];

        $sql = "INSERT INTO certificate_templates
                (name, template_file, description, status, created_by)
                VALUES
                ('$name', '$template_file', '$description', '$status', $created_by)";

        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }

        error_log("Certificate Template Create Error: " . $this->db->error);

        return false;
    }

    // Get certificate template by ID
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT *
                FROM certificate_templates
                WHERE id = $id
                LIMIT 1";

        $result = $this->db->query($sql);

        return $result ? $result->fetch_assoc() : null;
    }

    // Get all certificate templates
    public function getAll($status = null)
    {
        $sql = "SELECT ct.*,
                       u.name AS created_by_name
                FROM certificate_templates ct
                LEFT JOIN users u ON ct.created_by = u.id";

        if ($status !== null) {
            $status = $this->db->real_escape_string($status);
            $sql .= " WHERE ct.status = '$status'";
        }

        $sql .= " ORDER BY ct.created_at DESC";

        $result = $this->db->query($sql);

        $templates = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $templates[] = $row;
            }
        }

        return $templates;
    }

    // Update certificate template
    public function update($id, $data)
    {
        $id = (int)$id;

        $name = $this->db->real_escape_string($data['name']);
        $template_file = $this->db->real_escape_string($data['template_file']);
        $description = $this->db->real_escape_string($data['description'] ?? '');
        $status = $this->db->real_escape_string($data['status'] ?? 'active');

        $sql = "UPDATE certificate_templates SET
                    name = '$name',
                    template_file = '$template_file',
                    description = '$description',
                    status = '$status',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = $id";

        if ($this->db->query($sql)) {
            return true;
        }

        error_log("Certificate Template Update Error: " . $this->db->error);

        return false;
    }

    // Delete certificate template
    public function delete($id)
    {
        $id = (int)$id;

        $sql = "DELETE FROM certificate_templates
                WHERE id = $id";

        if ($this->db->query($sql)) {
            return true;
        }

        error_log("Certificate Template Delete Error: " . $this->db->error);

        return false;
    }

    // Check whether template exists
    public function exists($id)
    {
        $id = (int)$id;

        $sql = "SELECT id
                FROM certificate_templates
                WHERE id = $id
                LIMIT 1";

        $result = $this->db->query($sql);

        return $result && $result->num_rows > 0;
    }

    // Get only active templates
    public function getActive()
    {
        return $this->getAll('active');
    }
}
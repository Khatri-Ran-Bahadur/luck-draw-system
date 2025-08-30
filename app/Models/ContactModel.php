<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = 'contact_submissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'email', 'phone', 'subject', 'message', 'status', 'admin_notes'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
        'subject' => 'required|min_length[5]|max_length[200]',
        'message' => 'required|min_length[10]|max_length[2000]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 2 characters long',
            'max_length' => 'Name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'max_length' => 'Email cannot exceed 100 characters'
        ],
        'subject' => [
            'required' => 'Subject is required',
            'min_length' => 'Subject must be at least 5 characters long',
            'max_length' => 'Subject cannot exceed 200 characters'
        ],
        'message' => [
            'required' => 'Message is required',
            'min_length' => 'Message must be at least 10 characters long',
            'max_length' => 'Message cannot exceed 2000 characters'
        ]
    ];

    public function getPendingSubmissions()
    {
        return $this->where('status', 'pending')->orderBy('created_at', 'DESC')->findAll();
    }

    public function getProcessedSubmissions()
    {
        return $this->whereIn('status', ['replied', 'closed'])->orderBy('updated_at', 'DESC')->findAll();
    }

    public function markAsReplied($id, $adminNotes = '')
    {
        return $this->update($id, [
            'status' => 'replied',
            'admin_notes' => $adminNotes
        ]);
    }

    public function markAsClosed($id, $adminNotes = '')
    {
        return $this->update($id, [
            'status' => 'closed',
            'admin_notes' => $adminNotes
        ]);
    }

    public function getSubmissionStats()
    {
        $total = $this->countAllResults();
        $pending = $this->where('status', 'pending')->countAllResults();
        $replied = $this->where('status', 'replied')->countAllResults();
        $closed = $this->where('status', 'closed')->countAllResults();

        return [
            'total' => $total,
            'pending' => $pending,
            'replied' => $replied,
            'closed' => $closed
        ];
    }
}

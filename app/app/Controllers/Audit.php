<?php

namespace App\Controllers;

class Audit extends BaseController
{
    protected $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        // Check permission
        if (!has_permission('audit.view')) {
            return redirect()->to('/dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }

        // Filters
        $filters = [
            'table' => $this->request->getGet('table'),
            'action' => $this->request->getGet('action'),
            'user_id' => $this->request->getGet('user_id'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
        ];

        // Build query
        $builder = $this->auditLogModel
            ->select('audit_logs.*, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->orderBy('audit_logs.created_at', 'DESC');

        // Apply filters
        if (!empty($filters['table'])) {
            $builder->where('audit_logs.table_name', $filters['table']);
        }

        if (!empty($filters['action'])) {
            $builder->where('audit_logs.action', $filters['action']);
        }

        if (!empty($filters['user_id'])) {
            $builder->where('audit_logs.user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(audit_logs.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(audit_logs.created_at) <=', $filters['date_to']);
        }

        $data['logs'] = $builder->paginate(50);
        $data['pager'] = $this->auditLogModel->pager;
        $data['filters'] = $filters;

        // Get distinct tables and users for filters
        $data['tables'] = $this->auditLogModel
            ->select('table_name')
            ->distinct()
            ->orderBy('table_name', 'ASC')
            ->findAll();

        $userModel = model('UserModel');
        $data['users'] = $userModel
            ->select('id, name')
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('audit/index', $data);
    }

    public function view($id)
    {
        if (!has_permission('audit.view')) {
            return redirect()->to('/audit')
                ->with('error', 'Você não tem permissão para visualizar detalhes.');
        }

        $log = $this->auditLogModel
            ->select('audit_logs.*, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->find($id);

        if (!$log) {
            return redirect()->to('/audit')
                ->with('error', 'Log não encontrado.');
        }

        // Decode JSON data
        $log['old_data_decoded'] = !empty($log['old_data']) ? json_decode($log['old_data'], true) : null;
        $log['new_data_decoded'] = !empty($log['new_data']) ? json_decode($log['new_data'], true) : null;

        return view('audit/view', ['log' => $log]);
    }
}

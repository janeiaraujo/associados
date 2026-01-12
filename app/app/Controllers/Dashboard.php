<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $associadoModel;
    protected $importLogModel;
    protected $auditLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->associadoModel = model('AssociadoModel');
        $this->importLogModel = model('ImportLogModel');
        $this->auditLogModel = model('AuditLogModel');
        $this->userModel = model('UserModel');
    }

    public function index()
    {
        // Estatísticas principais
        $data['statistics'] = $this->associadoModel->getStatistics();
        
        // Distribuição por idade
        $data['ageDistribution'] = $this->associadoModel->getAgeDistribution();
        
        // Últimas importações
        $data['recentImports'] = $this->importLogModel->getRecentImports(5);
        
        // Atividades recentes
        $data['recentActivities'] = $this->auditLogModel->getRecentLogs(10);
        
        // Associados ativos por mês (últimos 6 meses)
        $data['monthlyStats'] = $this->getMonthlyStats();
        
        // Usuários ativos
        $data['activeUsers'] = $this->userModel->where('is_active', 1)->countAllResults();
        
        return view('dashboard/index', $data);
    }

    private function getMonthlyStats(): array
    {
        $stats = [];
        $db = \Config\Database::connect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m-01', strtotime("-$i months"));
            $nextDate = date('Y-m-01', strtotime("-" . ($i - 1) . " months"));
            
            $builder = $db->table('associados');
            $builder->where('status', 'ativo');
            $builder->where('created_at <', $nextDate);
            $count = $builder->countAllResults();
            
            $stats[] = [
                'month' => date('M/y', strtotime($date)),
                'count' => $count
            ];
        }
        
        return $stats;
    }

    public function getChartData()
    {
        // API endpoint para Chart.js
        $type = $this->request->getGet('type');
        
        switch ($type) {
            case 'age':
                $data = $this->associadoModel->getAgeDistribution();
                break;
            case 'monthly':
                $data = $this->getMonthlyStats();
                break;
            case 'status':
                $data = $this->associadoModel->getStatusDistribution();
                break;
            default:
                $data = [];
        }
        
        return $this->response->setJSON($data);
    }
}

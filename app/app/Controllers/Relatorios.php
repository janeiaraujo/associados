<?php

namespace App\Controllers;

class Relatorios extends BaseController
{
    protected $associadoModel;
    protected $reportLogModel;

    public function __construct()
    {
        $this->associadoModel = model('AssociadoModel');
        $this->reportLogModel = model('ReportLogModel');
    }

    public function index()
    {
        // Check permission
        if (!has_permission('relatorios.view')) {
            return redirect()->to('/dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }

        $data['title'] = 'Relatórios';
        $data['recentReports'] = $this->reportLogModel
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return view('relatorios/index', $data);
    }

    public function generate()
    {
        if (!has_permission('relatorios.view')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Você não tem permissão para gerar relatórios.'
            ]);
        }

        $reportType = $this->request->getPost('report_type');
        $filters = $this->request->getPost('filters') ?? [];

        try {
            $data = $this->generateReportData($reportType, $filters);

            // Log report generation
            $this->reportLogModel->insert([
                'user_id' => session()->get('user_id'),
                'report_type' => $reportType,
                'filters' => json_encode($filters),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao gerar relatório: ' . $e->getMessage()
            ]);
        }
    }

    public function export($format = 'xlsx')
    {
        if (!has_permission('relatorios.export')) {
            return redirect()->to('/relatorios')
                ->with('error', 'Você não tem permissão para exportar relatórios.');
        }

        $reportType = $this->request->getGet('report_type');
        $filters = $this->request->getGet('filters') ?? [];

        try {
            $data = $this->generateReportData($reportType, $filters);
            
            if ($format === 'xlsx') {
                return $this->exportExcel($data, $reportType);
            } elseif ($format === 'pdf') {
                return $this->exportPDF($data, $reportType);
            } elseif ($format === 'csv') {
                return $this->exportCSV($data, $reportType);
            }
        } catch (\Exception $e) {
            return redirect()->to('/relatorios')
                ->with('error', 'Erro ao exportar relatório: ' . $e->getMessage());
        }
    }

    private function generateReportData($reportType, $filters)
    {
        switch ($reportType) {
            case 'associados':
                return $this->generateAssociadosReport($filters);
            case 'estatisticas':
                return $this->generateEstatisticasReport($filters);
            case 'aniversariantes':
                return $this->generateAniversariantesReport($filters);
            default:
                throw new \Exception('Tipo de relatório inválido');
        }
    }

    private function generateAssociadosReport($filters)
    {
        $builder = $this->associadoModel
            ->select('associados.*, unidades.nome as unidade, funcoes.nome as funcao')
            ->join('unidades', 'unidades.id = associados.unidade_id', 'left')
            ->join('funcoes', 'funcoes.id = associados.funcao_id', 'left');

        if (!empty($filters['unidade_id'])) {
            $builder->where('associados.unidade_id', $filters['unidade_id']);
        }

        if (!empty($filters['funcao_id'])) {
            $builder->where('associados.funcao_id', $filters['funcao_id']);
        }

        if (!empty($filters['status'])) {
            $builder->where('associados.status', $filters['status']);
        }

        return $builder->findAll();
    }

    private function generateEstatisticasReport($filters)
    {
        return [
            'total' => $this->associadoModel->countAllResults(false),
            'ativos' => $this->associadoModel->where('status', 'ATIVO')->countAllResults(false),
            'inativos' => $this->associadoModel->where('status', 'INATIVO')->countAllResults(),
            'por_unidade' => $this->associadoModel->getCountByUnidade(),
            'por_funcao' => $this->associadoModel->getCountByFuncao(),
            'distribuicao_idade' => $this->associadoModel->getAgeDistribution()
        ];
    }

    private function generateAniversariantesReport($filters)
    {
        $mes = $filters['mes'] ?? date('m');
        
        return $this->associadoModel
            ->select('associados.*, unidades.nome as unidade, funcoes.nome as funcao')
            ->join('unidades', 'unidades.id = associados.unidade_id', 'left')
            ->join('funcoes', 'funcoes.id = associados.funcao_id', 'left')
            ->where('MONTH(data_nascimento)', $mes)
            ->where('status', 'ATIVO')
            ->orderBy('DAY(data_nascimento)', 'ASC')
            ->findAll();
    }

    private function exportExcel($data, $reportType)
    {
        // Implementar exportação Excel usando PhpSpreadsheet
        return redirect()->to('/relatorios')
            ->with('info', 'Exportação Excel em desenvolvimento');
    }

    private function exportPDF($data, $reportType)
    {
        // Implementar exportação PDF
        return redirect()->to('/relatorios')
            ->with('info', 'Exportação PDF em desenvolvimento');
    }

    private function exportCSV($data, $reportType)
    {
        // Implementar exportação CSV
        return redirect()->to('/relatorios')
            ->with('info', 'Exportação CSV em desenvolvimento');
    }
}

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

        $unidadeModel = model('UnidadeModel');
        $funcaoModel = model('FuncaoModel');

        $data['title'] = 'Relatórios';
        $data['unidades'] = $unidadeModel->getAtivas();
        $data['funcoes'] = $funcaoModel->getAtivas();
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

            // Log report export
            $this->reportLogModel->insert([
                'user_id' => session()->get('user_id'),
                'report_type' => $reportType,
                'filters' => json_encode($filters),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
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
        $db = \Config\Database::connect();

        $total = $this->associadoModel->countAllResults(false);
        $ativos = $this->associadoModel->where('status', 'ATIVO')->countAllResults(false);
        $inativos = $this->associadoModel->where('status', 'INATIVO')->countAllResults();

        $porUnidade = $db->table('associados')
            ->select('unidades.nome as unidade, COUNT(*) as total')
            ->join('unidades', 'unidades.id = associados.unidade_id', 'left')
            ->groupBy('associados.unidade_id')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        $porFuncao = $db->table('associados')
            ->select('funcoes.nome as funcao, COUNT(*) as total')
            ->join('funcoes', 'funcoes.id = associados.funcao_id', 'left')
            ->groupBy('associados.funcao_id')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        return [
            'total' => $total,
            'ativos' => $ativos,
            'inativos' => $inativos,
            'por_unidade' => $porUnidade,
            'por_funcao' => $porFuncao,
            'distribuicao_idade' => $this->associadoModel->getAgeDistribution()
        ];
    }

    private function generateAniversariantesReport($filters)
    {
        $mes = (int)($filters['mes'] ?? date('m'));
        
        $db = \Config\Database::connect();
        
        return $db->table('associados')
            ->select('associados.*, unidades.nome as unidade, funcoes.nome as funcao')
            ->join('unidades', 'unidades.id = associados.unidade_id', 'left')
            ->join('funcoes', 'funcoes.id = associados.funcao_id', 'left')
            ->where('MONTH(associados.data_nascimento)', $mes)
            ->where('associados.status', 'ATIVO')
            ->orderBy('DAY(associados.data_nascimento)', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function exportExcel($data, $reportType)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($reportType === 'estatisticas') {
            return $this->exportEstatisticasExcel($spreadsheet, $data);
        }

        // Determine headers and rows based on report type
        if ($reportType === 'aniversariantes') {
            $headers = ['Nome', 'CPF', 'Data Nascimento', 'Unidade', 'Função', 'Email', 'Status'];
            $sheet->setTitle('Aniversariantes');
        } else {
            $headers = ['Nome', 'CPF', 'Data Nascimento', 'Unidade', 'Função', 'Email', 'Telefone', 'Status'];
            $sheet->setTitle('Associados');
        }

        $sheet->fromArray($headers, null, 'A1');

        // Style headers
        $lastCol = chr(64 + count($headers));
        $headerStyle = $sheet->getStyle("A1:{$lastCol}1");
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF0d6efd');
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');

        $row = 2;
        foreach ($data as $item) {
            $rowData = [
                $item['nome'] ?? '',
                isset($item['cpf']) ? format_cpf($item['cpf']) : '',
                isset($item['data_nascimento']) ? format_date($item['data_nascimento']) : '',
                $item['unidade'] ?? '',
                $item['funcao'] ?? '',
                $item['email'] ?? '',
            ];
            if ($reportType !== 'aniversariantes') {
                $rowData[] = $item['telefone'] ?? '';
            }
            $rowData[] = $item['status'] ?? '';
            $sheet->fromArray($rowData, null, 'A' . $row);
            $row++;
        }

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = $reportType . '_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function exportEstatisticasExcel($spreadsheet, $data)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Estatísticas');

        // Resumo geral
        $sheet->setCellValue('A1', 'Relatório Estatístico');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'Total de Associados');
        $sheet->setCellValue('B3', $data['total']);
        $sheet->setCellValue('A4', 'Ativos');
        $sheet->setCellValue('B4', $data['ativos']);
        $sheet->setCellValue('A5', 'Inativos');
        $sheet->setCellValue('B5', $data['inativos']);

        // Por Unidade
        $row = 7;
        $sheet->setCellValue('A' . $row, 'Por Unidade');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (($data['por_unidade'] ?? []) as $item) {
            $sheet->setCellValue('A' . $row, $item['unidade'] ?? 'Sem unidade');
            $sheet->setCellValue('B' . $row, $item['total']);
            $row++;
        }

        // Por Função
        $row++;
        $sheet->setCellValue('A' . $row, 'Por Função');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (($data['por_funcao'] ?? []) as $item) {
            $sheet->setCellValue('A' . $row, $item['funcao'] ?? 'Sem função');
            $sheet->setCellValue('B' . $row, $item['total']);
            $row++;
        }

        // Distribuição por idade
        $row++;
        $sheet->setCellValue('A' . $row, 'Distribuição por Idade');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (($data['distribuicao_idade'] ?? []) as $faixa => $total) {
            $sheet->setCellValue('A' . $row, $faixa);
            $sheet->setCellValue('B' . $row, $total);
            $row++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        $filename = 'estatisticas_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function exportPDF($data, $reportType)
    {
        // Redireciona para Excel por enquanto
        return $this->exportExcel($data, $reportType);
    }

    private function exportCSV($data, $reportType)
    {
        if ($reportType === 'estatisticas') {
            return $this->exportExcel($data, $reportType);
        }

        $filename = $reportType . '_' . date('YmdHis') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if ($reportType === 'aniversariantes') {
            fputcsv($output, ['Nome', 'CPF', 'Data Nascimento', 'Unidade', 'Função', 'Email', 'Status'], ';');
        } else {
            fputcsv($output, ['Nome', 'CPF', 'Data Nascimento', 'Unidade', 'Função', 'Email', 'Telefone', 'Status'], ';');
        }

        foreach ($data as $item) {
            $row = [
                $item['nome'] ?? '',
                isset($item['cpf']) ? format_cpf($item['cpf']) : '',
                isset($item['data_nascimento']) ? format_date($item['data_nascimento']) : '',
                $item['unidade'] ?? '',
                $item['funcao'] ?? '',
                $item['email'] ?? '',
            ];
            if ($reportType !== 'aniversariantes') {
                $row[] = $item['telefone'] ?? '';
            }
            $row[] = $item['status'] ?? '';
            fputcsv($output, $row, ';');
        }

        fclose($output);
        exit;
    }
}

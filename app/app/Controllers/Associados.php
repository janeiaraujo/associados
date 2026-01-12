<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Associados extends BaseController
{
    protected $associadoModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->associadoModel = model('AssociadoModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        // Get filters from request
        $filters = [
            'search' => $this->request->getGet('search'),
            'unidade' => $this->request->getGet('unidade'),
            'funcao' => $this->request->getGet('funcao'),
            'status' => $this->request->getGet('status'),
            'idade_min' => $this->request->getGet('idade_min'),
            'idade_max' => $this->request->getGet('idade_max'),
        ];

        // Get data with pagination
        $data['associados'] = $this->associadoModel->searchAssociados($filters, 20);
        $data['pager'] = $this->associadoModel->pager;
        
        // Get filter options
        $unidadeModel = model('UnidadeModel');
        $funcaoModel = model('FuncaoModel');
        $data['unidades'] = $unidadeModel->getAtivas();
        $data['funcoes'] = $funcaoModel->getAtivas();
        $data['filters'] = $filters;

        return view('associados/index', $data);
    }

    public function create()
    {
        $unidadeModel = model('UnidadeModel');
        $funcaoModel = model('FuncaoModel');
        
        return view('associados/form', [
            'associado' => ['contatos' => []],
            'action' => 'create',
            'unidades' => $unidadeModel->getAtivas(),
            'funcoes' => $funcaoModel->getAtivas(),
            'associadoModel' => $this->associadoModel
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['cpf'] = clean_cpf($data['cpf']);

        // Extract contatos
        $contatos = $this->request->getPost('contatos') ?? [];
        
        // Remove from main data
        unset($data['contatos']);

        if (!$this->associadoModel->save($data)) {
            return redirect()->back()
                ->with('error', 'Erro ao criar associado: ' . implode(', ', $this->associadoModel->errors()))
                ->withInput();
        }

        $associadoId = $this->associadoModel->getInsertID();

        // Save contatos
        $contatoModel = model('AssociadoContatoModel');
        foreach ($contatos as $index => $contato) {
            if (!empty($contato['valor'])) {
                $contatoModel->insert([
                    'associado_id' => $associadoId,
                    'tipo' => $contato['tipo'] ?? 'celular',
                    'valor' => $contato['valor'],
                    'observacao' => $contato['observacao'] ?? null,
                    'principal' => ($index == 0) ? 1 : 0,
                ]);
            }
        }

        // Log action
        $this->auditLogModel->logAction(
            'associados',
            $associadoId,
            'CREATE',
            null,
            $data,
            auth_user_id()
        );

        return redirect()->to('/associados')
            ->with('success', 'Associado criado com sucesso!');
    }

    public function edit($id)
    {
        $associado = $this->associadoModel->getWithRelations($id);

        if (!$associado) {
            return redirect()->to('/associados')
                ->with('error', 'Associado não encontrado.');
        }

        $unidadeModel = model('UnidadeModel');
        $funcaoModel = model('FuncaoModel');

        return view('associados/form', [
            'associado' => $associado,
            'action' => 'edit',
            'unidades' => $unidadeModel->getAtivas(),
            'funcoes' => $funcaoModel->getAtivas(),
            'associadoModel' => $this->associadoModel
        ]);
    }

    public function update($id)
    {
        $associado = $this->associadoModel->find($id);

        if (!$associado) {
            return redirect()->to('/associados')
                ->with('error', 'Associado não encontrado.');
        }

        $data = $this->request->getPost();
        $data['cpf'] = clean_cpf($data['cpf']);

        // Extract contatos
        $contatos = $this->request->getPost('contatos') ?? [];
        
        // Remove from main data
        unset($data['contatos']);

        // Set custom validation rule for CPF update
        $this->associadoModel->setValidationRule('cpf', [
            'label' => 'CPF',
            'rules' => "required|exact_length[11]|is_unique[associados.cpf,id,{$id}]",
            'errors' => [
                'is_unique' => 'Este CPF já está cadastrado.',
                'exact_length' => 'CPF deve ter 11 dígitos.',
            ]
        ]);

        if (!$this->associadoModel->update($id, $data)) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar associado: ' . implode(', ', $this->associadoModel->errors()))
                ->withInput();
        }

        // Update contatos - delete old and insert new
        $contatoModel = model('AssociadoContatoModel');
        $contatoModel->where('associado_id', $id)->delete();
        
        foreach ($contatos as $index => $contato) {
            if (!empty($contato['valor'])) {
                $contatoModel->insert([
                    'associado_id' => $id,
                    'tipo' => $contato['tipo'] ?? 'celular',
                    'valor' => $contato['valor'],
                    'observacao' => $contato['observacao'] ?? null,
                    'principal' => ($index == 0) ? 1 : 0,
                ]);
            }
        }

        // Log action
        $this->auditLogModel->logAction(
            'associados',
            $id,
            'UPDATE',
            $associado,
            array_merge($associado, $data),
            auth_user_id()
        );

        return redirect()->to('/associados')
            ->with('success', 'Associado atualizado com sucesso!');
    }

    public function delete($id)
    {
        $associado = $this->associadoModel->find($id);

        if (!$associado) {
            return redirect()->to('/associados')
                ->with('error', 'Associado não encontrado.');
        }

        if (!$this->associadoModel->delete($id)) {
            return redirect()->to('/associados')
                ->with('error', 'Erro ao excluir associado.');
        }

        // Log action
        $this->auditLogModel->logAction(
            'associados',
            $id,
            'DELETE',
            $associado,
            null,
            auth_user_id()
        );

        return redirect()->to('/associados')
            ->with('success', 'Associado excluído com sucesso!');
    }

    public function view($id)
    {
        $associado = $this->associadoModel->getWithRelations($id);

        if (!$associado) {
            return redirect()->to('/associados')
                ->with('error', 'Associado não encontrado.');
        }

        return view('associados/view', ['associado' => $associado]);
    }

    public function export()
    {
        $format = $this->request->getGet('format') ?? 'xlsx';
        
        // Get all associados with current filters
        $filters = [
            'search' => $this->request->getGet('search'),
            'unidade' => $this->request->getGet('unidade'),
            'funcao' => $this->request->getGet('funcao'),
            'status' => $this->request->getGet('status'),
        ];

        $associados = $this->associadoModel->searchAssociados($filters, 10000);

        if ($format === 'csv') {
            return $this->exportCsv($associados);
        }

        return $this->exportExcel($associados);
    }

    private function exportCsv($associados)
    {
        $filename = 'associados_' . date('YmdHis') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for Excel UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, [
            'ID', 'Nome', 'CPF', 'Data Nascimento', 'Idade',
            'Unidade', 'Função', 'Email', 'Telefone', 'Status'
        ], ';');
        
        // Data
        foreach ($associados as $assoc) {
            fputcsv($output, [
                $assoc['id'],
                $assoc['nome'],
                format_cpf($assoc['cpf']),
                format_date($assoc['data_nascimento']),
                calculate_age($assoc['data_nascimento']),
                $assoc['unidade'],
                $assoc['funcao'],
                $assoc['email'],
                $assoc['telefone'],
                $assoc['status']
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    private function exportExcel($associados)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'ID', 'Nome', 'CPF', 'Data Nascimento', 'Idade',
            'Unidade', 'Função', 'Email', 'Telefone', 'Status'
        ];
        $sheet->fromArray($headers, null, 'A1');
        
        // Style headers
        $headerStyle = $sheet->getStyle('A1:J1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF0d6efd');
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Add data
        $row = 2;
        foreach ($associados as $assoc) {
            $sheet->fromArray([
                $assoc['id'],
                $assoc['nome'],
                format_cpf($assoc['cpf']),
                format_date($assoc['data_nascimento']),
                calculate_age($assoc['data_nascimento']),
                $assoc['unidade'],
                $assoc['funcao'],
                $assoc['email'],
                $assoc['telefone'],
                $assoc['status']
            ], null, 'A' . $row);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Output
        $filename = 'associados_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

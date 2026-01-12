<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Importacao extends BaseController
{
    protected $associadoModel;
    protected $importLogModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->associadoModel = model('AssociadoModel');
        $this->importLogModel = model('ImportLogModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        // Get recent imports
        $data['recentImports'] = $this->importLogModel->getRecentImports(10);
        
        return view('importacao/index', $data);
    }

    public function upload()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'file' => [
                'label' => 'Arquivo',
                'rules' => 'uploaded[file]|ext_in[file,xlsx,xls,csv]|max_size[file,5120]',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('error', 'Erro de validação: ' . implode(', ', $validation->getErrors()))
                ->withInput();
        }

        $file = $this->request->getFile('file');
        
        if (!$file->isValid()) {
            return redirect()->back()
                ->with('error', 'Arquivo inválido.')
                ->withInput();
        }

        try {
            // Move file to writable folder
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $filePath = WRITEPATH . 'uploads/' . $newName;

            // Process file
            $result = $this->processFile($filePath, $file->getClientName());

            // Delete uploaded file
            unlink($filePath);

            // Log import
            $this->importLogModel->insert([
                'file_name' => $file->getClientName(),
                'total_rows' => $result['total'],
                'inserted' => $result['inserted'],
                'updated' => $result['updated'],
                'skipped' => $result['skipped'],
                'user_id' => auth_user_id(),
            ]);

            $message = "Importação concluída! Total: {$result['total']} | Inseridos: {$result['inserted']} | Atualizados: {$result['updated']} | Ignorados: {$result['skipped']}";
            
            if (!empty($result['errors'])) {
                $message .= " | Erros: " . count($result['errors']);
                session()->setFlashdata('import_errors', $result['errors']);
            }

            return redirect()->to('/importacao')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar arquivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processFile(string $filePath, string $fileName): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $result = [
            'total' => 0,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        // Skip header row
        $header = array_shift($rows);
        
        // Validate header
        $expectedColumns = ['nome', 'cpf', 'data_nascimento', 'email', 'telefone', 'unidade', 'funcao', 'status'];
        $headerLower = array_map('strtolower', array_map('trim', $header));
        
        // Check if all required columns exist
        $requiredColumns = ['nome', 'cpf', 'data_nascimento', 'unidade', 'funcao'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $headerLower)) {
                throw new \Exception("Coluna obrigatória não encontrada: {$col}");
            }
        }

        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2; // +2 because we skipped header and array is 0-indexed
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $result['total']++;

            try {
                // Map columns
                $data = [];
                foreach ($headerLower as $colIndex => $colName) {
                    $value = $row[$colIndex] ?? null;
                    
                    // Clean and format data
                    if ($colName === 'cpf') {
                        $value = clean_cpf($value);
                    } elseif ($colName === 'data_nascimento') {
                        // Handle Excel date format
                        if (is_numeric($value)) {
                            $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                        } else {
                            $value = date('Y-m-d', strtotime($value));
                        }
                    } elseif ($colName === 'status') {
                        $value = strtolower(trim($value)) === 'ativo' ? 'ativo' : 'inativo';
                    }
                    
                    if (in_array($colName, $expectedColumns)) {
                        $data[$colName] = $value;
                    }
                }

                // Set default status if not provided
                if (!isset($data['status'])) {
                    $data['status'] = 'ativo';
                }

                // Validate required fields
                if (empty($data['nome']) || empty($data['cpf']) || empty($data['data_nascimento'])) {
                    throw new \Exception("Campos obrigatórios vazios na linha {$lineNumber}");
                }

                // Upsert by CPF
                $upsertResult = $this->associadoModel->upsertByCpf($data);
                
                if ($upsertResult['action'] === 'inserted') {
                    $result['inserted']++;
                    
                    // Log audit
                    $this->auditLogModel->logAction(
                        'associados',
                        $upsertResult['id'],
                        'IMPORT_CREATE',
                        null,
                        $data,
                        auth_user_id()
                    );
                } elseif ($upsertResult['action'] === 'updated') {
                    $result['updated']++;
                    
                    // Log audit
                    $this->auditLogModel->logAction(
                        'associados',
                        $upsertResult['id'],
                        'IMPORT_UPDATE',
                        null,
                        $data,
                        auth_user_id()
                    );
                } else {
                    $result['skipped']++;
                }

            } catch (\Exception $e) {
                $result['skipped']++;
                $result['errors'][] = "Linha {$lineNumber}: " . $e->getMessage();
            }
        }

        return $result;
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = ['Nome', 'CPF', 'Data Nascimento', 'Email', 'Telefone', 'Unidade', 'Função', 'Status'];
        $sheet->fromArray($headers, null, 'A1');

        // Style headers
        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF0d6efd');
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');

        // Example data
        $sheet->fromArray([
            'João da Silva',
            '123.456.789-00',
            '1990-01-15',
            'joao@example.com',
            '(11) 98765-4321',
            'Unidade Centro',
            'Operador',
            'ativo'
        ], null, 'A2');

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add instructions sheet
        $instructionsSheet = $spreadsheet->createSheet();
        $instructionsSheet->setTitle('Instruções');
        $instructionsSheet->setCellValue('A1', 'INSTRUÇÕES DE IMPORTAÇÃO');
        $instructionsSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $instructions = [
            [''],
            ['Campos Obrigatórios:', 'Nome, CPF, Data Nascimento, Unidade, Função'],
            [''],
            ['Formato dos Campos:'],
            ['CPF:', 'Com ou sem formatação (000.000.000-00 ou 00000000000)'],
            ['Data Nascimento:', 'DD/MM/AAAA ou AAAA-MM-DD'],
            ['Status:', 'ativo ou inativo (padrão: ativo se não informado)'],
            [''],
            ['Observações:'],
            ['- Linhas vazias serão ignoradas'],
            ['- Se o CPF já existir, o registro será atualizado'],
            ['- Máximo de 1000 registros por importação'],
            ['- Arquivo deve estar no formato .xlsx, .xls ou .csv'],
        ];
        
        $instructionsSheet->fromArray($instructions, null, 'A3');
        
        // Set active sheet to first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Output
        $filename = 'template_importacao_associados.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

<?php

namespace App\Controllers;

class Configuracoes extends BaseController
{
    protected $configuracaoModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->configuracaoModel = model('ConfiguracaoModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        // Check permission
        if (!has_permission('configuracoes.view')) {
            return redirect()->to('/dashboard')
                ->with('error', 'Você não tem permissão para acessar as configurações.');
        }

        $data['empresa'] = $this->configuracaoModel->getByGroup('empresa');
        $data['endereco'] = $this->configuracaoModel->getByGroup('endereco');
        $data['contato'] = $this->configuracaoModel->getByGroup('contato');
        $data['imagens'] = $this->configuracaoModel->getByGroup('imagens');
        $data['relatorios'] = $this->configuracaoModel->getByGroup('relatorios');

        return view('configuracoes/index', $data);
    }

    public function update()
    {
        if (!has_permission('configuracoes.update')) {
            return redirect()->to('/configuracoes')
                ->with('error', 'Você não tem permissão para alterar as configurações.');
        }

        $configs = $this->request->getPost();
        unset($configs['csrf_test_name']); // Remove CSRF token

        $oldValues = $this->configuracaoModel->getAllIndexed();

        foreach ($configs as $chave => $valor) {
            // Pular campos que não são configurações
            if (in_array($chave, ['_method'])) continue;
            
            $this->configuracaoModel->set($chave, $valor);
        }

        // Log action
        $this->auditLogModel->logAction(
            'configuracoes',
            0,
            'UPDATE',
            $oldValues,
            $configs,
            auth_user_id()
        );

        return redirect()->to('/configuracoes')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function uploadLogo()
    {
        if (!has_permission('configuracoes.update')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Você não tem permissão para alterar as configurações.'
            ]);
        }

        $tipo = $this->request->getPost('tipo') ?? 'logo_principal';
        $file = $this->request->getFile('logo');

        if (!$file || !$file->isValid()) {
            return redirect()->to('/configuracoes')
                ->with('error', 'Erro ao fazer upload da imagem.');
        }

        // Validate file
        $validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $validTypes)) {
            return redirect()->to('/configuracoes')
                ->with('error', 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.');
        }

        // Max 2MB
        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->to('/configuracoes')
                ->with('error', 'A imagem deve ter no máximo 2MB.');
        }

        // Generate filename
        $extension = $file->getExtension();
        $filename = $tipo === 'logo_relatorio' ? 'logo_relatorio.' . $extension : 'logo.' . $extension;
        
        // Move file
        $uploadPath = FCPATH . 'assets/images/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Remove old file if different extension
        $oldLogo = $this->configuracaoModel->get($tipo);
        if ($oldLogo && file_exists(FCPATH . $oldLogo)) {
            $oldExtension = pathinfo($oldLogo, PATHINFO_EXTENSION);
            if ($oldExtension !== $extension) {
                unlink(FCPATH . $oldLogo);
            }
        }

        $file->move($uploadPath, $filename, true);

        // Update config
        $newPath = 'assets/images/' . $filename;
        $this->configuracaoModel->set($tipo, $newPath);

        // Log action
        $this->auditLogModel->logAction(
            'configuracoes',
            0,
            'UPLOAD_LOGO',
            ['tipo' => $tipo, 'old' => $oldLogo],
            ['tipo' => $tipo, 'new' => $newPath],
            auth_user_id()
        );

        return redirect()->to('/configuracoes')
            ->with('success', 'Logo atualizada com sucesso!');
    }
}

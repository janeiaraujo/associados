<?php

namespace App\Controllers;

class Funcoes extends BaseController
{
    protected $funcaoModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->funcaoModel = model('FuncaoModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        $data['funcoes'] = $this->funcaoModel->orderBy('nome', 'ASC')->paginate(20);
        $data['pager'] = $this->funcaoModel->pager;

        return view('funcoes/index', $data);
    }

    public function create()
    {
        return view('funcoes/form', [
            'funcao' => [],
            'action' => 'create'
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->funcaoModel->save($data)) {
            return redirect()->back()
                ->with('error', 'Erro ao criar função: ' . implode(', ', $this->funcaoModel->errors()))
                ->withInput();
        }

        $funcaoId = $this->funcaoModel->getInsertID();

        // Log action
        $this->auditLogModel->logAction(
            'funcoes',
            $funcaoId,
            'CREATE',
            null,
            $data,
            auth_user_id()
        );

        return redirect()->to('/funcoes')
            ->with('success', 'Função criada com sucesso!');
    }

    public function edit($id)
    {
        $funcao = $this->funcaoModel->find($id);

        if (!$funcao) {
            return redirect()->to('/funcoes')
                ->with('error', 'Função não encontrada.');
        }

        return view('funcoes/form', [
            'funcao' => $funcao,
            'action' => 'edit'
        ]);
    }

    public function update($id)
    {
        $funcao = $this->funcaoModel->find($id);

        if (!$funcao) {
            return redirect()->to('/funcoes')
                ->with('error', 'Função não encontrada.');
        }

        $data = $this->request->getPost();

        if (!$this->funcaoModel->update($id, $data)) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar função: ' . implode(', ', $this->funcaoModel->errors()))
                ->withInput();
        }

        // Log action
        $this->auditLogModel->logAction(
            'funcoes',
            $id,
            'UPDATE',
            $funcao,
            array_merge($funcao, $data),
            auth_user_id()
        );

        return redirect()->to('/funcoes')
            ->with('success', 'Função atualizada com sucesso!');
    }

    public function delete($id)
    {
        $funcao = $this->funcaoModel->find($id);

        if (!$funcao) {
            return redirect()->to('/funcoes')
                ->with('error', 'Função não encontrada.');
        }

        if (!$this->funcaoModel->delete($id)) {
            return redirect()->to('/funcoes')
                ->with('error', 'Erro ao excluir função.');
        }

        // Log action
        $this->auditLogModel->logAction(
            'funcoes',
            $id,
            'DELETE',
            $funcao,
            null,
            auth_user_id()
        );

        return redirect()->to('/funcoes')
            ->with('success', 'Função excluída com sucesso!');
    }
}

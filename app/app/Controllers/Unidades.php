<?php

namespace App\Controllers;

class Unidades extends BaseController
{
    protected $unidadeModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->unidadeModel = model('UnidadeModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        $data['unidades'] = $this->unidadeModel->orderBy('nome', 'ASC')->paginate(20);
        $data['pager'] = $this->unidadeModel->pager;

        return view('unidades/index', $data);
    }

    public function create()
    {
        return view('unidades/form', [
            'unidade' => [],
            'action' => 'create'
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->unidadeModel->save($data)) {
            return redirect()->back()
                ->with('error', 'Erro ao criar unidade: ' . implode(', ', $this->unidadeModel->errors()))
                ->withInput();
        }

        $unidadeId = $this->unidadeModel->getInsertID();

        // Log action
        $this->auditLogModel->logAction(
            'unidades',
            $unidadeId,
            'CREATE',
            null,
            $data,
            auth_user_id()
        );

        return redirect()->to('/unidades')
            ->with('success', 'Unidade criada com sucesso!');
    }

    public function edit($id)
    {
        $unidade = $this->unidadeModel->find($id);

        if (!$unidade) {
            return redirect()->to('/unidades')
                ->with('error', 'Unidade não encontrada.');
        }

        return view('unidades/form', [
            'unidade' => $unidade,
            'action' => 'edit'
        ]);
    }

    public function update($id)
    {
        $unidade = $this->unidadeModel->find($id);

        if (!$unidade) {
            return redirect()->to('/unidades')
                ->with('error', 'Unidade não encontrada.');
        }

        $data = $this->request->getPost();

        if (!$this->unidadeModel->update($id, $data)) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar unidade: ' . implode(', ', $this->unidadeModel->errors()))
                ->withInput();
        }

        // Log action
        $this->auditLogModel->logAction(
            'unidades',
            $id,
            'UPDATE',
            $unidade,
            array_merge($unidade, $data),
            auth_user_id()
        );

        return redirect()->to('/unidades')
            ->with('success', 'Unidade atualizada com sucesso!');
    }

    public function delete($id)
    {
        $unidade = $this->unidadeModel->find($id);

        if (!$unidade) {
            return redirect()->to('/unidades')
                ->with('error', 'Unidade não encontrada.');
        }

        if (!$this->unidadeModel->delete($id)) {
            return redirect()->to('/unidades')
                ->with('error', 'Erro ao excluir unidade.');
        }

        // Log action
        $this->auditLogModel->logAction(
            'unidades',
            $id,
            'DELETE',
            $unidade,
            null,
            auth_user_id()
        );

        return redirect()->to('/unidades')
            ->with('success', 'Unidade excluída com sucesso!');
    }
}

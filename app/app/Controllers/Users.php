<?php

namespace App\Controllers;

class Users extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->userModel = model('UserModel');
        $this->roleModel = model('RoleModel');
        $this->auditLogModel = model('AuditLogModel');
    }

    public function index()
    {
        // Check permission
        if (!has_permission('users.view')) {
            return redirect()->to('/dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }

        $data['users'] = $this->userModel
            ->select('users.*, GROUP_CONCAT(roles.name SEPARATOR ", ") as role_name')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->paginate(20);
        
        $data['pager'] = $this->userModel->pager;

        return view('users/index', $data);
    }

    public function create()
    {
        if (!has_permission('users.create')) {
            return redirect()->to('/users')
                ->with('error', 'Você não tem permissão para criar usuários.');
        }

        $data['user'] = [];
        $data['roles'] = $this->roleModel->findAll();
        $data['action'] = 'create';

        return view('users/form', $data);
    }

    public function store()
    {
        if (!has_permission('users.create')) {
            return redirect()->to('/users')
                ->with('error', 'Você não tem permissão para criar usuários.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status') ?? 'ativo',
        ];

        if (!$this->userModel->save($data)) {
            return redirect()->back()
                ->with('error', 'Erro ao criar usuário: ' . implode(', ', $this->userModel->errors()))
                ->withInput();
        }

        $userId = $this->userModel->getInsertID();

        // Log action
        $this->auditLogModel->logAction(
            'users',
            $userId,
            'CREATE',
            null,
            $data,
            auth_user_id()
        );

        return redirect()->to('/users')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($id)
    {
        if (!has_permission('users.update')) {
            return redirect()->to('/users')
                ->with('error', 'Você não tem permissão para editar usuários.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')
                ->with('error', 'Usuário não encontrado.');
        }

        $data['user'] = $user;
        $data['roles'] = $this->roleModel->findAll();
        $data['action'] = 'edit';

        return view('users/form', $data);
    }

    public function update($id)
    {
        if (!has_permission('users.update')) {
            return redirect()->to('/users')
                ->with('error', 'Você não tem permissão para editar usuários.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')
                ->with('error', 'Usuário não encontrado.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status'),
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if (!$this->userModel->update($id, $data)) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar usuário: ' . implode(', ', $this->userModel->errors()))
                ->withInput();
        }

        // Log action
        $this->auditLogModel->logAction(
            'users',
            $id,
            'UPDATE',
            $user,
            array_merge($user, $data),
            auth_user_id()
        );

        return redirect()->to('/users')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function delete($id)
    {
        if (!has_permission('users.delete')) {
            return redirect()->to('/users')
                ->with('error', 'Você não tem permissão para excluir usuários.');
        }

        // Prevent deleting yourself
        if ($id == auth_user_id()) {
            return redirect()->to('/users')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')
                ->with('error', 'Usuário não encontrado.');
        }

        if (!$this->userModel->delete($id)) {
            return redirect()->to('/users')
                ->with('error', 'Erro ao excluir usuário.');
        }

        // Log action
        $this->auditLogModel->logAction(
            'users',
            $id,
            'DELETE',
            $user,
            null,
            auth_user_id()
        );

        return redirect()->to('/users')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}

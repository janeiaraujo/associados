<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;
    protected $auditLogModel;
    protected $passwordResetModel;

    public function __construct()
    {
        $this->userModel = model('UserModel');
        $this->auditLogModel = model('AuditLogModel');
        $this->passwordResetModel = model('PasswordResetModel');
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function loginPost()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUserByEmail($email);

        // Log attempt
        $this->auditLogModel->logAction('users', $user['id'] ?? null, 'LOGIN_ATTEMPT', null, ['email' => $email, 'success' => false], null);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Credenciais inválidas.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Credenciais inválidas.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Sua conta está inativa.');
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session
        session()->regenerate();
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'logged_in' => true,
            'session_regenerated_at' => time(),
        ]);

        // Log successful login
        $this->auditLogModel->logAction('users', $user['id'], 'LOGIN', null, ['email' => $email], $user['id']);

        // Redirect
        $redirectUrl = session()->get('redirect_url') ?? '/dashboard';
        session()->remove('redirect_url');
        
        return redirect()->to($redirectUrl)->with('success', 'Bem-vindo(a), ' . $user['name'] . '!');
    }

    public function logout()
    {
        $userId = session()->get('user_id');
        
        // Log logout
        if ($userId) {
            $this->auditLogModel->logAction('users', $userId, 'LOGOUT', null, null, $userId);
        }

        session()->destroy();
        return redirect()->to('/login')->with('success', 'Você foi desconectado com sucesso.');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function forgotPasswordPost()
    {
        $email = $this->request->getPost('email');
        
        // Always show generic message
        $message = 'Se o e-mail existir, você receberá instruções para redefinir sua senha.';

        $user = $this->userModel->getUserByEmail($email);

        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            
            // Delete old tokens
            $this->passwordResetModel->deleteUserTokens($user['id']);
            
            // Create new token
            $expirationMinutes = (int) env('app.resetTokenExpiration', 30);
            $this->passwordResetModel->createResetToken($user['id'], $token, $expirationMinutes);
            
            // Send email
            $resetLink = base_url("/reset-password?token={$token}");
            
            $emailService = \Config\Services::email();
            $emailService->setTo($user['email']);
            $emailService->setSubject('Redefinição de Senha - Sistema de Associados');
            
            $emailMessage = view('emails/reset_password', [
                'user' => $user,
                'resetLink' => $resetLink,
                'expirationMinutes' => $expirationMinutes,
            ]);
            
            $emailService->setMessage($emailMessage);
            $emailService->send();
            
            // Log
            $this->auditLogModel->logAction('users', $user['id'], 'PASSWORD_RESET_REQUEST', null, null, null);
        }

        return redirect()->back()->with('success', $message);
    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Token inválido.');
        }

        $resetData = $this->passwordResetModel->verifyToken($token);
        
        if (!$resetData) {
            return redirect()->to('/login')->with('error', 'Token inválido ou expirado.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function resetPasswordPost()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]/]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $resetData = $this->passwordResetModel->verifyToken($token);
        
        if (!$resetData) {
            return redirect()->to('/login')->with('error', 'Token inválido ou expirado.');
        }

        // Update password
        $this->userModel->update($resetData['user_id'], [
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        // Mark token as used
        $this->passwordResetModel->markTokenAsUsed($resetData['id']);

        // Log
        $this->auditLogModel->logAction('users', $resetData['user_id'], 'PASSWORD_RESET', null, null, null);

        return redirect()->to('/login')->with('success', 'Senha redefinida com sucesso. Faça login com sua nova senha.');
    }
}

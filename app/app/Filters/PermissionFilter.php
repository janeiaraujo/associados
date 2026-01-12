<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Por favor, faça login para continuar.');
        }

        // Get required permission from arguments
        $requiredPermission = $arguments[0] ?? null;
        
        if (!$requiredPermission) {
            log_message('error', 'Permission filter called without permission argument');
            return redirect()->back()->with('error', 'Erro de configuração de permissão.');
        }

        // Check if user has permission
        $userModel = model('UserModel');
        $userId = $session->get('user_id');
        
        if (!$userModel->hasPermission($userId, $requiredPermission)) {
            // Log unauthorized access attempt
            $auditLogModel = model('AuditLogModel');
            $auditLogModel->logAction(
                'access_control',
                null,
                'UNAUTHORIZED_ACCESS',
                null,
                [
                    'required_permission' => $requiredPermission,
                    'requested_url' => current_url(),
                ],
                $userId
            );
            
            return redirect()->back()->with('error', 'Você não tem permissão para acessar esta funcionalidade.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

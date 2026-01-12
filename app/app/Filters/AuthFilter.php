<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            // Store the intended URL
            $session->set('redirect_url', current_url());
            
            // Redirect to login
            return redirect()->to('/login')->with('error', 'Por favor, faça login para continuar.');
        }

        // Check if user is active
        $userModel = model('UserModel');
        $user = $userModel->find($session->get('user_id'));
        
        if (!$user || !$user['is_active']) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Sua conta está inativa. Entre em contato com o administrador.');
        }

        // Regenerate session ID periodically for security
        if (!$session->get('session_regenerated_at') || 
            (time() - $session->get('session_regenerated_at')) > 300) {
            $session->regenerate();
            $session->set('session_regenerated_at', time());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

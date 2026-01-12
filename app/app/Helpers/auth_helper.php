<?php

if (!function_exists('auth_user_id')) {
    /**
     * Get current logged user ID
     */
    function auth_user_id(): ?int
    {
        return session()->get('user_id');
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get current logged user data
     */
    function auth_user(): ?array
    {
        $userId = auth_user_id();
        
        if (!$userId) {
            return null;
        }

        $userModel = model('UserModel');
        return $userModel->find($userId);
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if current user has permission
     */
    function has_permission(string $permission): bool
    {
        $userId = auth_user_id();
        
        if (!$userId) {
            return false;
        }

        $userModel = model('UserModel');
        return $userModel->hasPermission($userId, $permission);
    }
}

if (!function_exists('format_cpf')) {
    /**
     * Format CPF
     */
    function format_cpf(string $cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        
        return $cpf;
    }
}

if (!function_exists('clean_cpf')) {
    /**
     * Clean CPF (remove formatting)
     */
    function clean_cpf(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }
}

if (!function_exists('calculate_age')) {
    /**
     * Calculate age from birth date
     */
    function calculate_age(string $birthDate): ?int
    {
        try {
            $birth = new DateTime($birthDate);
            $today = new DateTime();
            $age = $today->diff($birth);
            return $age->y;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date to BR format
     */
    function format_date(?string $date): string
    {
        if (!$date) {
            return '-';
        }

        try {
            $dt = new DateTime($date);
            return $dt->format('d/m/Y');
        } catch (Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format datetime to BR format
     */
    function format_datetime(?string $datetime): string
    {
        if (!$datetime) {
            return '-';
        }

        try {
            $dt = new DateTime($datetime);
            return $dt->format('d/m/Y H:i:s');
        } catch (Exception $e) {
            return $datetime;
        }
    }
}

if (!function_exists('generate_token')) {
    /**
     * Generate secure random token
     */
    function generate_token(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}

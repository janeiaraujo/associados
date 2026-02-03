<?php

/**
 * Obtém o valor de uma configuração do sistema
 * 
 * @param string $chave A chave da configuração
 * @param mixed $default Valor padrão se não encontrar
 * @return mixed
 */
if (!function_exists('config_get')) {
    function config_get(string $chave, $default = null)
    {
        static $cache = null;
        
        if ($cache === null) {
            $model = model('ConfiguracaoModel');
            $cache = $model->getAllIndexed();
        }
        
        return $cache[$chave] ?? $default;
    }
}

/**
 * Obtém todos os dados da empresa formatados
 * 
 * @return array
 */
if (!function_exists('empresa_data')) {
    function empresa_data(): array
    {
        static $cache = null;
        
        if ($cache === null) {
            $model = model('ConfiguracaoModel');
            $cache = $model->getEmpresaData();
        }
        
        return $cache;
    }
}

/**
 * Obtém a URL completa da logo principal
 * 
 * @return string
 */
if (!function_exists('logo_url')) {
    function logo_url(): string
    {
        $logo = config_get('logo_principal', 'assets/images/logo.png');
        return base_url($logo);
    }
}

/**
 * Obtém a URL completa da logo para relatórios
 * 
 * @return string
 */
if (!function_exists('logo_relatorio_url')) {
    function logo_relatorio_url(): string
    {
        $logo = config_get('logo_relatorio', 'assets/images/logo.png');
        return base_url($logo);
    }
}

/**
 * Formata CNPJ
 * 
 * @param string $cnpj
 * @return string
 */
if (!function_exists('format_cnpj')) {
    function format_cnpj(string $cnpj): string
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);
        
        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }
        
        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $cnpj
        );
    }
}

/**
 * Formata telefone
 * 
 * @param string $telefone
 * @return string
 */
if (!function_exists('format_telefone')) {
    function format_telefone(string $telefone): string
    {
        $telefone = preg_replace('/\D/', '', $telefone);
        
        if (strlen($telefone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif (strlen($telefone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
        
        return $telefone;
    }
}

/**
 * Formata CEP
 * 
 * @param string $cep
 * @return string
 */
if (!function_exists('format_cep')) {
    function format_cep(string $cep): string
    {
        $cep = preg_replace('/\D/', '', $cep);
        
        if (strlen($cep) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
        }
        
        return $cep;
    }
}

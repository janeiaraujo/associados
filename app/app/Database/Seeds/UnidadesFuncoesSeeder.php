<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UnidadesFuncoesSeeder extends Seeder
{
    public function run()
    {
        // Unidades
        $unidades = [
            ['nome' => 'Porto de Santos', 'descricao' => 'Terminal portuário de Santos', 'ativo' => true],
            ['nome' => 'Terminal TECON', 'descricao' => 'Terminal de Contêineres', 'ativo' => true],
            ['nome' => 'Terminal BTP', 'descricao' => 'Brasil Terminal Portuário', 'ativo' => true],
            ['nome' => 'Administração', 'descricao' => 'Sede administrativa', 'ativo' => true],
            ['nome' => 'Operações', 'descricao' => 'Centro de operações', 'ativo' => true],
        ];

        foreach ($unidades as $unidade) {
            $this->db->table('unidades')->insert($unidade);
        }

        // Funções
        $funcoes = [
            ['nome' => 'Operador Portuário', 'descricao' => 'Operação de equipamentos portuários', 'ativo' => true],
            ['nome' => 'Conferente', 'descricao' => 'Conferência de cargas', 'ativo' => true],
            ['nome' => 'Estivador', 'descricao' => 'Carga e descarga de navios', 'ativo' => true],
            ['nome' => 'Capataz', 'descricao' => 'Supervisão de equipes', 'ativo' => true],
            ['nome' => 'Administrativo', 'descricao' => 'Atividades administrativas', 'ativo' => true],
            ['nome' => 'Motorista', 'descricao' => 'Transporte interno', 'ativo' => true],
            ['nome' => 'Mecânico', 'descricao' => 'Manutenção de equipamentos', 'ativo' => true],
        ];

        foreach ($funcoes as $funcao) {
            $this->db->table('funcoes')->insert($funcao);
        }
    }
}

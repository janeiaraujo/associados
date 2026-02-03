<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfiguracoesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'chave' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'valor' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['text', 'textarea', 'image', 'email', 'tel', 'number'],
                'default' => 'text',
            ],
            'grupo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'geral',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('grupo', false, false, 'idx_grupo');
        $this->forge->createTable('configuracoes');

        // Inserir configurações padrão
        $this->db->table('configuracoes')->insertBatch([
            // Dados da Empresa
            ['chave' => 'empresa_nome', 'valor' => 'STSPPERJ', 'descricao' => 'Nome da Empresa/Sindicato', 'tipo' => 'text', 'grupo' => 'empresa'],
            ['chave' => 'empresa_nome_completo', 'valor' => 'Sindicato dos Trabalhadores em Serviços Portuários e Portuários Avulsos do Estado do Rio de Janeiro', 'descricao' => 'Nome Completo/Razão Social', 'tipo' => 'text', 'grupo' => 'empresa'],
            ['chave' => 'empresa_cnpj', 'valor' => '', 'descricao' => 'CNPJ', 'tipo' => 'text', 'grupo' => 'empresa'],
            ['chave' => 'empresa_inscricao_estadual', 'valor' => '', 'descricao' => 'Inscrição Estadual', 'tipo' => 'text', 'grupo' => 'empresa'],
            ['chave' => 'empresa_inscricao_municipal', 'valor' => '', 'descricao' => 'Inscrição Municipal', 'tipo' => 'text', 'grupo' => 'empresa'],
            
            // Endereço
            ['chave' => 'endereco_logradouro', 'valor' => '', 'descricao' => 'Logradouro', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_numero', 'valor' => '', 'descricao' => 'Número', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_complemento', 'valor' => '', 'descricao' => 'Complemento', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_bairro', 'valor' => '', 'descricao' => 'Bairro', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_cidade', 'valor' => 'Rio de Janeiro', 'descricao' => 'Cidade', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_estado', 'valor' => 'RJ', 'descricao' => 'Estado (UF)', 'tipo' => 'text', 'grupo' => 'endereco'],
            ['chave' => 'endereco_cep', 'valor' => '', 'descricao' => 'CEP', 'tipo' => 'text', 'grupo' => 'endereco'],
            
            // Contato
            ['chave' => 'contato_telefone', 'valor' => '', 'descricao' => 'Telefone Principal', 'tipo' => 'tel', 'grupo' => 'contato'],
            ['chave' => 'contato_telefone2', 'valor' => '', 'descricao' => 'Telefone Secundário', 'tipo' => 'tel', 'grupo' => 'contato'],
            ['chave' => 'contato_celular', 'valor' => '', 'descricao' => 'Celular/WhatsApp', 'tipo' => 'tel', 'grupo' => 'contato'],
            ['chave' => 'contato_email', 'valor' => '', 'descricao' => 'E-mail Principal', 'tipo' => 'email', 'grupo' => 'contato'],
            ['chave' => 'contato_email2', 'valor' => '', 'descricao' => 'E-mail Secundário', 'tipo' => 'email', 'grupo' => 'contato'],
            ['chave' => 'contato_site', 'valor' => '', 'descricao' => 'Website', 'tipo' => 'text', 'grupo' => 'contato'],
            
            // Imagens
            ['chave' => 'logo_principal', 'valor' => 'assets/images/logo.png', 'descricao' => 'Logo Principal', 'tipo' => 'image', 'grupo' => 'imagens'],
            ['chave' => 'logo_relatorio', 'valor' => 'assets/images/logo.png', 'descricao' => 'Logo para Relatórios', 'tipo' => 'image', 'grupo' => 'imagens'],
            
            // Textos para Relatórios
            ['chave' => 'relatorio_cabecalho', 'valor' => '', 'descricao' => 'Texto adicional no cabeçalho dos relatórios', 'tipo' => 'textarea', 'grupo' => 'relatorios'],
            ['chave' => 'relatorio_rodape', 'valor' => '', 'descricao' => 'Texto do rodapé dos relatórios', 'tipo' => 'textarea', 'grupo' => 'relatorios'],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('configuracoes');
    }
}

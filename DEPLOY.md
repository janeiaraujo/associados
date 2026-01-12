# Instruções de Deploy - Servidor de Produção

## Última Atualização: 2026-01-12

### Passos para atualizar o servidor:

1. **Conecte via SSH/FTP ao servidor**

2. **Faça pull das alterações do Git:**
   ```bash
   cd /caminho/do/projeto
   git pull origin main
   ```

3. **Execute as migrations pendentes:**
   ```bash
   cd app
   php spark migrate
   ```

4. **Limpe o cache (se necessário):**
   ```bash
   php spark cache:clear
   ```

5. **Verifique permissões das pastas writable:**
   ```bash
   chmod -R 775 writable/
   ```

### Migrations Pendentes:

- `2026-01-12-201349_UpdateAssociadosAddNewFields.php`
  - Remove: campo `matricula`
  - Adiciona: `registro`, `matricula_sindical`, `tipo_aposentado`

### Alterações Recentes:

- Adicionado timezone America/Sao_Paulo
- Corrigido RewriteBase do .htaccess
- Adicionado controller e view de Relatórios
- Adicionados novos campos ao cadastro de associados
- Corrigido exibição de contatos na view
- Corrigido campo status (is_active) em Users

### Verificação:

Após executar as migrations, teste:
1. Criar novo associado
2. Editar associado existente
3. Visualizar detalhes do associado

---

**Importante:** Sempre faça backup do banco de dados antes de executar migrations em produção!

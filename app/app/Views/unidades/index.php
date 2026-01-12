<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Unidades<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Unidades</h1>
    <a href="<?= base_url('unidades/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Unidade
    </a>
</div>

<!-- Tabela -->
<div class="card shadow-sm">
    <div class="card-body">
        <?php if (!empty($unidades)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($unidades as $unidade): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= esc($unidade['nome']) ?></div>
                            </td>
                            <td><?= esc($unidade['descricao'] ?? '-') ?></td>
                            <td>
                                <?php if ($unidade['ativo']): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= base_url('unidades/edit/' . $unidade['id']) ?>" 
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="confirmarExclusao(<?= $unidade['id'] ?>)" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-3">
                <?= $pager->links() ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">Nenhuma unidade cadastrada.</p>
                <a href="<?= base_url('unidades/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Cadastrar Primeira Unidade
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form de exclusão -->
<form id="formExcluir" method="post" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta unidade?')) {
        const form = document.getElementById('formExcluir');
        form.action = '<?= base_url('unidades/delete') ?>/' + id;
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>

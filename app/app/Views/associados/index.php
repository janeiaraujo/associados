<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Associados<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Associados</h1>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="bi bi-funnel"></i> Filtros
        </button>
        <?php if (has_permission('associados.create')): ?>
            <a href="<?= base_url('associados/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Novo Associado
            </a>
        <?php endif; ?>
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Exportar
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= base_url('associados/export?format=xlsx&' . http_build_query($filters)) ?>">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a></li>
            <li><a class="dropdown-item" href="<?= base_url('associados/export?format=csv&' . http_build_query($filters)) ?>">
                <i class="bi bi-filetype-csv"></i> CSV
            </a></li>
        </ul>
    </div>
</div>

<!-- Filtros -->
<div class="collapse mb-3 <?= !empty(array_filter($filters)) ? 'show' : '' ?>" id="filterCollapse">
    <div class="card">
        <div class="card-body">
            <form method="get" action="<?= base_url('associados') ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Busca</label>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Nome, CPF ou Email" 
                               value="<?= esc($filters['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unidade</label>
                        <select class="form-select" name="unidade">
                            <option value="">Todas</option>
                            <?php foreach ($unidades as $unidade): ?>
                                <option value="<?= esc($unidade['id']) ?>" 
                                        <?= ($filters['unidade'] ?? '') == $unidade['id'] ? 'selected' : '' ?>>
                                    <?= esc($unidade['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Função</label>
                        <select class="form-select" name="funcao">
                            <option value="">Todas</option>
                            <?php foreach ($funcoes as $funcao): ?>
                                <option value="<?= esc($funcao['id']) ?>" 
                                        <?= ($filters['funcao'] ?? '') == $funcao['id'] ? 'selected' : '' ?>>
                                    <?= esc($funcao['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Todos</option>
                            <option value="ATIVO" <?= ($filters['status'] ?? '') === 'ATIVO' ? 'selected' : '' ?>>Ativo</option>
                            <option value="INATIVO" <?= ($filters['status'] ?? '') === 'INATIVO' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Idade</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="idade_min" 
                                   placeholder="Min" value="<?= esc($filters['idade_min'] ?? '') ?>">
                            <span class="input-group-text">-</span>
                            <input type="number" class="form-control" name="idade_max" 
                                   placeholder="Max" value="<?= esc($filters['idade_max'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="<?= base_url('associados') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabela -->
<div class="card shadow-sm">
    <div class="card-body">
        <?php if (!empty($associados)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Unidade</th>
                            <th>Função</th>
                            <th>Idade</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($associados as $associado): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= esc($associado['nome']) ?></div>
                                <?php if (!empty($associado['email'])): ?>
                                    <small class="text-muted"><?= esc($associado['email']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= format_cpf($associado['cpf']) ?></td>
                            <td><?= esc($associado['unidade']) ?></td>
                            <td><?= esc($associado['funcao']) ?></td>
                            <td><?= calculate_age($associado['data_nascimento']) ?> anos</td>
                            <td>
                                <?php if ($associado['status'] === 'ATIVO'): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <?php if (has_permission('associados.view')): ?>
                                        <a href="<?= base_url('associados/view/' . $associado['id']) ?>" 
                                           class="btn btn-outline-primary" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (has_permission('associados.update')): ?>
                                        <a href="<?= base_url('associados/edit/' . $associado['id']) ?>" 
                                           class="btn btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (has_permission('associados.delete')): ?>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Excluir"
                                                onclick="confirmDelete(<?= $associado['id'] ?>, '<?= esc($associado['nome']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
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
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Nenhum associado encontrado</p>
                <?php if (has_permission('associados.create')): ?>
                    <a href="<?= base_url('associados/create') ?>" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Cadastrar Primeiro Associado
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form para delete (hidden) -->
<form id="deleteForm" method="post" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id, name) {
    if (confirm(`Tem certeza que deseja excluir o associado "${name}"?\n\nEsta ação não pode ser desfeita.`)) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= base_url('associados/delete/') ?>' + id;
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>

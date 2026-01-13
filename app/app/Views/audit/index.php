<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Auditoria<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Logs de Auditoria</h1>
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
        <i class="bi bi-funnel"></i> Filtros
    </button>
</div>

<!-- Filtros -->
<div class="collapse mb-3 <?= !empty(array_filter($filters)) ? 'show' : '' ?>" id="filterCollapse">
    <div class="card">
        <div class="card-body">
            <form method="get" action="<?= base_url('audit') ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tabela</label>
                        <select class="form-select" name="table">
                            <option value="">Todas</option>
                            <?php foreach ($tables as $table): ?>
                                <option value="<?= esc($table['table_name']) ?>" 
                                        <?= ($filters['table'] ?? '') == $table['table_name'] ? 'selected' : '' ?>>
                                    <?= esc($table['table_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Ação</label>
                        <select class="form-select" name="action">
                            <option value="">Todas</option>
                            <option value="CREATE" <?= ($filters['action'] ?? '') == 'CREATE' ? 'selected' : '' ?>>Criação</option>
                            <option value="UPDATE" <?= ($filters['action'] ?? '') == 'UPDATE' ? 'selected' : '' ?>>Atualização</option>
                            <option value="DELETE" <?= ($filters['action'] ?? '') == 'DELETE' ? 'selected' : '' ?>>Exclusão</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Usuário</label>
                        <select class="form-select" name="user_id">
                            <option value="">Todos</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= esc($user['id']) ?>" 
                                        <?= ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                    <?= esc($user['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Data Início</label>
                        <input type="date" class="form-control" name="date_from" 
                               value="<?= esc($filters['date_from'] ?? '') ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Data Fim</label>
                        <input type="date" class="form-control" name="date_to" 
                               value="<?= esc($filters['date_to'] ?? '') ?>">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="<?= base_url('audit') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabela -->
<div class="card shadow-sm">
    <div class="card-body">
        <?php if (!empty($logs)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 150px;">Data/Hora</th>
                            <th>Usuário</th>
                            <th>Tabela</th>
                            <th>Ação</th>
                            <th>Registro ID</th>
                            <th style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <small><?= format_datetime($log['created_at']) ?></small>
                            </td>
                            <td><?= esc($log['user_name'] ?? 'Sistema') ?></td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($log['table_name']) ?></span>
                            </td>
                            <td>
                                <?php if ($log['action'] === 'CREATE'): ?>
                                    <span class="badge bg-success">Criação</span>
                                <?php elseif ($log['action'] === 'UPDATE'): ?>
                                    <span class="badge bg-primary">Atualização</span>
                                <?php elseif ($log['action'] === 'DELETE'): ?>
                                    <span class="badge bg-danger">Exclusão</span>
                                <?php else: ?>
                                    <span class="badge bg-info"><?= esc($log['action']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($log['record_id']) ?></td>
                            <td>
                                <a href="<?= base_url('audit/view/' . $log['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($pager->getPageCount() > 1): ?>
                <div class="mt-3">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Nenhum log de auditoria encontrado.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

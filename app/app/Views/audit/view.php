<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Detalhes do Log<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Detalhes do Log de Auditoria</h1>
    <a href="<?= base_url('audit') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Informações Gerais</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="text-muted small">Data/Hora</label>
                <div class="fw-bold"><?= format_datetime($log['created_at']) ?></div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small">Usuário</label>
                <div class="fw-bold"><?= esc($log['user_name'] ?? 'Sistema') ?></div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small">Tabela</label>
                <div><span class="badge bg-secondary"><?= esc($log['table_name']) ?></span></div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small">Ação</label>
                <div>
                    <?php if ($log['action'] === 'CREATE'): ?>
                        <span class="badge bg-success">Criação</span>
                    <?php elseif ($log['action'] === 'UPDATE'): ?>
                        <span class="badge bg-primary">Atualização</span>
                    <?php elseif ($log['action'] === 'DELETE'): ?>
                        <span class="badge bg-danger">Exclusão</span>
                    <?php else: ?>
                        <span class="badge bg-info"><?= esc($log['action']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small">ID do Registro</label>
                <div class="fw-bold"><?= esc($log['record_id']) ?></div>
            </div>

            <div class="col-md-9">
                <label class="text-muted small">IP</label>
                <div class="fw-bold"><?= esc($log['ip_address'] ?? '-') ?></div>
            </div>
        </div>
    </div>
</div>

<?php if ($log['action'] === 'UPDATE' && !empty($log['old_data_decoded']) && !empty($log['new_data_decoded'])): ?>
<div class="card shadow-sm mt-3">
    <div class="card-header bg-light">
        <h5 class="mb-0">Alterações Realizadas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor Anterior</th>
                        <th>Valor Novo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log['new_data_decoded'] as $field => $newValue): ?>
                        <?php 
                        $oldValue = $log['old_data_decoded'][$field] ?? null;
                        if ($oldValue != $newValue):
                        ?>
                        <tr>
                            <td class="fw-bold"><?= esc($field) ?></td>
                            <td>
                                <span class="text-danger"><?= esc($oldValue ?? '-') ?></span>
                            </td>
                            <td>
                                <span class="text-success"><?= esc($newValue ?? '-') ?></span>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($log['action'] === 'CREATE' && !empty($log['new_data_decoded'])): ?>
<div class="card shadow-sm mt-3">
    <div class="card-header bg-light">
        <h5 class="mb-0">Dados Criados</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log['new_data_decoded'] as $field => $value): ?>
                    <tr>
                        <td class="fw-bold"><?= esc($field) ?></td>
                        <td><?= esc($value ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($log['action'] === 'DELETE' && !empty($log['old_data_decoded'])): ?>
<div class="card shadow-sm mt-3">
    <div class="card-header bg-light">
        <h5 class="mb-0">Dados Excluídos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log['old_data_decoded'] as $field => $value): ?>
                    <tr>
                        <td class="fw-bold"><?= esc($field) ?></td>
                        <td><?= esc($value ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

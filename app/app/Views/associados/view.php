<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Detalhes do Associado<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detalhes do Associado</h1>
            <div class="btn-group">
                <?php if (has_permission('associados.update')): ?>
                    <a href="<?= base_url('associados/edit/' . $associado['id']) ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('associados') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?= esc($associado['nome']) ?></h5>
                    <?php if ($associado['status'] === 'ATIVO'): ?>
                        <span class="badge bg-success">Ativo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inativo</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">CPF</label>
                        <div class="fw-bold"><?= format_cpf($associado['cpf']) ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">REGISTRO</label>
                        <div class="fw-bold">
                            <?= !empty($associado['registro']) ? esc($associado['registro']) : '<span class="text-muted">-</span>' ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Matrícula Sindical</label>
                        <div class="fw-bold">
                            <?= !empty($associado['matricula_sindical']) ? esc($associado['matricula_sindical']) : '<span class="text-muted">-</span>' ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Data de Nascimento</label>
                        <div class="fw-bold">
                            <?= format_date($associado['data_nascimento']) ?>
                            <span class="text-muted">(<?= calculate_age($associado['data_nascimento']) ?> anos)</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <div class="fw-bold">
                            <?php if (!empty($associado['email'])): ?>
                                <a href="mailto:<?= esc($associado['email']) ?>"><?= esc($associado['email']) ?></a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Unidade</label>
                        <div class="fw-bold"><?= esc($associado['unidade']) ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Função</label>
                        <div class="fw-bold"><?= esc($associado['funcao']) ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Tipo de Aposentadoria</label>
                        <div class="fw-bold">
                            <?php 
                            $tipo = $associado['tipo_aposentado'] ?? 'NAO_APOSENTADO';
                            if ($tipo === 'CLT'): ?>
                                <span class="badge bg-info">Aposentado CLT</span>
                            <?php elseif ($tipo === 'PENSIONISTA'): ?>
                                <span class="badge bg-warning">Aposentado Pensionista</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Não Aposentado</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contatos -->
                    <div class="col-12">
                        <label class="text-muted small">Contatos</label>
                        <?php if (!empty($associado['contatos'])): ?>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($associado['contatos'] as $contato): ?>
                                    <div class="badge bg-info text-dark">
                                        <i class="bi bi-<?= $contato['tipo'] === 'telefone' ? 'telephone' : ($contato['tipo'] === 'email' ? 'envelope' : 'chat-dots') ?>"></i>
                                        <?= esc($contato['valor']) ?>
                                        <span class="ms-1">(<?= ucfirst($contato['tipo']) ?>)</span>
                                        <?php if ($contato['principal']): ?>
                                            <span class="ms-1">★</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">-</div>
                        <?php endif; ?>
                    </div>

                    <!-- Endereço -->
                    <div class="col-12">
                        <label class="text-muted small">Endereço</label>
                        <?php if (!empty($associado['endereco_logradouro']) || !empty($associado['endereco_cep'])): ?>
                            <div class="border rounded p-3">
                                <div>
                                    <?= esc($associado['endereco_logradouro'] ?? '') ?>
                                    <?= !empty($associado['endereco_numero']) ? ', ' . esc($associado['endereco_numero']) : '' ?>
                                    <?= !empty($associado['endereco_complemento']) ? ' - ' . esc($associado['endereco_complemento']) : '' ?>
                                </div>
                                <div class="text-muted small">
                                    <?= esc($associado['endereco_bairro'] ?? '') ?> 
                                    <?= !empty($associado['endereco_cidade']) ? ' - ' . esc($associado['endereco_cidade']) : '' ?>
                                    <?= !empty($associado['endereco_estado']) ? '/' . strtoupper(esc($associado['endereco_estado'])) : '' ?>
                                    <?= !empty($associado['endereco_cep']) ? ' - CEP: ' . esc($associado['endereco_cep']) : '' ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">-</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Cadastrado em</label>
                        <div class="fw-bold"><?= format_datetime($associado['created_at']) ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Última Atualização</label>
                        <div class="fw-bold"><?= format_datetime($associado['updated_at']) ?></div>
                    </div>
                </div>
            </div>
            <?php if (has_permission('associados.update') || has_permission('associados.delete')): ?>
            <div class="card-footer bg-white">
                <div class="d-flex gap-2 justify-content-end">
                    <?php if (has_permission('associados.update')): ?>
                        <a href="<?= base_url('associados/edit/' . $associado['id']) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    <?php endif; ?>
                    <?php if (has_permission('associados.delete')): ?>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete(<?= $associado['id'] ?>, '<?= esc($associado['nome']) ?>')">
                            <i class="bi bi-trash"></i> Excluir
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
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

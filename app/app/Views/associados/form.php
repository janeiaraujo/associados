<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= $action === 'create' ? 'Novo Associado' : 'Editar Associado' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <?= $action === 'create' ? 'Novo Associado' : 'Editar Associado' ?>
            </h1>
            <a href="<?= base_url('associados') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= $action === 'create' ? base_url('associados/store') : base_url('associados/update/' . $associado['id']) ?>">
                    <?= csrf_field() ?>
                    
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif; ?>

                    <div class="row g-3">
                        <!-- Nome -->
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.nome') ? 'is-invalid' : '' ?>" 
                                   id="nome" name="nome" 
                                   value="<?= old('nome', $associado['nome'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.nome')): ?>
                                <div class="invalid-feedback"><?= session('errors.nome') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-6">
                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.cpf') ? 'is-invalid' : '' ?>" 
                                   id="cpf" name="cpf" 
                                   value="<?= old('cpf', isset($associado['cpf']) ? format_cpf($associado['cpf']) : '') ?>" 
                                   maxlength="14"
                                   required>
                            <?php if (session('errors.cpf')): ?>
                                <div class="invalid-feedback"><?= session('errors.cpf') ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Formato: 000.000.000-00</small>
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="col-md-4">
                            <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?= session('errors.data_nascimento') ? 'is-invalid' : '' ?>" 
                                   id="data_nascimento" name="data_nascimento" 
                                   value="<?= old('data_nascimento', $associado['data_nascimento'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.data_nascimento')): ?>
                                <div class="invalid-feedback"><?= session('errors.data_nascimento') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="col-md-8">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" 
                                   value="<?= old('email', $associado['email'] ?? '') ?>">
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Telefone -->
                        <div class="col-md-4">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control <?= session('errors.telefone') ? 'is-invalid' : '' ?>" 
                                   id="telefone" name="telefone" 
                                   value="<?= old('telefone', $associado['telefone'] ?? '') ?>"
                                   maxlength="15">
                            <?php if (session('errors.telefone')): ?>
                                <div class="invalid-feedback"><?= session('errors.telefone') ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">(00) 00000-0000</small>
                        </div>

                        <!-- Unidade -->
                        <div class="col-md-4">
                            <label for="unidade" class="form-label">Unidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.unidade') ? 'is-invalid' : '' ?>" 
                                   id="unidade" name="unidade" 
                                   value="<?= old('unidade', $associado['unidade'] ?? '') ?>"
                                   list="unidades"
                                   required>
                            <datalist id="unidades"></datalist>
                            <?php if (session('errors.unidade')): ?>
                                <div class="invalid-feedback"><?= session('errors.unidade') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Função -->
                        <div class="col-md-4">
                            <label for="funcao" class="form-label">Função <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.funcao') ? 'is-invalid' : '' ?>" 
                                   id="funcao" name="funcao" 
                                   value="<?= old('funcao', $associado['funcao'] ?? '') ?>"
                                   list="funcoes"
                                   required>
                            <datalist id="funcoes"></datalist>
                            <?php if (session('errors.funcao')): ?>
                                <div class="invalid-feedback"><?= session('errors.funcao') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Status -->
                        <div class="col-md-12">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusAtivo" value="ativo" 
                                           <?= old('status', $associado['status'] ?? 'ativo') === 'ativo' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusAtivo">
                                        <span class="badge bg-success">Ativo</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusInativo" value="inativo" 
                                           <?= old('status', $associado['status'] ?? 'ativo') === 'inativo' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusInativo">
                                        <span class="badge bg-danger">Inativo</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="col-12">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control <?= session('errors.observacoes') ? 'is-invalid' : '' ?>" 
                                      id="observacoes" name="observacoes" 
                                      rows="3"><?= old('observacoes', $associado['observacoes'] ?? '') ?></textarea>
                            <?php if (session('errors.observacoes')): ?>
                                <div class="invalid-feedback"><?= session('errors.observacoes') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                        <a href="<?= base_url('associados') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> 
                            <?= $action === 'create' ? 'Cadastrar' : 'Atualizar' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Máscara de CPF
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    }
});

// Máscara de Telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
    }
});

// Buscar unidades e funções para datalist
fetch('<?= base_url('associados') ?>')
    .then(() => {
        // Implementar busca de unidades e funções via API se necessário
    });
</script>
<?= $this->endSection() ?>

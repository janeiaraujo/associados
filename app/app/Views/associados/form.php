<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= $action === 'create' ? 'Novo Associado' : 'Editar Associado' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <?= $action === 'create' ? 'Novo Associado' : 'Editar Associado' ?>
            </h1>
            <a href="<?= base_url('associados') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <form method="post" action="<?= $action === 'create' ? base_url('associados/store') : base_url('associados/update/' . $associado['id']) ?>" id="formAssociado">
            <?= csrf_field() ?>
            
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <!-- Dados Pessoais -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Dados Pessoais</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.nome') ? 'is-invalid' : '' ?>" 
                                   id="nome" name="nome" 
                                   value="<?= old('nome', $associado['nome'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.nome')): ?>
                                <div class="invalid-feedback"><?= session('errors.nome') ?></div>
                            <?php endif; ?>
                        </div>

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

                        <div class="col-md-4">
                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.cpf') ? 'is-invalid' : '' ?>" 
                                   id="cpf" name="cpf" 
                                   value="<?= old('cpf', isset($associado['cpf']) ? format_cpf($associado['cpf']) : '') ?>" 
                                   maxlength="14"
                                   required>
                            <?php if (session('errors.cpf')): ?>
                                <div class="invalid-feedback"><?= session('errors.cpf') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input type="text" class="form-control <?= session('errors.matricula') ? 'is-invalid' : '' ?>" 
                                   id="matricula" name="matricula" 
                                   value="<?= old('matricula', $associado['matricula'] ?? '') ?>">
                            <?php if (session('errors.matricula')): ?>
                                <div class="invalid-feedback"><?= session('errors.matricula') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" 
                                   value="<?= old('email', $associado['email'] ?? '') ?>">
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados Profissionais -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase"></i> Dados Profissionais</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="unidade_id" class="form-label">Unidade <span class="text-danger">*</span></label>
                            <select class="form-select <?= session('errors.unidade_id') ? 'is-invalid' : '' ?>" 
                                    id="unidade_id" name="unidade_id" required>
                                <option value="">Selecione uma unidade</option>
                                <?php foreach ($unidades as $unidade): ?>
                                    <option value="<?= esc($unidade['id']) ?>" 
                                            <?= old('unidade_id', $associado['unidade_id'] ?? '') == $unidade['id'] ? 'selected' : '' ?>>
                                        <?= esc($unidade['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.unidade_id')): ?>
                                <div class="invalid-feedback"><?= session('errors.unidade_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="funcao_id" class="form-label">Função <span class="text-danger">*</span></label>
                            <select class="form-select <?= session('errors.funcao_id') ? 'is-invalid' : '' ?>" 
                                    id="funcao_id" name="funcao_id" required>
                                <option value="">Selecione uma função</option>
                                <?php foreach ($funcoes as $funcao): ?>
                                    <option value="<?= esc($funcao['id']) ?>" 
                                            <?= old('funcao_id', $associado['funcao_id'] ?? '') == $funcao['id'] ? 'selected' : '' ?>>
                                        <?= esc($funcao['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.funcao_id')): ?>
                                <div class="invalid-feedback"><?= session('errors.funcao_id') ?></div>
                            <?php endif; ?>
                        </div>

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
                    </div>
                </div>
            </div>

            <!-- Telefones -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-telephone"></i> Telefones</h5>
                    <button type="button" class="btn btn-sm btn-light" id="addTelefone">
                        <i class="bi bi-plus-circle"></i> Adicionar
                    </button>
                </div>
                <div class="card-body">
                    <div id="telefonesContainer">
                        <?php if (!empty($associado['telefones'])): ?>
                            <?php foreach ($associado['telefones'] as $index => $telefone): ?>
                                <div class="row g-2 mb-2 telefone-item">
                                    <div class="col-md-4">
                                        <select class="form-select form-select-sm" name="telefones[<?= $index ?>][tipo]">
                                            <option value="celular" <?= $telefone['tipo'] === 'celular' ? 'selected' : '' ?>>Celular</option>
                                            <option value="residencial" <?= $telefone['tipo'] === 'residencial' ? 'selected' : '' ?>>Residencial</option>
                                            <option value="comercial" <?= $telefone['tipo'] === 'comercial' ? 'selected' : '' ?>>Comercial</option>
                                            <option value="outro" <?= $telefone['tipo'] === 'outro' ? 'selected' : '' ?>>Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm mask-phone" 
                                               name="telefones[<?= $index ?>][numero]" 
                                               value="<?= esc($telefone['numero']) ?>" 
                                               placeholder="(00) 00000-0000">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger w-100 remove-telefone">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="row g-2 mb-2 telefone-item">
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm" name="telefones[0][tipo]">
                                        <option value="celular">Celular</option>
                                        <option value="residencial">Residencial</option>
                                        <option value="comercial">Comercial</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm mask-phone" 
                                           name="telefones[0][numero]" placeholder="(00) 00000-0000">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-danger w-100 remove-telefone">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Endereços -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Endereços</h5>
                    <button type="button" class="btn btn-sm btn-dark" id="addEndereco">
                        <i class="bi bi-plus-circle"></i> Adicionar
                    </button>
                </div>
                <div class="card-body">
                    <div id="enderecosContainer">
                        <?php if (!empty($associado['enderecos'])): ?>
                            <?php foreach ($associado['enderecos'] as $index => $endereco): ?>
                                <div class="endereco-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong><?= $index === 0 ? 'Endereço Principal' : 'Endereço ' . ($index + 1) ?></strong>
                                        <button type="button" class="btn btn-sm btn-danger remove-endereco">
                                            <i class="bi bi-trash"></i> Remover
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control mask-cep buscar-cep" 
                                                       name="enderecos[<?= $index ?>][cep]" 
                                                       value="<?= esc($endereco['cep'] ?? '') ?>" 
                                                       placeholder="CEP"
                                                       data-index="<?= $index ?>">
                                                <button type="button" class="btn btn-outline-primary btn-buscar-cep" data-index="<?= $index ?>">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Ex: 01001-000</small>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control form-control-sm endereco-logradouro" 
                                                   name="enderecos[<?= $index ?>][logradouro]" 
                                                   value="<?= esc($endereco['logradouro']) ?>" 
                                                   placeholder="Logradouro"
                                                   data-index="<?= $index ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="enderecos[<?= $index ?>][numero]" 
                                                   value="<?= esc($endereco['numero'] ?? '') ?>" 
                                                   placeholder="Nº">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm endereco-complemento" 
                                                   name="enderecos[<?= $index ?>][complemento]" 
                                                   value="<?= esc($endereco['complemento'] ?? '') ?>" 
                                                   placeholder="Complemento"
                                                   data-index="<?= $index ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm endereco-bairro" 
                                                   name="enderecos[<?= $index ?>][bairro]" 
                                                   value="<?= esc($endereco['bairro']) ?>" 
                                                   placeholder="Bairro"
                                                   data-index="<?= $index ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm endereco-cidade" 
                                                   name="enderecos[<?= $index ?>][cidade]" 
                                                   value="<?= esc($endereco['cidade']) ?>" 
                                                   placeholder="Cidade"
                                                   data-index="<?= $index ?>">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control form-control-sm text-uppercase endereco-estado" 
                                                   name="enderecos[<?= $index ?>][estado]" 
                                                   value="<?= esc($endereco['estado']) ?>" 
                                                   placeholder="UF" maxlength="2"
                                                   data-index="<?= $index ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="endereco-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Endereço Principal</strong>
                                    <button type="button" class="btn btn-sm btn-danger remove-endereco">
                                        <i class="bi bi-trash"></i> Remover
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control mask-cep buscar-cep" 
                                                   name="enderecos[0][cep]" 
                                                   placeholder="CEP"
                                                   data-index="0">
                                            <button type="button" class="btn btn-outline-primary btn-buscar-cep" data-index="0">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Ex: 01001-000</small>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control form-control-sm endereco-logradouro" 
                                               name="enderecos[0][logradouro]" 
                                               placeholder="Logradouro"
                                               data-index="0">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="enderecos[0][numero]" 
                                               placeholder="Nº">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm endereco-complemento" 
                                               name="enderecos[0][complemento]" 
                                               placeholder="Complemento"
                                               data-index="0">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm endereco-bairro" 
                                               name="enderecos[0][bairro]" 
                                               placeholder="Bairro"
                                               data-index="0">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm endereco-cidade" 
                                               name="enderecos[0][cidade]" 
                                               placeholder="Cidade"
                                               data-index="0">
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" class="form-control form-control-sm text-uppercase endereco-estado" 
                                               name="enderecos[0][estado]" 
                                               placeholder="UF" maxlength="2"
                                               data-index="0">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control <?= session('errors.observacoes') ? 'is-invalid' : '' ?>" 
                              id="observacoes" name="observacoes" 
                              rows="3"><?= old('observacoes', $associado['observacoes'] ?? '') ?></textarea>
                    <?php if (session('errors.observacoes')): ?>
                        <div class="invalid-feedback"><?= session('errors.observacoes') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2 justify-content-end mb-4">
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
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('mask-phone')) {
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
    }
});

// Máscara de CEP
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('mask-cep')) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        }
    }
});

// Adicionar Telefone
let telefoneIndex = <?= count($associado['telefones'] ?? [1]) ?>;
document.getElementById('addTelefone').addEventListener('click', function() {
    const container = document.getElementById('telefonesContainer');
    const html = `
        <div class="row g-2 mb-2 telefone-item">
            <div class="col-md-4">
                <select class="form-select form-select-sm" name="telefones[${telefoneIndex}][tipo]">
                    <option value="celular">Celular</option>
                    <option value="residencial">Residencial</option>
                    <option value="comercial">Comercial</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm mask-phone" 
                       name="telefones[${telefoneIndex}][numero]" placeholder="(00) 00000-0000">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger w-100 remove-telefone">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    telefoneIndex++;
});

// Remover Telefone
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-telefone')) {
        const item = e.target.closest('.telefone-item');
        if (document.querySelectorAll('.telefone-item').length > 1) {
            item.remove();
        } else {
            alert('Pelo menos um telefone deve ser mantido.');
        }
    }
});

// Adicionar Endereço
let enderecoIndex = <?= count($associado['enderecos'] ?? [1]) ?>;
document.getElementById('addEndereco').addEventListener('click', function() {
    const container = document.getElementById('enderecosContainer');
    const html = `
        <div class="endereco-item border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <strong>Endereço ${enderecoIndex + 1}</strong>
                <button type="button" class="btn btn-sm btn-danger remove-endereco">
                    <i class="bi bi-trash"></i> Remover
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control mask-cep buscar-cep" 
                               name="enderecos[${enderecoIndex}][cep]" 
                               placeholder="CEP"
                               data-index="${enderecoIndex}">
                        <button type="button" class="btn btn-outline-primary btn-buscar-cep" data-index="${enderecoIndex}">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <small class="text-muted">Ex: 01001-000</small>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control form-control-sm endereco-logradouro" 
                           name="enderecos[${enderecoIndex}][logradouro]" 
                           placeholder="Logradouro"
                           data-index="${enderecoIndex}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" 
                           name="enderecos[${enderecoIndex}][numero]" 
                           placeholder="Nº">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm endereco-complemento" 
                           name="enderecos[${enderecoIndex}][complemento]" 
                           placeholder="Complemento"
                           data-index="${enderecoIndex}">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm endereco-bairro" 
                           name="enderecos[${enderecoIndex}][bairro]" 
                           placeholder="Bairro"
                           data-index="${enderecoIndex}">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm endereco-cidade" 
                           name="enderecos[${enderecoIndex}][cidade]" 
                           placeholder="Cidade"
                           data-index="${enderecoIndex}">
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control form-control-sm text-uppercase endereco-estado" 
                           name="enderecos[${enderecoIndex}][estado]" 
                           placeholder="UF" maxlength="2"
                           data-index="${enderecoIndex}">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    enderecoIndex++;
});

// Remover Endereço
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-endereco')) {
        const item = e.target.closest('.endereco-item');
        if (document.querySelectorAll('.endereco-item').length > 1) {
            item.remove();
        } else {
            alert('Pelo menos um endereço deve ser mantido.');
        }
    }
});

// Buscar CEP via ViaCEP
async function buscarCEP(cep, index) {
    // Remove caracteres não numéricos
    const cepLimpo = cep.replace(/\D/g, '');
    
    // Validação: deve ter exatamente 8 dígitos
    if (cepLimpo.length !== 8) {
        alert('CEP inválido. O CEP deve conter exatamente 8 dígitos.');
        return;
    }
    
    // Validação: não pode conter letras
    if (!/^\d+$/.test(cepLimpo)) {
        alert('CEP inválido. O CEP deve conter apenas números.');
        return;
    }
    
    // Buscar elementos do endereço pelo data-index
    const logradouro = document.querySelector(`.endereco-logradouro[data-index="${index}"]`);
    const complemento = document.querySelector(`.endereco-complemento[data-index="${index}"]`);
    const bairro = document.querySelector(`.endereco-bairro[data-index="${index}"]`);
    const cidade = document.querySelector(`.endereco-cidade[data-index="${index}"]`);
    const estado = document.querySelector(`.endereco-estado[data-index="${index}"]`);
    const btnBuscar = document.querySelector(`.btn-buscar-cep[data-index="${index}"]`);
    
    // Mostrar loading
    const iconOriginal = btnBuscar.innerHTML;
    btnBuscar.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    btnBuscar.disabled = true;
    
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
        const data = await response.json();
        
        if (data.erro) {
            alert('CEP não encontrado. Por favor, preencha o endereço manualmente.');
            // Habilitar campos para preenchimento manual
            logradouro.removeAttribute('readonly');
            bairro.removeAttribute('readonly');
            cidade.removeAttribute('readonly');
            estado.removeAttribute('readonly');
        } else {
            // Preencher os campos com os dados da API
            logradouro.value = data.logradouro || '';
            complemento.value = data.complemento || '';
            bairro.value = data.bairro || '';
            cidade.value = data.localidade || '';
            estado.value = data.uf || '';
            
            // Colocar foco no campo número
            const numero = logradouro.closest('.row').querySelector('input[name*="[numero]"]');
            if (numero) {
                numero.focus();
            }
            
            // Mostrar feedback positivo
            btnBuscar.innerHTML = '<i class="bi bi-check-circle text-success"></i>';
            setTimeout(() => {
                btnBuscar.innerHTML = iconOriginal;
            }, 2000);
        }
    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        alert('Erro ao consultar o CEP. Verifique sua conexão e tente novamente, ou preencha o endereço manualmente.');
        // Habilitar campos para preenchimento manual
        logradouro.removeAttribute('readonly');
        bairro.removeAttribute('readonly');
        cidade.removeAttribute('readonly');
        estado.removeAttribute('readonly');
    } finally {
        btnBuscar.disabled = false;
        btnBuscar.innerHTML = iconOriginal;
    }
}

// Event listener para os botões de buscar CEP
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-buscar-cep')) {
        e.preventDefault();
        const btn = e.target.closest('.btn-buscar-cep');
        const index = btn.getAttribute('data-index');
        const inputCep = document.querySelector(`.buscar-cep[data-index="${index}"]`);
        if (inputCep && inputCep.value) {
            buscarCEP(inputCep.value, index);
        } else {
            alert('Por favor, informe o CEP.');
        }
    }
});

// Buscar CEP ao pressionar Enter no campo CEP
document.addEventListener('keypress', function(e) {
    if (e.target.classList.contains('buscar-cep') && e.key === 'Enter') {
        e.preventDefault();
        const index = e.target.getAttribute('data-index');
        if (e.target.value) {
            buscarCEP(e.target.value, index);
        }
    }
});

// Buscar CEP automaticamente quando o usuário terminar de digitar (após 8 dígitos)
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('buscar-cep')) {
        const cepLimpo = e.target.value.replace(/\D/g, '');
        if (cepLimpo.length === 8) {
            const index = e.target.getAttribute('data-index');
            // Aguardar 500ms para dar tempo do usuário finalizar
            setTimeout(() => {
                const cepAtual = e.target.value.replace(/\D/g, '');
                if (cepAtual.length === 8) {
                    buscarCEP(e.target.value, index);
                }
            }, 500);
        }
    }
});
</script>
<?= $this->endSection() ?>


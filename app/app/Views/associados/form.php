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
                            <label for="registro" class="form-label">REGISTRO</label>
                            <input type="text" class="form-control <?= session('errors.registro') ? 'is-invalid' : '' ?>" 
                                   id="registro" name="registro" 
                                   value="<?= old('registro', $associado['registro'] ?? '') ?>">
                            <?php if (session('errors.registro')): ?>
                                <div class="invalid-feedback"><?= session('errors.registro') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <label for="matricula_sindical" class="form-label">Matrícula Sindical</label>
                            <input type="text" class="form-control <?= session('errors.matricula_sindical') ? 'is-invalid' : '' ?>" 
                                   id="matricula_sindical" name="matricula_sindical" 
                                   value="<?= old('matricula_sindical', $associado['matricula_sindical'] ?? '') ?>">
                            <?php if (session('errors.matricula_sindical')): ?>
                                <div class="invalid-feedback"><?= session('errors.matricula_sindical') ?></div>
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

                        <div class="col-md-6">
                            <label for="tipo_aposentado" class="form-label">Tipo de Aposentadoria</label>
                            <select class="form-select <?= session('errors.tipo_aposentado') ? 'is-invalid' : '' ?>" 
                                    id="tipo_aposentado" name="tipo_aposentado">
                                <option value="NAO_APOSENTADO" <?= old('tipo_aposentado', $associado['tipo_aposentado'] ?? 'NAO_APOSENTADO') == 'NAO_APOSENTADO' ? 'selected' : '' ?>>
                                    Não Aposentado
                                </option>
                                <option value="CLT" <?= old('tipo_aposentado', $associado['tipo_aposentado'] ?? '') == 'CLT' ? 'selected' : '' ?>>
                                    Aposentado CLT
                                </option>
                                <option value="PENSIONISTA" <?= old('tipo_aposentado', $associado['tipo_aposentado'] ?? '') == 'PENSIONISTA' ? 'selected' : '' ?>>
                                    Aposentado Pensionista
                                </option>
                            </select>
                            <?php if (session('errors.tipo_aposentado')): ?>
                                <div class="invalid-feedback"><?= session('errors.tipo_aposentado') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusAtivo" value="ATIVO" 
                                           <?= old('status', $associado['status'] ?? 'ATIVO') === 'ATIVO' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusAtivo">
                                        <span class="badge bg-success">Ativo</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusInativo" value="INATIVO" 
                                           <?= old('status', $associado['status'] ?? 'ATIVO') === 'INATIVO' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusInativo">
                                        <span class="badge bg-danger">Inativo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contatos -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-telephone"></i> Contatos</h5>
                    <button type="button" class="btn btn-sm btn-light" id="addContato">
                        <i class="bi bi-plus-circle"></i> Adicionar
                    </button>
                </div>
                <div class="card-body">
                    <div id="contatosContainer">
                        <?php if (!empty($associado['contatos'])): ?>
                            <?php foreach ($associado['contatos'] as $index => $contato): ?>
                                <div class="row g-2 mb-2 contato-item">
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" name="contatos[<?= $index ?>][tipo]">
                                            <option value="celular" <?= $contato['tipo'] === 'celular' ? 'selected' : '' ?>>Celular</option>
                                            <option value="fixo" <?= $contato['tipo'] === 'fixo' ? 'selected' : '' ?>>Fixo</option>
                                            <option value="email" <?= $contato['tipo'] === 'email' ? 'selected' : '' ?>>Email</option>
                                            <option value="responsavel" <?= $contato['tipo'] === 'responsavel' ? 'selected' : '' ?>>Responsável</option>
                                            <option value="outro" <?= $contato['tipo'] === 'outro' ? 'selected' : '' ?>>Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="contatos[<?= $index ?>][valor]" 
                                               value="<?= esc($contato['valor']) ?>" 
                                               placeholder="Telefone, email, etc.">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="contatos[<?= $index ?>][observacao]" 
                                               value="<?= esc($contato['observacao'] ?? '') ?>" 
                                               placeholder="Observação">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger w-100 remove-contato">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="row g-2 mb-2 contato-item">
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" name="contatos[0][tipo]">
                                        <option value="celular">Celular</option>
                                        <option value="fixo">Fixo</option>
                                        <option value="email">Email</option>
                                        <option value="responsavel">Responsável</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" 
                                           name="contatos[0][valor]" placeholder="Telefone, email, etc.">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-sm" 
                                           name="contatos[0][observacao]" placeholder="Observação">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-danger w-100 remove-contato">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Endereço</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="endereco_cep" class="form-label">CEP</label>
                            <div class="input-group">
                                <input type="text" class="form-control mask-cep" 
                                       id="endereco_cep"
                                       name="endereco_cep" 
                                       value="<?= old('endereco_cep', $associado['endereco_cep'] ?? '') ?>" 
                                       placeholder="00000-000">
                                <button type="button" class="btn btn-outline-primary" id="btnBuscarCep">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <small class="text-muted">Ex: 01001-000</small>
                        </div>
                        <div class="col-md-7">
                            <label for="endereco_logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" 
                                   id="endereco_logradouro"
                                   name="endereco_logradouro" 
                                   value="<?= old('endereco_logradouro', $associado['endereco_logradouro'] ?? '') ?>" 
                                   placeholder="Rua, Avenida, etc.">
                        </div>
                        <div class="col-md-2">
                            <label for="endereco_numero" class="form-label">Número</label>
                            <input type="text" class="form-control" 
                                   id="endereco_numero"
                                   name="endereco_numero" 
                                   value="<?= old('endereco_numero', $associado['endereco_numero'] ?? '') ?>" 
                                   placeholder="Nº">
                        </div>
                        <div class="col-md-4">
                            <label for="endereco_complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" 
                                   id="endereco_complemento"
                                   name="endereco_complemento" 
                                   value="<?= old('endereco_complemento', $associado['endereco_complemento'] ?? '') ?>" 
                                   placeholder="Apt, Bloco, etc.">
                        </div>
                        <div class="col-md-4">
                            <label for="endereco_bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" 
                                   id="endereco_bairro"
                                   name="endereco_bairro" 
                                   value="<?= old('endereco_bairro', $associado['endereco_bairro'] ?? '') ?>" 
                                   placeholder="Bairro">
                        </div>
                        <div class="col-md-3">
                            <label for="endereco_cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" 
                                   id="endereco_cidade"
                                   name="endereco_cidade" 
                                   value="<?= old('endereco_cidade', $associado['endereco_cidade'] ?? '') ?>" 
                                   placeholder="Cidade">
                        </div>
                        <div class="col-md-1">
                            <label for="endereco_estado" class="form-label">UF</label>
                            <input type="text" class="form-control text-uppercase" 
                                   id="endereco_estado"
                                   name="endereco_estado" 
                                   value="<?= old('endereco_estado', $associado['endereco_estado'] ?? '') ?>" 
                                   placeholder="UF" 
                                   maxlength="2">
                        </div>
                    </div>
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

// Adicionar Contato
let contatoIndex = <?= count($associado['contatos'] ?? [1]) ?>;
document.getElementById('addContato').addEventListener('click', function() {
    const container = document.getElementById('contatosContainer');
    const html = `
        <div class="row g-2 mb-2 contato-item">
            <div class="col-md-3">
                <select class="form-select form-select-sm" name="contatos[${contatoIndex}][tipo]">
                    <option value="celular">Celular</option>
                    <option value="fixo">Fixo</option>
                    <option value="email">Email</option>
                    <option value="responsavel">Responsável</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" 
                       name="contatos[${contatoIndex}][valor]" placeholder="Telefone, email, etc.">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" 
                       name="contatos[${contatoIndex}][observacao]" placeholder="Observação">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger w-100 remove-contato">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    contatoIndex++;
});

// Remover Contato
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-contato')) {
        const item = e.target.closest('.contato-item');
        if (document.querySelectorAll('.contato-item').length > 1) {
            item.remove();
        } else {
            alert('Pelo menos um contato deve ser mantido.');
        }
    }
});

// Buscar CEP via ViaCEP
async function buscarCEP(cep) {
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
    
    // Buscar elementos do endereço
    const logradouro = document.getElementById('endereco_logradouro');
    const complemento = document.getElementById('endereco_complemento');
    const bairro = document.getElementById('endereco_bairro');
    const cidade = document.getElementById('endereco_cidade');
    const estado = document.getElementById('endereco_estado');
    const btnBuscar = document.getElementById('btnBuscarCep');
    
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
            const numero = document.getElementById('endereco_numero');
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

// Event listener para o botão de buscar CEP
document.getElementById('btnBuscarCep').addEventListener('click', function(e) {
    e.preventDefault();
    const inputCep = document.getElementById('endereco_cep');
    if (inputCep && inputCep.value) {
        buscarCEP(inputCep.value);
    } else {
        alert('Por favor, informe o CEP.');
    }
});

// Buscar CEP ao pressionar Enter no campo CEP
document.getElementById('endereco_cep').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (this.value) {
            buscarCEP(this.value);
        }
    }
});

// Buscar CEP automaticamente quando o usuário terminar de digitar (após 8 dígitos)
document.getElementById('endereco_cep').addEventListener('input', function(e) {
    const cepLimpo = e.target.value.replace(/\D/g, '');
    if (cepLimpo.length === 8) {
        // Aguardar 500ms para dar tempo do usuário finalizar
        setTimeout(() => {
            const cepAtual = e.target.value.replace(/\D/g, '');
            if (cepAtual.length === 8) {
                buscarCEP(e.target.value);
            }
        }, 500);
    }
});
</script>
<?= $this->endSection() ?>


<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Configurações<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-gear"></i> Configurações do Sistema
    </h1>
</div>

<form action="<?= base_url('configuracoes/update') ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="row">
        <!-- Dados da Empresa -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Dados da Empresa/Sindicato</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($empresa as $config): ?>
                    <div class="mb-3">
                        <label for="<?= $config['chave'] ?>" class="form-label">
                            <?= esc($config['descricao']) ?>
                        </label>
                        <input type="text" class="form-control" 
                               id="<?= $config['chave'] ?>" 
                               name="<?= $config['chave'] ?>" 
                               value="<?= esc($config['valor']) ?>"
                               <?= $config['chave'] === 'empresa_cnpj' ? 'data-mask="00.000.000/0000-00"' : '' ?>>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Endereço</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($endereco as $config): ?>
                        <?php 
                            $colSize = 'col-12';
                            if (in_array($config['chave'], ['endereco_numero', 'endereco_estado', 'endereco_cep'])) {
                                $colSize = 'col-md-4';
                            } elseif (in_array($config['chave'], ['endereco_bairro', 'endereco_cidade'])) {
                                $colSize = 'col-md-6';
                            } elseif ($config['chave'] === 'endereco_complemento') {
                                $colSize = 'col-md-8';
                            }
                        ?>
                        <div class="<?= $colSize ?> mb-3">
                            <label for="<?= $config['chave'] ?>" class="form-label">
                                <?= esc($config['descricao']) ?>
                            </label>
                            <input type="text" class="form-control" 
                                   id="<?= $config['chave'] ?>" 
                                   name="<?= $config['chave'] ?>" 
                                   value="<?= esc($config['valor']) ?>"
                                   <?= $config['chave'] === 'endereco_cep' ? 'data-mask="00000-000"' : '' ?>
                                   <?= $config['chave'] === 'endereco_estado' ? 'maxlength="2" style="text-transform: uppercase;"' : '' ?>>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-telephone"></i> Contato</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($contato as $config): ?>
                        <?php 
                            $colSize = 'col-md-6';
                            if ($config['chave'] === 'contato_site') {
                                $colSize = 'col-12';
                            }
                            $inputType = 'text';
                            if ($config['tipo'] === 'email') $inputType = 'email';
                            if ($config['tipo'] === 'tel') $inputType = 'tel';
                        ?>
                        <div class="<?= $colSize ?> mb-3">
                            <label for="<?= $config['chave'] ?>" class="form-label">
                                <?= esc($config['descricao']) ?>
                            </label>
                            <input type="<?= $inputType ?>" class="form-control" 
                                   id="<?= $config['chave'] ?>" 
                                   name="<?= $config['chave'] ?>" 
                                   value="<?= esc($config['valor']) ?>"
                                   <?= $config['tipo'] === 'tel' ? 'data-mask="(00) 00000-0000"' : '' ?>>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logos -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-image"></i> Logotipos</h5>
                </div>
                <div class="card-body">
                    <!-- Logo Principal -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Logo Principal (Sistema)</label>
                        <div class="text-center mb-3">
                            <?php 
                                $logoPrincipal = 'assets/images/logo.png';
                                foreach ($imagens as $img) {
                                    if ($img['chave'] === 'logo_principal') {
                                        $logoPrincipal = $img['valor'];
                                        break;
                                    }
                                }
                            ?>
                            <img src="<?= base_url($logoPrincipal) ?>?v=<?= time() ?>" 
                                 alt="Logo Principal" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 150px;"
                                 id="preview-logo-principal">
                        </div>
                        <form action="<?= base_url('configuracoes/upload-logo') ?>" method="post" enctype="multipart/form-data" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tipo" value="logo_principal">
                            <input type="file" class="form-control" name="logo" accept="image/*" required 
                                   onchange="previewImage(this, 'preview-logo-principal')">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-upload"></i> Atualizar Logo Principal
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- Logo Relatórios -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Logo para Relatórios</label>
                        <div class="text-center mb-3">
                            <?php 
                                $logoRelatorio = 'assets/images/logo.png';
                                foreach ($imagens as $img) {
                                    if ($img['chave'] === 'logo_relatorio') {
                                        $logoRelatorio = $img['valor'];
                                        break;
                                    }
                                }
                            ?>
                            <img src="<?= base_url($logoRelatorio) ?>?v=<?= time() ?>" 
                                 alt="Logo Relatórios" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 150px;"
                                 id="preview-logo-relatorio">
                        </div>
                        <form action="<?= base_url('configuracoes/upload-logo') ?>" method="post" enctype="multipart/form-data" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tipo" value="logo_relatorio">
                            <input type="file" class="form-control" name="logo" accept="image/*" required
                                   onchange="previewImage(this, 'preview-logo-relatorio')">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-upload"></i> Atualizar Logo Relatórios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatórios -->
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Configurações de Relatórios</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($relatorios as $config): ?>
                    <div class="mb-3">
                        <label for="<?= $config['chave'] ?>" class="form-label">
                            <?= esc($config['descricao']) ?>
                        </label>
                        <textarea class="form-control" 
                                  id="<?= $config['chave'] ?>" 
                                  name="<?= $config['chave'] ?>" 
                                  rows="3"><?= esc($config['valor']) ?></textarea>
                        <small class="text-muted">
                            Este texto aparecerá <?= $config['chave'] === 'relatorio_cabecalho' ? 'no cabeçalho' : 'no rodapé' ?> dos relatórios gerados
                        </small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão Salvar -->
    <div class="d-flex justify-content-end mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Salvar Todas as Configurações
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Preview de imagem antes do upload
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Máscaras de entrada
$(document).ready(function() {
    // Máscara CNPJ
    $('[data-mask="00.000.000/0000-00"]').mask('00.000.000/0000-00');
    
    // Máscara CEP
    $('[data-mask="00000-000"]').mask('00000-000');
    
    // Máscara Telefone
    $('[data-mask="(00) 00000-0000"]').mask('(00) 00000-0000');
});
</script>
<?= $this->endSection() ?>

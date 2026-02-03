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

        <!-- Textos para Relatórios -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Textos para Relatórios</h5>
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
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Logos -->
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-image"></i> Logos e Imagens</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($imagens as $config): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?= esc($config['descricao']) ?></h6>
                                    
                                    <?php if (!empty($config['valor']) && file_exists(FCPATH . $config['valor'])): ?>
                                    <div class="mb-3">
                                        <img src="<?= base_url($config['valor']) ?>?v=<?= time() ?>" 
                                             alt="<?= esc($config['descricao']) ?>" 
                                             class="img-thumbnail" 
                                             style="max-height: 150px; background: #f8f9fa;">
                                    </div>
                                    <?php else: ?>
                                    <div class="mb-3 p-4 bg-light rounded">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mb-0">Nenhuma imagem</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <form action="<?= base_url('configuracoes/upload-logo') ?>" 
                                          method="post" 
                                          enctype="multipart/form-data"
                                          class="d-flex gap-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="tipo" value="<?= $config['chave'] ?>">
                                        <input type="file" class="form-control form-control-sm" 
                                               name="logo" accept="image/*" required>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="alert alert-info mt-2">
                        <i class="bi bi-info-circle"></i> 
                        Formatos aceitos: JPG, PNG, GIF, WEBP. Tamanho máximo: 2MB.
                        <br>
                        <small>A <strong>Logo Principal</strong> aparece no sistema. A <strong>Logo para Relatórios</strong> é usada nos PDFs gerados.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões -->
    <div class="d-flex gap-2 justify-content-end mb-4">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Salvar Configurações
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    // Máscaras
    $('[data-mask]').each(function() {
        $(this).mask($(this).data('mask'));
    });
    
    // Uppercase para estado
    $('#endereco_estado').on('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<?= $this->endSection() ?>

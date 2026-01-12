<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Importação de Associados<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Importação de Associados</h1>
    <a href="<?= base_url('importacao/downloadTemplate') ?>" class="btn btn-outline-primary">
        <i class="bi bi-download"></i> Baixar Template
    </a>
</div>

<!-- Upload Form -->
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Upload de Arquivo</h5>
                
                <form method="post" action="<?= base_url('importacao/upload') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Selecione o arquivo Excel ou CSV</label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Formatos aceitos: .xlsx, .xls, .csv | Tamanho máximo: 5MB
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Importante:</strong>
                        <ul class="mb-0 mt-2">
                            <li>O arquivo deve conter as colunas: <strong>Nome, CPF, Data Nascimento, Email, Telefone, Unidade, Função, Status</strong></li>
                            <li>Campos obrigatórios: <strong>Nome, CPF, Data Nascimento, Unidade, Função</strong></li>
                            <li>Se o CPF já existir no sistema, o registro será <strong>atualizado</strong></li>
                            <li>Status padrão é "ativo" se não informado</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-upload"></i> Importar Arquivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Errors -->
<?php if (session()->has('import_errors')): ?>
<div class="row mb-4">
    <div class="col-lg-10 mx-auto">
        <div class="alert alert-warning alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle"></i> Erros Encontrados
            </h5>
            <hr>
            <div style="max-height: 300px; overflow-y: auto;">
                <ul class="mb-0">
                    <?php foreach (session('import_errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Imports -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Histórico de Importações</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($recentImports)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Arquivo</th>
                            <th>Usuário</th>
                            <th>Data/Hora</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Inseridos</th>
                            <th class="text-center">Atualizados</th>
                            <th class="text-center">Ignorados</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentImports as $import): ?>
                        <tr>
                            <td>
                                <i class="bi bi-file-earmark-excel text-success"></i>
                                <?= esc($import['file_name']) ?>
                            </td>
                            <td><?= esc($import['user_name'] ?? 'Sistema') ?></td>
                            <td><?= format_datetime($import['created_at']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-secondary"><?= $import['total_rows'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success"><?= $import['inserted'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info"><?= $import['updated'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($import['skipped'] > 0): ?>
                                    <span class="badge bg-warning"><?= $import['skipped'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Nenhuma importação realizada ainda</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Validate file before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('file');
    const file = fileInput.files[0];
    
    if (!file) {
        e.preventDefault();
        alert('Por favor, selecione um arquivo.');
        return false;
    }
    
    // Check file size (5MB = 5242880 bytes)
    if (file.size > 5242880) {
        e.preventDefault();
        alert('O arquivo é muito grande. Tamanho máximo: 5MB');
        return false;
    }
    
    // Check extension
    const validExtensions = ['.xlsx', '.xls', '.csv'];
    const fileName = file.name.toLowerCase();
    const isValid = validExtensions.some(ext => fileName.endsWith(ext));
    
    if (!isValid) {
        e.preventDefault();
        alert('Formato de arquivo inválido. Use: .xlsx, .xls ou .csv');
        return false;
    }
    
    // Show loading
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
});

// Preview file name
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        console.log('Arquivo selecionado:', fileName);
    }
});
</script>
<?= $this->endSection() ?>

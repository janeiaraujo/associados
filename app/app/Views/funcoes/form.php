<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= $action === 'create' ? 'Nova Função' : 'Editar Função' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <?= $action === 'create' ? 'Nova Função' : 'Editar Função' ?>
            </h1>
            <a href="<?= base_url('funcoes') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $action === 'create' ? base_url('funcoes/store') : base_url('funcoes/update/' . $funcao['id']) ?>">
            <?= csrf_field() ?>
            
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= session('errors.nome') ? 'is-invalid' : '' ?>" 
                                   id="nome" 
                                   name="nome" 
                                   value="<?= old('nome', $funcao['nome'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.nome')): ?>
                                <div class="invalid-feedback"><?= session('errors.nome') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control <?= session('errors.descricao') ? 'is-invalid' : '' ?>" 
                                      id="descricao" 
                                      name="descricao" 
                                      rows="3"><?= old('descricao', $funcao['descricao'] ?? '') ?></textarea>
                            <?php if (session('errors.descricao')): ?>
                                <div class="invalid-feedback"><?= session('errors.descricao') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="ativo" 
                                       name="ativo" 
                                       value="1" 
                                       <?= old('ativo', $funcao['ativo'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ativo">
                                    Ativo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="<?= base_url('funcoes') ?>" class="btn btn-secondary">
                    Cancelar
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

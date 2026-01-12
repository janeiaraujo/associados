<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
    <?= $action === 'create' ? 'Novo Usuário' : 'Editar Usuário' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <?= $action === 'create' ? 'Novo Usuário' : 'Editar Usuário' ?>
            </h1>
            <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <form method="post" 
              action="<?= $action === 'create' ? base_url('users/store') : base_url('users/update/' . $user['id']) ?>"
              class="needs-validation" novalidate>
            
            <?= csrf_field() ?>
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <!-- Dados Pessoais -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Dados do Usuário</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" 
                                   value="<?= old('name', $user['name'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.name')): ?>
                                <div class="invalid-feedback"><?= session('errors.name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" 
                                   value="<?= old('email', $user['email'] ?? '') ?>" 
                                   required>
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="role_id" class="form-label">Perfil <span class="text-danger">*</span></label>
                            <select class="form-select <?= session('errors.role_id') ? 'is-invalid' : '' ?>" 
                                    id="role_id" name="role_id" required>
                                <option value="">Selecione um perfil</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" 
                                            <?= old('role_id', $user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                        <?= esc($role['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.role_id')): ?>
                                <div class="invalid-feedback"><?= session('errors.role_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Senha <?= $action === 'create' ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" 
                                   <?= $action === 'create' ? 'required' : '' ?>>
                            <?php if ($action === 'edit'): ?>
                                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                            <?php endif; ?>
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback"><?= session('errors.password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="ativo" <?= old('status', $user['status'] ?? 'ativo') === 'ativo' ? 'selected' : '' ?>>
                                    Ativo
                                </option>
                                <option value="inativo" <?= old('status', $user['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>
                                    Inativo
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2 justify-content-end mb-4">
                <a href="<?= base_url('users') ?>" class="btn btn-secondary">
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

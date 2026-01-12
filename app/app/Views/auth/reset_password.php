<?= $this->extend('layout/auth') ?>

<?= $this->section('title') ?>Nova Senha<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4 class="text-center mb-4">Definir Nova Senha</h4>

<form action="<?= base_url('reset-password') ?>" method="post" id="resetForm">
    <?= csrf_field() ?>
    <input type="hidden" name="token" value="<?= esc($token) ?>">
    
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="bi bi-lock"></i> Nova Senha
        </label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="password-strength" id="strength"></div>
        <small class="text-muted">
            Mínimo 8 caracteres, incluindo maiúscula, minúscula, número e caractere especial.
        </small>
    </div>
    
    <div class="mb-3">
        <label for="password_confirm" class="form-label">
            <i class="bi bi-lock-fill"></i> Confirmar Senha
        </label>
        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
    </div>
    
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="showPass">
            <label class="form-check-label" for="showPass">
                Mostrar senhas
            </label>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-check-circle"></i> Redefinir Senha
    </button>
    
    <div class="text-center">
        <a href="<?= base_url('login') ?>" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Voltar ao login
        </a>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = document.getElementById('strength');
    
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[@$!%*?&#]/.test(password)) score++;
    
    strength.className = 'password-strength';
    if (score >= 5) {
        strength.classList.add('strong');
    } else if (score >= 3) {
        strength.classList.add('medium');
    } else if (score > 0) {
        strength.classList.add('weak');
    }
});

// Show/hide passwords
document.getElementById('showPass').addEventListener('change', function() {
    const type = this.checked ? 'text' : 'password';
    document.getElementById('password').type = type;
    document.getElementById('password_confirm').type = type;
});
</script>
<?= $this->endSection() ?>

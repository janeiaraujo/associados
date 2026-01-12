<?= $this->extend('layout/auth') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4 class="text-center mb-4">Bem-vindo!</h4>

<form action="<?= base_url('login') ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> E-mail
        </label>
        <input type="email" class="form-control" id="email" name="email" 
               value="<?= old('email') ?>" required autofocus>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="bi bi-lock"></i> Senha
        </label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">
            Lembrar-me
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-box-arrow-in-right"></i> Entrar
    </button>
    
    <div class="text-center">
        <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">
            Esqueceu sua senha?
        </a>
    </div>
</form>
<?= $this->endSection() ?>

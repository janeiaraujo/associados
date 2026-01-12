<?= $this->extend('layout/auth') ?>

<?= $this->section('title') ?>Esqueci minha senha<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4 class="text-center mb-4">Redefinir Senha</h4>
<p class="text-muted text-center mb-4">
    Digite seu e-mail e enviaremos instruções para redefinir sua senha.
</p>

<form action="<?= base_url('forgot-password') ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> E-mail
        </label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-send"></i> Enviar
    </button>
    
    <div class="text-center">
        <a href="<?= base_url('login') ?>" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Voltar ao login
        </a>
    </div>
</form>
<?= $this->endSection() ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Redefinição de Senha</h2>
        </div>
        <div class="content">
            <p>Olá, <strong><?= esc($user['name']) ?></strong>!</p>
            
            <p>Você solicitou a redefinição de senha do Sistema de Associados.</p>
            
            <p>Clique no botão abaixo para definir uma nova senha:</p>
            
            <p style="text-align: center;">
                <a href="<?= esc($resetLink) ?>" class="button">Redefinir Senha</a>
            </p>
            
            <p>Ou copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; color: #667eea;"><?= esc($resetLink) ?></p>
            
            <p><strong>Este link expira em <?= $expirationMinutes ?> minutos.</strong></p>
            
            <p>Se você não solicitou esta redefinição, ignore este e-mail. Sua senha permanecerá inalterada.</p>
            
            <hr>
            
            <p class="footer">
                Este é um e-mail automático. Por favor, não responda.<br>
                &copy; <?= date('Y') ?> Sistema de Associados
            </p>
        </div>
    </div>
</body>
</html>

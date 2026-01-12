<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Sistema de Associados</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        
        .navbar-top {
            background-color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-left: 250px;
            padding: 1rem 2rem;
        }
        
        .stat-card {
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .sidebar.show {
                width: 250px;
            }
            
            .main-content, .navbar-top {
                margin-left: 0;
            }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <h4 class="mb-0"><i class="bi bi-people-fill"></i> Associados</h4>
            <small class="text-white-50">Sistema de Gestão</small>
        </div>
        
        <hr class="text-white-50">
        
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <?php if (has_permission('associados.view')): ?>
            <a class="nav-link" href="<?= base_url('associados') ?>">
                <i class="bi bi-person-lines-fill"></i> Associados
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('unidades.view')): ?>
            <a class="nav-link" href="<?= base_url('unidades') ?>">
                <i class="bi bi-building"></i> Unidades
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('funcoes.view')): ?>
            <a class="nav-link" href="<?= base_url('funcoes') ?>">
                <i class="bi bi-briefcase"></i> Funções
            </a>
            <?php endif; ?>
            <!--
            <?php if (has_permission('associados.import')): ?>
            <a class="nav-link" href="<?= base_url('importacao') ?>">
                <i class="bi bi-file-earmark-arrow-up"></i> Importação
            </a>
            <?php endif; ?>
            -->
            <?php if (has_permission('relatorios.view')): ?>
            <a class="nav-link" href="<?= base_url('relatorios') ?>">
                <i class="bi bi-file-earmark-bar-graph"></i> Relatórios
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('users.manage')): ?>
            <hr class="text-white-50">
            <a class="nav-link" href="<?= base_url('users') ?>">
                <i class="bi bi-people"></i> Usuários
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('audit.view')): ?>
            <a class="nav-link" href="<?= base_url('audit') ?>">
                <i class="bi bi-clock-history"></i> Auditoria
            </a>
            <?php endif; ?>
        </nav>
        
        <div class="position-absolute bottom-0 w-100 p-3">
            <div class="text-white-50 small mb-2">
                <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name')) ?>
            </div>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
    
    <!-- Top Navbar -->
    <nav class="navbar navbar-top navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn d-md-none" type="button" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="ms-auto d-flex align-items-center">
                <span class="text-muted me-3">
                    <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name')) ?>
                </span>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Alerts -->
        <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> 
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional, for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>

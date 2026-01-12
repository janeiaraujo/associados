<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <div class="text-muted">
        <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i') ?>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total de Associados</p>
                        <h3 class="mb-0"><?= number_format($statistics['total']) ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Ativos</p>
                        <h3 class="mb-0 text-success"><?= number_format($statistics['ativos']) ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded p-2">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Inativos</p>
                        <h3 class="mb-0 text-danger"><?= number_format($statistics['inativos']) ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded p-2">
                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Usuários Ativos</p>
                        <h3 class="mb-0"><?= $activeUsers ?></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded p-2">
                        <i class="bi bi-person-check-fill text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Associados Ativos (Últimos 6 Meses)</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Distribuição por Idade</h5>
            </div>
            <div class="card-body">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabelas -->
<div class="row g-3 mb-4">
    <!-- Top Unidades -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Top 5 Unidades</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($statistics['by_unidade'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Unidade</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistics['by_unidade'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['unidade']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-primary"><?= number_format($item['total']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">Nenhum dado disponível</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Funções -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Top 5 Funções</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($statistics['by_funcao'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Função</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistics['by_funcao'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['funcao']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-success"><?= number_format($item['total']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">Nenhum dado disponível</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Importações e Atividades -->
<div class="row g-3">
    <!-- Últimas Importações -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Últimas Importações</h5>
                <?php if (has_permission('associados.import')): ?>
                    <a href="<?= base_url('importacao') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-upload"></i> Nova Importação
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (!empty($recentImports)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentImports as $import): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= esc($import['file_name']) ?></h6>
                                    <p class="mb-1 small text-muted">
                                        Por <?= esc($import['user_name'] ?? 'Sistema') ?> •
                                        <?= format_datetime($import['created_at']) ?>
                                    </p>
                                    <div class="d-flex gap-2 mt-1">
                                        <span class="badge bg-success"><?= $import['inserted'] ?> inseridos</span>
                                        <span class="badge bg-info"><?= $import['updated'] ?> atualizados</span>
                                        <?php if ($import['skipped'] > 0): ?>
                                            <span class="badge bg-warning"><?= $import['skipped'] ?> ignorados</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark"><?= $import['total_rows'] ?> linhas</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">Nenhuma importação realizada</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Atividades Recentes</h5>
                <?php if (has_permission('audit.view')): ?>
                    <a href="<?= base_url('auditoria') ?>" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (!empty($recentActivities)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentActivities as $activity): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-0 small">
                                        <strong><?= esc($activity['action']) ?></strong> em
                                        <em><?= esc($activity['entity']) ?></em>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <?= format_datetime($activity['created_at']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">Nenhuma atividade registrada</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Gráfico Mensal
const monthlyData = <?= json_encode($monthlyStats) ?>;
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Associados Ativos',
            data: monthlyData.map(item => item.count),
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Gráfico de Idade
const ageData = <?= json_encode($ageDistribution) ?>;
const ageCtx = document.getElementById('ageChart').getContext('2d');
new Chart(ageCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(ageData),
        datasets: [{
            data: Object.values(ageData),
            backgroundColor: [
                'rgba(13, 110, 253, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Relatórios<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Relatórios</h1>
    </div>

    <div class="row">
        <!-- Relatório de Associados -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people-fill text-primary"></i>
                        Relatório de Associados
                    </h5>
                    <p class="card-text">
                        Listagem completa ou filtrada de associados com suas informações cadastrais.
                    </p>
                    <button class="btn btn-primary" onclick="openReportModal('associados')">
                        <i class="bi bi-file-earmark-text"></i> Gerar Relatório
                    </button>
                </div>
            </div>
        </div>

        <!-- Relatório Estatístico -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-graph-up text-success"></i>
                        Relatório Estatístico
                    </h5>
                    <p class="card-text">
                        Estatísticas gerais: totais por unidade, função, faixa etária e status.
                    </p>
                    <button class="btn btn-success" onclick="openReportModal('estatisticas')">
                        <i class="bi bi-bar-chart"></i> Gerar Relatório
                    </button>
                </div>
            </div>
        </div>

        <!-- Relatório de Aniversariantes -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-cake2 text-warning"></i>
                        Aniversariantes do Mês
                    </h5>
                    <p class="card-text">
                        Lista de associados que fazem aniversário no mês selecionado.
                    </p>
                    <button class="btn btn-warning" onclick="openReportModal('aniversariantes')">
                        <i class="bi bi-calendar-event"></i> Gerar Relatório
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Relatórios Recentes -->
    <?php if (!empty($recentReports)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Relatórios Gerados Recentemente</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Tipo</th>
                            <th>Usuário</th>
                            <th>Filtros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentReports as $report): ?>
                        <tr>
                            <td><?= format_datetime($report['created_at']) ?></td>
                            <td><?= esc($report['report_type']) ?></td>
                            <td><?= esc($report['user_id']) ?></td>
                            <td>
                                <small class="text-muted">
                                    <?= esc(substr($report['filters'], 0, 50)) ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Gerar Relatório</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportModalBody">
                <!-- Conteúdo dinâmico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">
                    <i class="bi bi-download"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentReportType = '';

function openReportModal(type) {
    currentReportType = type;
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    const body = document.getElementById('reportModalBody');
    
    // Definir conteúdo baseado no tipo
    if (type === 'associados') {
        body.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Unidade</label>
                <select class="form-select" id="filter_unidade">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Função</label>
                <select class="form-select" id="filter_funcao">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" id="filter_status">
                    <option value="">Todos</option>
                    <option value="ATIVO">Ativo</option>
                    <option value="INATIVO">Inativo</option>
                </select>
            </div>
        `;
    } else if (type === 'aniversariantes') {
        body.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Mês</label>
                <select class="form-select" id="filter_mes">
                    ${generateMonthOptions()}
                </select>
            </div>
        `;
    } else {
        body.innerHTML = '<p>Relatório estatístico geral - sem filtros necessários.</p>';
    }
    
    modal.show();
}

function generateMonthOptions() {
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                   'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const currentMonth = new Date().getMonth() + 1;
    
    return months.map((month, index) => {
        const value = String(index + 1).padStart(2, '0');
        const selected = (index + 1) === currentMonth ? 'selected' : '';
        return `<option value="${value}" ${selected}>${month}</option>`;
    }).join('');
}

function generateReport() {
    const filters = {};
    
    if (currentReportType === 'associados') {
        filters.unidade_id = document.getElementById('filter_unidade')?.value || '';
        filters.funcao_id = document.getElementById('filter_funcao')?.value || '';
        filters.status = document.getElementById('filter_status')?.value || '';
    } else if (currentReportType === 'aniversariantes') {
        filters.mes = document.getElementById('filter_mes')?.value || '';
    }
    
    // Redirecionar para export
    const params = new URLSearchParams({
        report_type: currentReportType,
        ...filters
    });
    
    window.location.href = `<?= base_url('relatorios/export/xlsx') ?>?${params.toString()}`;
}
</script>

<?= $this->endSection() ?>

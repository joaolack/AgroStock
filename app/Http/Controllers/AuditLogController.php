<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $timezone = 'America/Sao_Paulo';
        $filters = [
            'user_id' => trim((string) $request->query('user_id', '')),
            'module' => trim((string) $request->query('module', '')),
            'action' => trim((string) $request->query('action', '')),
            'date_from' => trim((string) $request->query('date_from', '')),
            'date_to' => trim((string) $request->query('date_to', '')),
        ];

        $auditLogsQuery = AuditLog::query()
            ->with('user');

        if ($filters['user_id'] !== '' && ctype_digit($filters['user_id'])) {
            $auditLogsQuery->where('user_id', (int) $filters['user_id']);
        }

        if ($filters['module'] !== '') {
            $auditLogsQuery->where('module', $filters['module']);
        }

        if ($filters['action'] !== '') {
            $auditLogsQuery->where('action', $filters['action']);
        }

        if ($filters['date_from'] !== '') {
            $auditLogsQuery->where(
                'created_at',
                '>=',
                CarbonImmutable::createFromFormat('Y-m-d', $filters['date_from'], $timezone)
                    ->startOfDay()
                    ->utc()
            );
        }

        if ($filters['date_to'] !== '') {
            $auditLogsQuery->where(
                'created_at',
                '<=',
                CarbonImmutable::createFromFormat('Y-m-d', $filters['date_to'], $timezone)
                    ->endOfDay()
                    ->utc()
            );
        }

        $auditLogs = $auditLogsQuery->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('audit-logs.index', [
            'auditLogs' => $auditLogs,
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'filters' => $filters,
            'modules' => [
                'products' => 'Produtos',
                'categories' => 'Categorias',
                'suppliers' => 'Fornecedores',
                'stock_movements' => 'Movimentacoes',
            ],
            'actions' => [
                'created' => 'Criacao',
                'updated' => 'Edicao',
                'deleted' => 'Exclusao',
                'entry' => 'Entrada',
                'exit' => 'Saida',
            ],
        ]);
    }
}

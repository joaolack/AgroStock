<?php

namespace App\Http\Controllers;

use App\Http\Requests\WriteOffExpiredBatchRequest;
use App\Models\ProductBatch;
use App\Services\ExpirationDateService;
use App\Services\ExpiredBatchWriteOffService;
use Illuminate\Http\Request;

class ExpirationDateController extends Controller
{
    public function __construct(
        private readonly ExpirationDateService $expirationDateService,
        private readonly ExpiredBatchWriteOffService $expiredBatchWriteOffService
    ) {}

    public function index(Request $request)
    {
        return view('expiration_date.index', $this->expirationDateService->indexData(
            $request->query(),
            $request->url()
        ));
    }

    public function writeOffExpiredBatch(WriteOffExpiredBatchRequest $request, ProductBatch $batch)
    {
        $removedQuantity = $this->expiredBatchWriteOffService->writeOff(
            $batch,
            (int) $request->validated('quantity')
        );

        return back()->with('success', "Baixa de {$removedQuantity} unidade(s) vencida(s) registrada com sucesso.");
    }
}

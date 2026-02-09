<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHistoryEntryRequest;
use App\Http\Resources\HistoryEntryResource;
use App\Models\HistoryEntry;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HistoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return HistoryEntryResource::collection(
            HistoryEntry::query()
                ->with(['order.items.product', 'customer', 'serviceTeam', 'handledBy'])
                ->orderByDesc('service_date')
                ->get()
        );
    }

    public function store(StoreHistoryEntryRequest $request): HistoryEntryResource
    {
        $historyEntry = HistoryEntry::create($request->validated());
        $historyEntry->load(['order.items.product', 'customer', 'serviceTeam', 'handledBy']);

        return new HistoryEntryResource($historyEntry);
    }

    public function show(HistoryEntry $historyEntry): HistoryEntryResource
    {
        $historyEntry->load(['order.items.product', 'customer', 'serviceTeam', 'handledBy']);

        return new HistoryEntryResource($historyEntry);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceTeamRequest;
use App\Http\Requests\UpdateServiceTeamRequest;
use App\Http\Resources\ServiceTeamResource;
use App\Models\ServiceTeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;

class ServiceTeamController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ServiceTeamResource::collection(
            ServiceTeam::query()
                ->with(['leader', 'employees'])
                ->orderBy('name')
                ->get()
        );
    }

    public function store(StoreServiceTeamRequest $request): ServiceTeamResource
    {
        $validated = $request->validated();
        $memberIds = $validated['member_ids'] ?? [];
        unset($validated['member_ids']);

        $team = ServiceTeam::create($validated);

        if (!empty($memberIds)) {
            \App\Models\Employee::whereIn('id', $memberIds)->update(['service_team_id' => $team->id]);
        }

        $team->load(['leader', 'employees']);

        return new ServiceTeamResource($team);
    }

    public function show(ServiceTeam $serviceTeam): ServiceTeamResource
    {
        $serviceTeam->load(['leader', 'employees']);

        return new ServiceTeamResource($serviceTeam);
    }

    public function update(UpdateServiceTeamRequest $request, ServiceTeam $serviceTeam): ServiceTeamResource
    {
        $validated = $request->validated();
        $memberIds = $validated['member_ids'] ?? null;
        unset($validated['member_ids']);

        $serviceTeam->update($validated);

        if (is_array($memberIds)) {
            \App\Models\Employee::where('service_team_id', $serviceTeam->id)->update(['service_team_id' => null]);
            \App\Models\Employee::whereIn('id', $memberIds)->update(['service_team_id' => $serviceTeam->id]);
        }

        $serviceTeam->load(['leader', 'employees']);

        return new ServiceTeamResource($serviceTeam);
    }

    public function destroy(ServiceTeam $serviceTeam): JsonResponse
    {
        $serviceTeam->delete();

        return Response::json([], 204);
    }
}

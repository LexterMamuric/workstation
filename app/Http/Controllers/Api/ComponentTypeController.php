<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ComponentType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentTypeResource;
use App\Http\Resources\ComponentTypeCollection;
use App\Http\Requests\ComponentTypeStoreRequest;
use App\Http\Requests\ComponentTypeUpdateRequest;

class ComponentTypeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', ComponentType::class);

        $search = $request->get('search', '');

        $componentTypes = ComponentType::search($search)
            ->latest()
            ->paginate();

        return new ComponentTypeCollection($componentTypes);
    }

    /**
     * @param \App\Http\Requests\ComponentTypeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComponentTypeStoreRequest $request)
    {
        $this->authorize('create', ComponentType::class);

        $validated = $request->validated();

        $componentType = ComponentType::create($validated);

        return new ComponentTypeResource($componentType);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentType $componentType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentType $componentType)
    {
        $this->authorize('view', $componentType);

        return new ComponentTypeResource($componentType);
    }

    /**
     * @param \App\Http\Requests\ComponentTypeUpdateRequest $request
     * @param \App\Models\ComponentType $componentType
     * @return \Illuminate\Http\Response
     */
    public function update(
        ComponentTypeUpdateRequest $request,
        ComponentType $componentType
    ) {
        $this->authorize('update', $componentType);

        $validated = $request->validated();

        $componentType->update($validated);

        return new ComponentTypeResource($componentType);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentType $componentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ComponentType $componentType)
    {
        $this->authorize('delete', $componentType);

        $componentType->delete();

        return response()->noContent();
    }
}

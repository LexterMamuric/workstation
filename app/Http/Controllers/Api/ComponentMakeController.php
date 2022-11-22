<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ComponentMake;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentMakeResource;
use App\Http\Resources\ComponentMakeCollection;
use App\Http\Requests\ComponentMakeStoreRequest;
use App\Http\Requests\ComponentMakeUpdateRequest;

class ComponentMakeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', ComponentMake::class);

        $search = $request->get('search', '');

        $componentMakes = ComponentMake::search($search)
            ->latest()
            ->paginate();

        return new ComponentMakeCollection($componentMakes);
    }

    /**
     * @param \App\Http\Requests\ComponentMakeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComponentMakeStoreRequest $request)
    {
        $this->authorize('create', ComponentMake::class);

        $validated = $request->validated();

        $componentMake = ComponentMake::create($validated);

        return new ComponentMakeResource($componentMake);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentMake $componentMake
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentMake $componentMake)
    {
        $this->authorize('view', $componentMake);

        return new ComponentMakeResource($componentMake);
    }

    /**
     * @param \App\Http\Requests\ComponentMakeUpdateRequest $request
     * @param \App\Models\ComponentMake $componentMake
     * @return \Illuminate\Http\Response
     */
    public function update(
        ComponentMakeUpdateRequest $request,
        ComponentMake $componentMake
    ) {
        $this->authorize('update', $componentMake);

        $validated = $request->validated();

        $componentMake->update($validated);

        return new ComponentMakeResource($componentMake);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentMake $componentMake
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ComponentMake $componentMake)
    {
        $this->authorize('delete', $componentMake);

        $componentMake->delete();

        return response()->noContent();
    }
}

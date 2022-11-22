<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponentType;
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
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.component_types.index',
            compact('componentTypes', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', ComponentType::class);

        return view('app.component_types.create');
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

        return redirect()
            ->route('component-types.edit', $componentType)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentType $componentType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentType $componentType)
    {
        $this->authorize('view', $componentType);

        return view('app.component_types.show', compact('componentType'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentType $componentType
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ComponentType $componentType)
    {
        $this->authorize('update', $componentType);

        return view('app.component_types.edit', compact('componentType'));
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

        return redirect()
            ->route('component-types.edit', $componentType)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('component-types.index')
            ->withSuccess(__('crud.common.removed'));
    }
}

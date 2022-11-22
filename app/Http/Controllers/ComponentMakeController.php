<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponentMake;
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
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.component_makes.index',
            compact('componentMakes', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', ComponentMake::class);

        return view('app.component_makes.create');
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

        return redirect()
            ->route('component-makes.edit', $componentMake)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentMake $componentMake
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentMake $componentMake)
    {
        $this->authorize('view', $componentMake);

        return view('app.component_makes.show', compact('componentMake'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentMake $componentMake
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ComponentMake $componentMake)
    {
        $this->authorize('update', $componentMake);

        return view('app.component_makes.edit', compact('componentMake'));
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

        return redirect()
            ->route('component-makes.edit', $componentMake)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('component-makes.index')
            ->withSuccess(__('crud.common.removed'));
    }
}

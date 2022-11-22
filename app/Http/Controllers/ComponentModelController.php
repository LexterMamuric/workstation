<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponentModel;
use App\Http\Requests\ComponentModelStoreRequest;
use App\Http\Requests\ComponentModelUpdateRequest;

class ComponentModelController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', ComponentModel::class);

        $search = $request->get('search', '');

        $componentModels = ComponentModel::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.component_models.index',
            compact('componentModels', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', ComponentModel::class);

        return view('app.component_models.create');
    }

    /**
     * @param \App\Http\Requests\ComponentModelStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComponentModelStoreRequest $request)
    {
        $this->authorize('create', ComponentModel::class);

        $validated = $request->validated();

        $componentModel = ComponentModel::create($validated);

        return redirect()
            ->route('component-models.edit', $componentModel)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentModel $componentModel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentModel $componentModel)
    {
        $this->authorize('view', $componentModel);

        return view('app.component_models.show', compact('componentModel'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentModel $componentModel
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ComponentModel $componentModel)
    {
        $this->authorize('update', $componentModel);

        return view('app.component_models.edit', compact('componentModel'));
    }

    /**
     * @param \App\Http\Requests\ComponentModelUpdateRequest $request
     * @param \App\Models\ComponentModel $componentModel
     * @return \Illuminate\Http\Response
     */
    public function update(
        ComponentModelUpdateRequest $request,
        ComponentModel $componentModel
    ) {
        $this->authorize('update', $componentModel);

        $validated = $request->validated();

        $componentModel->update($validated);

        return redirect()
            ->route('component-models.edit', $componentModel)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentModel $componentModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ComponentModel $componentModel)
    {
        $this->authorize('delete', $componentModel);

        $componentModel->delete();

        return redirect()
            ->route('component-models.index')
            ->withSuccess(__('crud.common.removed'));
    }
}

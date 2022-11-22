<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ComponentModel;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentModelResource;
use App\Http\Resources\ComponentModelCollection;
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
            ->paginate();

        return new ComponentModelCollection($componentModels);
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

        return new ComponentModelResource($componentModel);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentModel $componentModel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentModel $componentModel)
    {
        $this->authorize('view', $componentModel);

        return new ComponentModelResource($componentModel);
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

        return new ComponentModelResource($componentModel);
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

        return response()->noContent();
    }
}

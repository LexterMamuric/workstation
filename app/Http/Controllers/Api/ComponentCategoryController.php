<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ComponentCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentCategoryResource;
use App\Http\Resources\ComponentCategoryCollection;
use App\Http\Requests\ComponentCategoryStoreRequest;
use App\Http\Requests\ComponentCategoryUpdateRequest;

class ComponentCategoryController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', ComponentCategory::class);

        $search = $request->get('search', '');

        $componentCategories = ComponentCategory::search($search)
            ->latest()
            ->paginate();

        return new ComponentCategoryCollection($componentCategories);
    }

    /**
     * @param \App\Http\Requests\ComponentCategoryStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComponentCategoryStoreRequest $request)
    {
        $this->authorize('create', ComponentCategory::class);

        $validated = $request->validated();

        $componentCategory = ComponentCategory::create($validated);

        return new ComponentCategoryResource($componentCategory);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentCategory $componentCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentCategory $componentCategory)
    {
        $this->authorize('view', $componentCategory);

        return new ComponentCategoryResource($componentCategory);
    }

    /**
     * @param \App\Http\Requests\ComponentCategoryUpdateRequest $request
     * @param \App\Models\ComponentCategory $componentCategory
     * @return \Illuminate\Http\Response
     */
    public function update(
        ComponentCategoryUpdateRequest $request,
        ComponentCategory $componentCategory
    ) {
        $this->authorize('update', $componentCategory);

        $validated = $request->validated();

        $componentCategory->update($validated);

        return new ComponentCategoryResource($componentCategory);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentCategory $componentCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        ComponentCategory $componentCategory
    ) {
        $this->authorize('delete', $componentCategory);

        $componentCategory->delete();

        return response()->noContent();
    }
}

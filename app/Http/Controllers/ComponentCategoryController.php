<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponentCategory;
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
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.component_categories.index',
            compact('componentCategories', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', ComponentCategory::class);

        return view('app.component_categories.create');
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

        return redirect()
            ->route('component-categories.edit', $componentCategory)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentCategory $componentCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComponentCategory $componentCategory)
    {
        $this->authorize('view', $componentCategory);

        return view(
            'app.component_categories.show',
            compact('componentCategory')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComponentCategory $componentCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ComponentCategory $componentCategory)
    {
        $this->authorize('update', $componentCategory);

        return view(
            'app.component_categories.edit',
            compact('componentCategory')
        );
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

        return redirect()
            ->route('component-categories.edit', $componentCategory)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('component-categories.index')
            ->withSuccess(__('crud.common.removed'));
    }
}

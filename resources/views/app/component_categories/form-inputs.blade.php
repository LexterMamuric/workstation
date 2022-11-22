@php $editing = isset($componentCategory) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full lg:w-6/12">
        <x-inputs.text
            name="component_category"
            label="Component Category"
            :value="old('component_category', ($editing ? $componentCategory->component_category : ''))"
            maxlength="255"
            placeholder="Component Category"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>

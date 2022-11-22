@php $editing = isset($componentType) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full lg:w-6/12">
        <x-inputs.text
            name="component_type"
            label="Component Type"
            :value="old('component_type', ($editing ? $componentType->component_type : ''))"
            maxlength="255"
            placeholder="Component Type"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>

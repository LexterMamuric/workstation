@php $editing = isset($componentModel) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full lg:w-6/12">
        <x-inputs.text
            name="component_model"
            label="Component Model"
            :value="old('component_model', ($editing ? $componentModel->component_model : ''))"
            maxlength="255"
            placeholder="Component Model"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>

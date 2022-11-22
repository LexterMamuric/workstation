@php $editing = isset($componentMake) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full lg:w-6/12">
        <x-inputs.text
            name="component_make"
            label="Component Make"
            :value="old('component_make', ($editing ? $componentMake->component_make : ''))"
            maxlength="255"
            placeholder="Component Make"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>

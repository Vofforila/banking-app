<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">

            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>

    <div x-data="{ theme: $persist('default') }"
         x-init="$watch('theme', val => document.documentElement.setAttribute('data-theme', val))">
        <flux:select x-model="theme" placeholder="Choose Theme...">
            <flux:select.option value="default">Default</flux:select.option>
            <flux:select.option value="red">Red</flux:select.option>
            <flux:select.option value="orange">Orange</flux:select.option>
            <flux:select.option value="amber">Amber</flux:select.option>
            <flux:select.option value="yellow">Yellow</flux:select.option>
            <flux:select.option value="lime">Lime</flux:select.option>
            <flux:select.option value="green">Green</flux:select.option>
            <flux:select.option value="emerald">Emerald</flux:select.option>
            <flux:select.option value="teal">Teal</flux:select.option>
            <flux:select.option value="cyan">Cyan</flux:select.option>
            <flux:select.option value="sky">Sky</flux:select.option>
            <flux:select.option value="blue">Blue</flux:select.option>
            <flux:select.option value="indigo">Indigo</flux:select.option>
            <flux:select.option value="purple">Purple</flux:select.option>
            <flux:select.option value="fuchsia">Fuchsia</flux:select.option>
            <flux:select.option value="pink">Pink</flux:select.option>
            <flux:select.option value="rose">Rose</flux:select.option>
        </flux:select>
    </div>

</section>

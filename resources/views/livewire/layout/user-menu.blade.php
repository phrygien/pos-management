<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\{on, state};

state(['user' => fn() => Auth::user()]);
on(['user-updated' => fn() => $this->user->refresh()]);

$logout = function () {
    Auth::logout();

    $this->redirect(url: route('login'));
}; ?>

<x-mary-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
    <x-slot:actions>
        <x-mary-dropdown>
            <x-slot:trigger>
                <x-mary-button icon="o-cog-6-tooth" class="btn-circle btn-ghost btn-xs" />
            </x-slot:trigger>

            <x-mary-menu-item title="Profile" icon="o-user" link="/profile" />
            <x-mary-menu-item title="Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />
            <x-mary-menu-item title="Logout" icon="o-power" wire:click="logout" />
        </x-mary-dropdown>
    </x-slot:actions>
</x-mary-list-item>

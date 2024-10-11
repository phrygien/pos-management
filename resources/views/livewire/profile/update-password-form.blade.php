<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <x-mary-input label="Current Password" required wire:model="current_password"
                id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full border-0 rounded-lg ring-1 ring-inset ring-gray-200"
                autocomplete="current-password" />
        </div>

        <div>
            <x-mary-input label="New Password" required wire:model="password" id="update_password_password"
                name="password" type="password"
                class="mt-1 block w-full border-0 rounded-lg ring-1 ring-inset ring-gray-200"
                autocomplete="new-password" />
        </div>

        <div>
            <x-mary-input label="Confirm Password" required wire:model="password_confirmation"
                id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full border-0 rounded-lg ring-1 ring-inset ring-gray-200"
                autocomplete="new-password" />
        </div>

        <div class="flex items-center gap-4">
            <x-mary-button label="Enregistrer" class="btn-primary" type="submit" spinner="updatePassword" />
            <x-action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>

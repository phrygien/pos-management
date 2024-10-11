<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-mary-header title="Create Product" subtitle="Manage products">
                <x-slot:middle class="!justify-end">
                    {{-- <x-mary-input icon="o-bolt" placeholder="Search..." /> --}}
                </x-slot:middle>
                <x-slot:actions>
                    {{-- <x-mary-button icon="o-funnel" /> --}}
                    <x-mary-button class="uppercase btn-primary" label="Back to products" />
                </x-slot:actions>
            </x-mary-header>

            <livewire:catalogues.product.product-create />

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-mary-header title="Produits" subtitle="Gestion des produits">
                <x-slot:middle class="!justify-end">
                </x-slot:middle>
                <x-slot:actions>
                    <x-mary-button class="uppercase btn-primary" label="Ajouter un produit" />
                </x-slot:actions>
            </x-mary-header>

            <livewire:catalogues.product.product-list />

        </div>
    </div>
</x-app-layout>

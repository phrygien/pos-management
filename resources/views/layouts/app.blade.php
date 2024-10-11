<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- The navbar with `sticky` and `full-width` --}}
        <x-mary-nav sticky full-width>

            <x-slot:brand>
                {{-- Drawer toggle for "main-drawer" --}}
                <label for="main-drawer" class="mr-3 lg:hidden">
                    <x-mary-icon name="o-bars-3" class="cursor-pointer" />
                </label>

                {{-- Brand --}}
                <div>
                    <img src="{{ asset('images/logo/logo-default.png') }}" class="w-auto h-10" />
                </div>
            </x-slot:brand>

            {{-- Right side actions --}}
            <x-slot:actions>
                <x-mary-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
                <x-mary-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm"
                    responsive />
            </x-slot:actions>
        </x-mary-nav>
        {{-- The main content with `full-width` --}}
        <x-mary-main with-nav full-width>

            {{-- This is a sidebar that works also as a drawer on small screens --}}
            {{-- Notice the `main-drawer` reference here --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

                {{-- User --}}
                @if ($user = auth()->user())
                    <livewire:layout.user-menu />
                    <x-mary-menu-separator />
                @endif

                {{-- Activates the menu item when a route matches the `link` property --}}
                <x-mary-menu activate-by-route>
                    <x-mary-menu-item title="Tableau de bord" icon="o-home" link="/dashboard" />
                    <x-mary-menu-sub title="Catalogues" icon="o-cube">
                        <x-mary-menu-item title="Categories produits" icon="o-tag" link="###" />
                        <x-mary-menu-item title="Marques" icon="o-rectangle-stack" link="###" />
                        <x-mary-menu-item title="Tous les produits" icon="o-rectangle-stack"
                            link="/catalogues/products" />
                        <x-mary-menu-item title="Ajouter un produit" icon="o-archive-box"
                            link="/catalogues/products/create" />
                        <x-mary-menu-item title="Vartiation des produits" icon="o-archive-box-arrow-down"
                            link="####" />
                        <x-mary-menu-item title="Gestion codes-barres" icon="o-archive-box" link="####" />
                    </x-mary-menu-sub>
                    <x-mary-menu-sub title="Stock" icon="o-building-storefront">
                        <x-mary-menu-item title="Gestion du stock" icon="o-document-chart-bar" link="####" />
                        <x-mary-menu-item title="Historique des stocks" icon="o-chart-pie" link="####" />
                        <x-mary-menu-item title="Alertes de stock" icon="o-exclamation-triangle" link="####" />
                    </x-mary-menu-sub>
                </x-mary-menu>
            </x-slot:sidebar>

            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>

            {{-- The `$footer` goes here --}}
            {{-- <x-slot:footer>
            <hr />
            <div class="p-6">
                Footer section
            </div>
        </x-slot:footer> --}}
        </x-mary-main>

        {{--  TOAST area --}}
        <x-mary-toast />
    </div>
</body>

</html>

<x-app-layout>

    <div class="max-w-4xl mx-auto my-20 space-y-2">
        <h1 class="text-7xl font-bold tracking-tight">Hello to {{ config("app.name") }}</h1>
        <p class="leading-loose text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque eligendi fugiat ipsa ipsam odio provident, tenetur. Aliquam blanditiis deleniti explicabo fuga labore minima quasi quidem repudiandae,
            sapiente vero
            voluptatem voluptatibus.</p>

        @if (Auth::user()?->ethereum_address)
            <div class="px-4 py-2 text-sm text-gray-500">
                Ethereum: {{ substr(Auth::user()->ethereum_address, 0, 8) }}...{{ substr(Auth::user()->ethereum_address, -4) }}
            </div>
        @endif
    </div>

</x-app-layout>

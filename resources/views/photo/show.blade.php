<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($photo->name) }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto">
                <img src="https://res.cloudinary.com/dtwzikt2h/image/upload/v1674611280/gambar/{{ $photo->picture }}">
            </div>
        </div>
    </div>
</x-app-layout>

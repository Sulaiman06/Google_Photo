<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Foto') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form style="display: inline-block" method="GET">
                            <input type="text" name="keyword" placeholder="Search">
                            <x-primary-button>Search</x-primary-button>
                        </form>
                        <x-primary-button class="ml-4">
                            <a href="/photo/create">Upload Gambar</a>
                        </x-primary-button>
                        <x-primary-button class="ml-4">
                            <a href="/photos/trash">recycle bin</a>
                        </x-primary-button>
                    </div>
                    @if(Session::has('status'))
                        <div class="p-6 bg-white border-b border-gray-200">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    @foreach($photos as $photo)
                        <div class="p-6 bg-white border-b border-gray-200">
                            <a href="{{ route('photo.show', $photo->id) }}"><img src="{{ asset('storage/gambar/' . $photo->picture) }}" style="width:200px;"></a><br>
                            <a href="{{ route('photo.edit', $photo->id) }}" style="color:green;">Edit</a>
                            <form action="/photos/{{$photo->id}}" method="post">
                                @csrf
                                <button type="submit" style="color:red;">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                    <div class="p-6 bg-white border-b border-gray-200">
                        {{ $photos->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

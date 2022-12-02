<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recycle Bin') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Session::has('status'))
                        <div class="p-6 bg-white border-b border-gray-200">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    @foreach($photo as $foto)
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h1>{{$foto->name}}</h1>
                            <a href="/photos/{{$foto->id}}/restore" style="color:green;">Restore</a>
                            <form action="{{ route('photo.destroy', $foto->id) }}" method="post">
                                @csrf
                                <button type="submit" style="color:red;">Hapus</button>
                                @method('delete')
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
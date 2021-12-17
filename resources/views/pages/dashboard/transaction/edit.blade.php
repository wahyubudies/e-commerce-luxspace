<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 leading-tight">
            <a href="{{ route('dashboard.transaction.index') }}" class="text-pink-400 cursor-pointer">Transaction</a> &raquo; Edit &raquo; {{ $transaction->name }}
        </h2>
    </x-slot>

    <div class="py-12">
       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                @if ( $errors->any() )
                    <div class="mb-5" role="alert">
                        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2"> There's something wrong! </div>
                        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-3 py-3">
                            <p>
                                <ul>
                                    @foreach ( $errors->all() as $error )
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <form action="{{ route('dashboard.transaction.update', $transaction->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex flex-wrap mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Status</label>
                        <select name="status" class="block w-full bg-gray-200 text-gray-700 border-2 border-gray-200 rounded py-2 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            <option value="{{ $transaction->status }}">{{ $transaction->status }}</option>
                            <option disabled>----------</option>
                            <option value="PENDING">PENDING</option>
                            <option value="SUCCESS">SUCCESS</option>
                            <option value="CANCELED">CANCELED</option>
                            <option value="FAILED">FAILED</option>
                            <option value="SHIPPING">SHIPPING</option>
                            <option value="SHIPPED">SHIPPED</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap mx-3 mb-6">
                    <div class="w-full px-3">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace( 'description' );
        </script>
    </x-slot>
</x-app-layout>

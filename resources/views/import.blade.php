<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Import Data Projects
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
        <div 
            id="success-toast" 
            class="fixed top-10 right-10 flex items-center text-white text-sm font-medium px-4 py-3 rounded-lg shadow-lg z-50"
            style="min-width: 250px; background-color: #4CAF50;">
            <span class="flex-1 text-center">{{ session('success') }}</span>
            <button 
                onclick="document.getElementById('success-toast').remove()" 
                class="ml-4 text-white hover:text-gray-200 focus:outline-none">&times;
            </button>
        </div>

        <script>
            setTimeout(() => {
                let toast = document.getElementById('success-toast');
                if (toast) {
                    toast.remove();
                }
            }, 3000); // hilang setelah 3 detik
        </script>
        @endif


            @if($errors->any())
                <div class="mb-4 font-medium text-sm text-red-600">
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.import') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
                @csrf
                <div>
                    <label class="block text-base font-medium text-gray-700">Download Template Import Excel</label>
                    📥  <a href="{{ asset('storage/Template_Import_Excel.xlsx') }}" class="text-blue-600 italic underline" download>
                            Download Template
                        </a>


                </div><br>

                <label class="block text-lg font-medium text-gray-700 mb-2">Upload File Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full border border-black focus:border-grey focus:ring-0 rounded p-1 focus:bg-green-100" required>
                <br><br>                                                                      

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Import
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

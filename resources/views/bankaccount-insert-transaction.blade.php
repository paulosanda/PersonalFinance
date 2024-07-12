<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <p class="text-sm font-medium text-gray-500 dark:text-white-900 ml-2.5 mt-1.5 mr-1.5 mb-1.5">
                    <b> Você pode inserir os lançamentos em suas contas correntes manualmente ou importando o arquivo OFX caso seu banco ofereça esta facilidade.</b>
                </p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <form method="post" action="{{ route('bank-account-transaction.upload') }}" enctype="multipart/form-data" class="space-y-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @csrf
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Enviar arquivo OFX</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg
                        cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                        dark:placeholder-gray-400" name="file_ofx" id="file_ofx" type="file" accept=".ofx">
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                        focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
                </div>
            </form>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">

        </div>
    </div>

</x-app-layout>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-start gap-1">
                <button data-modal-target="create-bank-account-modal" data-modal-toggle="create-bank-account-modal"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none
                        focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600
                        dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Nova conta bancária
                </button>
                <button data-modal-target="insert-transactions-on-a-bank-account" data-modal-toggle="insert-transactions-on-a-bank-account"
                        class=" text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none
                        focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-700
                        dark:hover:bg-red-800 dark:focus:ring-red-900" type="button">
                    Lançamentos
                </button>

            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($bankAccounts != null)
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul>
                        @foreach($bankAccounts as $bankAccount)
                        <li>{{ $bankAccount->bank_name }}</li>
                         @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!--- modal de cadastramento de conta bancária --->
    <div id="create-bank-account-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0
            right-0 left-0 z-50 justify-center items-center w-full
            md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Cadastre nova conta bancária
                    </h3>
                    <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                        rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center
                        dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="create-bank-account-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form method="post" class="space-y-4" action="{{ route('bank-account.store') }}">

                        <div>
                            @csrf
                            <label for="bank_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numero do banco</label>
                            <input type="text" name="bank_number" id="bank_number" class="bg-gray-50 border border-gray-300
                                text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="numero do banco" required {{ old('bank_number') }}/>
                        </div>
                        <div>
                            <label for="bank_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banco</label>
                            <input type="text" name="bank_name" id="bank_name" placeholder="nome do banco" class="bg-gray-50 border
                                border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block
                                w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required {{ old('bank_name') }} />
                        </div>
                        <div>
                            <label for="bank_branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agência</label>
                            <input type="text" name="bank_branch" id="bank_branch" placeholder="agência" class="bg-gray-50 border
                                border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block
                                w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required {{ old('bank_branch') }}/>
                        </div>
                        <div>
                            <label for="bank_account" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Conta</label>
                            <input type="text" name="bank_account" id="bank_account" placeholder="conta" class="bg-gray-50 border
                                border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block
                                w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required {{ old('bank_account') }}/>
                        </div>


                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                        focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">criar conta</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--- insert-transactions-on-a-bank-account --->
    <div id="insert-transactions-on-a-bank-account" tabindex="-1" aria-hidden="true"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0
            right-0 left-0 z-50 justify-center items-center w-full
            md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Lançamentos
                    </h3>
                    <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                        rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center
                        dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="insert-transactions-on-a-bank-account">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form method="post" action="{{ route('bank-account-transaction.upload') }}" class="space-y-4">
                        <div>
                            @csrf
                            <p>Você pode fazer o arquivo ofx de seu extrato.</p>
                        </div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Enviar arquivo OFX</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg
                        cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                        dark:placeholder-gray-400" id="file_input" type="file">
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                        focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
                    </form>
                    <div>
                        <p>Ou use o formulário abaixo para lançamentos manuais</p>
                    </div>
                    <div>
                        <form method="post" class="space-y-4" action="{{ route('bank-account.store') }}">
                            <div>
                                @csrf
                                <label for="bank_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numero do banco</label>
                                <input type="text" name="bank_number" id="bank_number" class="bg-gray-50 border border-gray-300
                                text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="numero do banco" required {{ old('bank_number') }}/>
                            </div>
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                        focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">criar conta</button>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

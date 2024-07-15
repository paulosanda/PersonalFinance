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

            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
                @if($bankAccounts != null)
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Banco
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Agencia / Conta
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Titular
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Saldo atual
                                </th>
                            </tr>

                        @foreach($bankAccounts as $bankAccount)
                                <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $bankAccount->bank_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $bankAccount->bank_branch . ' / ' . $bankAccount->bank_account }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $bankAccount->bank_account_owner_name }}
                                    </td>
                                    <td class="px-6 py-4 ">
                                        {!! formatCurrency($bankAccount->latestBalance->balance) !!}
                                    </td>
                         @endforeach
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th colspan="3" scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Saldo consolidado</th>
                                    <td class="px-6 py- 4 font-medium {{ $bankAccounts->sum(fn($account) => $account->latestBalance->balance) < 0 ? 'text-red-500' : '' }}">
                                        R$ {!! formatMoneyToInput($bankAccounts->sum(fn($account) => $account->latestBalance->balance)) !!}
                                    </td>
                                </tr>
                                </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @foreach($bankAccounts as $bankAccount)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th colspan="4" scope="col" class="px-6 py-3">
                                    {{ $bankAccount->bank_name }} -  {{ $bankAccount->bank_branch }}/{{ $bankAccount->bank_account }}
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" class="px-6 py-3">data</th>
                                <th scope="col" class="px-6 py-3">histórico</th>
                                <th scope="col" class="px-6 py-3">valor</th>
                            </tr>
                            </thead>
                            @php $actualDate = null @endphp
                            @foreach($bankAccount->latestTransactions->sortBy('date') as $transaction)
                                @if($actualDate !== $transaction->date)
                                  @php
                                        $balance = $transaction->bankAccount->balance()->where('date',(string)$transaction->date)->first() ?
                                        $transaction->bankAccount->balance()->where('date',(string)$transaction->date)->first()->value('balance') : 'Valor não encontrado'
                                    @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" colspan="3" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white bg-gray-600">
                                            <div class="flex justify-end">
                                                <span>{{ date_format(date_create($transaction->date), 'd/m/Y') }}</span>

                                                <span class="ml-4">R$  {{ $balance }}</span>
                                            </div>
                                        </th>

                                    </tr>
                                @endif

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ date_format(date_create($transaction->date), 'd/m/Y')  }}
                                    </th>
                                    <td class="px-6 py-4">
                                      {{ $transaction->history }}
                                    </td>
                                    <td class="px-6 py-4">
                                       {!! formatCurrency($transaction->amount) !!}
                                    </td>
                                </tr>
                            @php $actualDate = $transaction->date @endphp
                            @endforeach

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

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

                <!-- Modal body-->
                <div class="p-4 md:p-5">
                    <form method="post" class="space-y-4" action="{{ route('bank-account.store') }}">

                        <div>
                            @csrf
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Selecione o tipo de pessoa</h4>
                            <div class="flex items-center mb-4">
                                <input checked id="default-radio-1" type="radio" value="personal" name="type"
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500
                                       dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 ml-2">
                                <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 ml-4 mr-4">Pessoa física</label>

                                <input id="default-radio-2" type="radio" value="company" name="type"
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500
                                       dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 ml-2">
                                <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 ml-4 mr-4">Pessoa jurídica</label>
                            </div>
                            </div>

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
                            <label for="bank_account_owner_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do titular</label>
                            <input type="text" name="bank_account_owner_name" id="bank_account_owner_name" placeholder="titular da conta" class="bg-gray-50 border
                                border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block
                                w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" {{ old('bank_account_owner_name') }} />
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
                        <div>
                            <label for="initial_balance" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banco</label>
                            <input type="text" name="initial_balance" id="initial_balance" placeholder="saldo inicial" class="bg-gray-50 border
                                border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block
                                w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"  {{ old('initial_balance') }} />
                        </div>


                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                        focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">criar conta</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-layouts::app.sidebar :title="'Dashboard'">
    <flux:main>
        <div class="min-h-screen flex flex-col justify-center px-8 py-16 max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-[0.3em] uppercase text-zinc-400 mb-4">
                    Personal Finance
                </p>
                <h1 class="text-6xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100 leading-none mb-6">
                    Smart <br>
                    <span class="text-blue-500">Banking.</span>
                </h1>
                <p class="text-zinc-500 dark:text-zinc-400 text-lg max-w-md leading-relaxed">
                    Track your spending, analyze your finances and take control of your money.
                </p>
            </div>

            {{-- Stats Row --}}
            {{--            <div class="grid grid-cols-3 gap-4 mb-12">--}}
            {{--                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
            {{--                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Transactions</p>--}}
            {{--                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">--}}
            {{--                        {{ auth()->user()->transactions()->count() }}--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
            {{--                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Spent</p>--}}
            {{--                    <p class="text-3xl font-bold text-red-500">--}}
            {{--                        {{ number_format(abs(auth()->user()->transactions()->where('amount', '<', 0)->sum('amount')), 2) }}--}}
            {{--                        <span class="text-sm font-normal text-zinc-400">RON</span>--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
            {{--                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Income</p>--}}
            {{--                    <p class="text-3xl font-bold text-green-500">--}}
            {{--                        {{ number_format(auth()->user()->transactions()->where('amount', '>', 0)->sum('amount'), 2) }}--}}
            {{--                        <span class="text-sm font-normal text-zinc-400">RON</span>--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            {{-- Upload Section --}}
            <div
                class="bg-zinc-50 dark:bg-zinc-800 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-2xl p-10 text-center">
                <div class="text-4xl mb-4">📂</div>
                <p class="text-zinc-600 dark:text-zinc-300 font-medium mb-2">Import your bank statement</p>
                <p class="text-zinc-400 text-sm mb-6">Upload a CSV file exported from your bank</p>

                <form action="{{ route('import.storeTransactions') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input
                        type="file"
                        name="statement"
                        id="statementFile"
                        class="hidden"
                        onchange="this.form.submit()"
                    >
                    <flux:button type="button" variant="primary"
                                 onclick="document.getElementById('statementFile').click()">
                        Upload Statement
                    </flux:button>
                </form>
            </div>

            {{-- Quick Links --}}
            {{--            <div class="grid grid-cols-2 gap-4 mt-6">--}}
            {{--                <a href="{{ route('transactions.index') }}"--}}
            {{--                   class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700 hover:border-blue-300 transition-colors group">--}}
            {{--                    <span class="text-2xl">📊</span>--}}
            {{--                    <div>--}}
            {{--                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-blue-500 transition-colors">--}}
            {{--                            Transactions</p>--}}
            {{--                        <p class="text-xs text-zinc-400">View all your transactions</p>--}}
            {{--                    </div>--}}
            {{--                </a>--}}
            {{--                <a href="{{ route('transaction.add') }}"--}}
            {{--                   class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700 hover:border-blue-300 transition-colors group">--}}
            {{--                    <span class="text-2xl">➕</span>--}}
            {{--                    <div>--}}
            {{--                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-blue-500 transition-colors">--}}
            {{--                            Add Transaction</p>--}}
            {{--                        <p class="text-xs text-zinc-400">Manually log a transaction</p>--}}
            {{--                    </div>--}}
            {{--                </a>--}}
            {{--            </div>--}}

        </div>
    </flux:main>
</x-layouts::app.sidebar>

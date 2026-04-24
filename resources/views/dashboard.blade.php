<x-layouts::app.sidebar :title="'Dashboard'">
    <flux:main>

        @auth
            {{-- LOGGED IN USER DASHBOARD --}}
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

                {{--                Stats Row--}}
                {{--                <div class="grid grid-cols-3 gap-4 mb-12">--}}
                {{--                    <div--}}
                {{--                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
                {{--                        <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Transactions</p>--}}
                {{--                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">--}}
                {{--                            {{ auth()->user()->transactions()->count() }}--}}
                {{--                        </p>--}}
                {{--                    </div>--}}
                {{--                    <div--}}
                {{--                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
                {{--                        <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Spent</p>--}}
                {{--                        <p class="text-3xl font-bold text-red-500">--}}
                {{--                            {{ number_format(abs(auth()->user()->transactions()->where('amount', '<', 0)->sum('amount')), 2) }}--}}
                {{--                            <span class="text-sm font-normal text-zinc-400">RON</span>--}}
                {{--                        </p>--}}
                {{--                    </div>--}}
                {{--                    <div--}}
                {{--                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700">--}}
                {{--                        <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Income</p>--}}
                {{--                        <p class="text-3xl font-bold text-green-500">--}}
                {{--                            {{ number_format(auth()->user()->transactions()->where('amount', '>', 0)->sum('amount'), 2) }}--}}
                {{--                            <span class="text-sm font-normal text-zinc-400">RON</span>--}}
                {{--                        </p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{-- Upload Section --}}
                <div
                    class="bg-zinc-50 dark:bg-zinc-800 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-2xl p-10 flex flex-col items-center text-center">
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
                            accept=".csv,.pdf,.jpg,.jpeg"
                            onchange="validateFile(this)">

                        <flux:button type="button" variant="primary"
                                     onclick="document.getElementById('statementFile').click()">
                            Upload Statement
                        </flux:button>

                        <p id="fileError" class="text-red-500 text-xs mt-2 hidden">
                            ⚠️ Only CSV, PDF, JPG or JPEG files are allowed.
                        </p>
                    </form>

                    <script>
                        function validateFile(input) {
                            const allowed = ['csv', 'pdf', 'jpg', 'jpeg'];
                            const file = input.files[0];

                            if (!file) return;

                            const ext = file.name.split('.').pop().toLowerCase();
                            const error = document.getElementById('fileError');

                            if (!allowed.includes(ext)) {
                                error.classList.remove('hidden');
                                input.value = '';
                            }

                            error.classList.add('hidden');
                            input.form.submit();
                        }
                    </script>
                </div>

                {{-- Quick Links --}}
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('transactions.index') }}"
                       class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700 hover:border-blue-300 transition-colors group">
                        <span class="text-2xl">📊</span>
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-blue-500 transition-colors">
                                Transactions</p>
                            <p class="text-xs text-zinc-400">View all your transactions</p>
                        </div>
                    </a>
                    <a href="{{ route('transaction.add') }}"
                       class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-700 hover:border-blue-300 transition-colors group">
                        <span class="text-2xl">➕</span>
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-blue-500 transition-colors">
                                Add Transaction</p>
                            <p class="text-xs text-zinc-400">Manually log a transaction</p>
                        </div>
                    </a>
                </div>

            </div>
        @endauth

        @guest
            {{-- GUEST LANDING PAGE --}}
            <div
                class="min-h-screen flex flex-col items-center justify-center px-8 py-16 text-center max-w-3xl mx-auto">

                <p class="text-xs font-semibold tracking-[0.3em] uppercase text-zinc-400 mb-4">
                    Personal Finance
                </p>
                <h1 class="text-7xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100 leading-none mb-6">
                    Smart <span class="text-blue-500">Banking.</span>
                </h1>
                <p class="text-zinc-500 dark:text-zinc-400 text-xl max-w-lg leading-relaxed mb-10">
                    Take control of your money. Track spending, analyze finances and understand where your money goes.
                </p>

                <div class="flex gap-4 mb-16">
                    <a href="{{ route('register') }}">
                        <flux:button variant="primary" size="sm">Get Started</flux:button>
                    </a>
                    <a href="{{ route('login') }}">
                        <flux:button variant="outline" size="sm">Sign In</flux:button>
                    </a>
                </div>

                {{-- Feature Cards --}}
                <div class="grid grid-cols-3 gap-4 w-full">
                    <div
                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-700 text-left">
                        <span class="text-3xl mb-3 block">📥</span>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Import Statements</p>
                        <p class="text-xs text-zinc-400">Upload your bank CSV and we parse it automatically</p>
                    </div>
                    <div
                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-700 text-left">
                        <span class="text-3xl mb-3 block">🏷️</span>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Auto Categories</p>
                        <p class="text-xs text-zinc-400">Transactions are automatically categorized for you</p>
                    </div>
                    <div
                        class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-700 text-left">
                        <span class="text-3xl mb-3 block">📊</span>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Track Spending</p>
                        <p class="text-xs text-zinc-400">See where your money goes with clear analytics</p>
                    </div>
                </div>

            </div>
        @endguest

    </flux:main>
</x-layouts::app.sidebar>

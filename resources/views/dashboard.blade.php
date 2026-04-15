<x-layouts::app.sidebar :title="'Dashboard'">
    <flux:main>
        <div class="row primary">
            <div style="max-width:1100px; margin:0 auto;">
                <nav style="display:flex; justify-content:space-between; margin-bottom:40px;">
                    <strong>BankingApp</strong>

                    <a href="/transactions" style="color:white;">
                        Transactions
                    </a>
                </nav>
                <h1 style="font-size:64px; margin:0;">
                    Smart Banking
                </h1>
                <p style="max-width:600px; font-size:16px;">
                    Track your spending, analyze your finances and take control of your money.
                </p>
                <button style="
                    margin-top:20px;
                    padding:12px 20px;
                    background:white;
                    border:none;
                    font-weight:600;
                    cursor:pointer;
                ">
                    Get Started
                </button>
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>

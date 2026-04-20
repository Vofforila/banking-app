<x-layouts::app.sidebar :title="'Dashboard'">
    <flux:main>
        <div class="row primary">
            <div style="max-width:1100px; margin:0 auto;">
                <h1 style="font-size:64px; margin:0;">
                    Smart Banking
                </h1>
                <p style="max-width:600px; font-size:16px;">
                    Track your spending, analyze your finances and take control of your money.
                </p>
                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input
                        type="file"
                        name="statement"
                        id="statementFile"
                        class="hidden"
                        onchange="this.form.submit()"
                    >

                    <flux:button type="button" onclick="document.getElementById('statementFile').click()">
                        Upload Statement
                    </flux:button>
                </form>
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>

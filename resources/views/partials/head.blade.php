<script>
    (function () {
        const theme = localStorage.getItem('_x_theme') || 'default';
        const dark = localStorage.getItem('_x_darkMode') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
        if (dark === 'dark') document.documentElement.classList.add('dark');
    })();
</script>

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>VOF Banking</title>

<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>


@vite(['resources/css/app.css', 'resources/js/app.js','resources/css/index.css'])
@fluxAppearance


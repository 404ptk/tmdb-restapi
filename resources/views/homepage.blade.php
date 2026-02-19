<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies (Livewire)</title>
    @livewireStyles
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --panel-2: #0b1220;
            --border: #1f2937;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #38bdf8;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif;
            background: radial-gradient(1000px 600px at 20% -10%, #1f2937 0%, #0f172a 45%);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 980px;
            margin: 3rem auto;
            background: linear-gradient(180deg, #111827 0%, #0b1220 100%);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.45);
        }
        h1 { font-size: 2rem; margin: 0 0 1.25rem; letter-spacing: -0.02em; }
        .subtle { color: var(--muted); margin-bottom: 1.5rem; }

        .controls { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .select {
            background: #0b1220;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
        }

        .table-wrap { overflow: hidden; border-radius: 12px; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #0b1220; }
        th, td { padding: 0.75rem 0.9rem; text-align: left; font-size: 0.92rem; }
        th { color: var(--muted); font-weight: 600; }
        tbody tr { border-top: 1px solid var(--border); }
        tbody tr:nth-child(2n) { background: rgba(17,24,39,.4); }

        .pager { display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; gap: 1rem; }
        .pager-info { color: var(--muted); font-size: 0.9rem; }
        .btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.45rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn:hover { border-color: var(--accent); color: var(--accent); }
        .btn[disabled] { opacity: .5; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Movies List</h1>
        <livewire:movies-table />
    </div>
    @livewireScripts
</body>
</html>

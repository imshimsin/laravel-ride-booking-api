<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Ride Booking</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        a { color: #38bdf8; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .header { background: #1e293b; border-bottom: 1px solid #334155; padding: 1rem 0; }
        .header h1 { font-size: 1.25rem; font-weight: 600; }
        .header a { color: #e2e8f0; }
        .header a:hover { color: #38bdf8; text-decoration: none; }
        .card { background: #1e293b; border-radius: 0.5rem; border: 1px solid #334155; overflow: hidden; }
        .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #334155; font-weight: 600; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 0.75rem 1.25rem; text-align: left; border-bottom: 1px solid #334155; }
        .table th { background: #0f172a; font-weight: 500; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .table tr:hover td { background: rgba(56, 189, 248, 0.05); }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-accepted { background: #dbeafe; color: #1e40af; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-approved { background: #dbeafe; color: #1e40af; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .btn { display: inline-block; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; border: none; }
        .btn-primary { background: #38bdf8; color: #0f172a; }
        .btn-primary:hover { background: #0ea5e9; }
        .btn-ghost { background: transparent; color: #94a3b8; }
        .btn-ghost:hover { background: #334155; color: #e2e8f0; }
        .detail-grid { display: grid; gap: 1.5rem; }
        .detail-section { background: #1e293b; border-radius: 0.5rem; border: 1px solid #334155; padding: 1.25rem; }
        .detail-section h3 { font-size: 0.875rem; color: #94a3b8; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .detail-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #334155; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #94a3b8; }
        .detail-value { font-weight: 500; }
        .pagination { display: flex; gap: 0.5rem; margin-top: 1.5rem; align-items: center; list-style: none; }
        .pagination li { display: inline; }
        .pagination a, .pagination span { padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #1e293b; border: 1px solid #334155; color: #e2e8f0; }
        .pagination a:hover { background: #334155; }
        .pagination .disabled span { color: #64748b; cursor: not-allowed; }
        .empty-state { padding: 3rem 2rem; text-align: center; color: #64748b; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="{{ route('admin.rides.index') }}">
                <h1>Ride Booking Admin</h1>
            </a>
        </div>
    </header>
    <main class="container" style="padding: 2rem 0;">
        @yield('content')
    </main>
</body>
</html>

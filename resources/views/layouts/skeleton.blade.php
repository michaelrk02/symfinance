<html>
    <head>
        <link rel="stylesheet" href="/assets/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons.css">
        <script src="/assets/bootstrap/bootstrap.bundle.min.js"></script>

        <title>@yield('title') - Symfinance</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
    </head>
    <body>
        <div class="d-flex flex-column" style="min-height: 100vh">
            <div class="flex-grow-1">
                <div class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-lg">
                        <a class="navbar-brand" href="{{ url('/') }}">Symfinance</a>
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar-nav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbar-nav">
                            <ul class="navbar-nav">@yield('navbar')</ul>
                        </div>
                    </div>
                </div>
                <div class="container-lg py-3">
                    <div>@yield('banner')</div>
                    <h3>@yield('title')</h3>
                    @if (!empty(session('status')))
                        <div class="py-2">
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <div class="py-2">{{ session('status') }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="py-2">
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                @foreach ($errors->all() as $error)
                                    <div class="py-2">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @yield('main')
                </div>
            </div>
            <div class="text-light bg-dark p-2">
                <div class="text-center">Copyright &copy; 2022, Michael R. Krisnadhi</div>
            </div>
        </div>
    </body>
</html>

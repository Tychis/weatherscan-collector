<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Weather Scan (Canada)</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" type="text/css" />
        <script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body>
        <div id="app">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Current Alerts</div>
                            <ul class="list-group list-group-flush">
                                @foreach($alerts as $alert)
                                <li class="list-group-item">Issued: {{ $alert->issue_datetime }}<br />{{ $alert->alert_type->alert_title }}<br />{{ $alert->alert_location->location_name }}, {{ $alert->alert_location->province }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <show-alerts></show-alerts>
                </div>
        </div>
            <show-alerts></show-alerts>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>

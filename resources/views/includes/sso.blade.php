@php
    ////////////////////////////////////////////////////////////////////////
    // bepa-sso 2021-04-25
    /* Get oauth2 token using a POST request */
    $curlPostToken = curl_init();

    curl_setopt_array($curlPostToken, array(
        CURLOPT_URL => "https://login.windows.net/common/oauth2/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'grant_type' => 'password',
            'scope' => 'openid',
            'resource' => env('POWERBI_API'),
            'client_id' => env('OAUTH_APP_ID'),
            'username' => env('POWERBI_TOKEN_USERID'),
            'password' => env('POWERBI_TOKEN_PASSWORD')
        )
    ));

    $tokenResponse = curl_exec($curlPostToken);
    $tokenError = curl_error($curlPostToken);
    curl_close($curlPostToken);
    //dd($tokenResponse);

    // decode result, and store the access_token in $embeddedToken
    $tokenResult = json_decode($tokenResponse, true);
    //dd($tokenResult);
    $token = $tokenResult["access_token"];
    $embeddedToken = "Bearer "  . ' ' .  $token;
    // $embeddedToken = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im5PbzNaRHJPRFhFSzFqS1doWHNsSFJfS1hFZyIsImtpZCI6Im5PbzNaRHJPRFhFSzFqS1doWHNsSFJfS1hFZyJ9.eyJhdWQiOiJodHRwczovL2FuYWx5c2lzLndpbmRvd3MubmV0L3Bvd2VyYmkvYXBpIiwiaXNzIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvYjU0YzRiOGMtMDFkMC00OTUxLWI0NDgtMjJhNWU0OGVhOGQyLyIsImlhdCI6MTYxOTE3MDMwNSwibmJmIjoxNjE5MTcwMzA1LCJleHAiOjE2MTkxNzQyMDUsImFjY3QiOjAsImFjciI6IjEiLCJhaW8iOiJBVVFBdS84VEFBQUE2Qmx5NkxMUkxXTFR6UUtDYVFZcHBQdFpaRUNncDBvdEdRczBXWGtxU3BEclFLSWRleWlOK2duZWZNM01zVWplaTJ1cXRHa2FhQUE0dEVBOEhtQmlJZz09IiwiYW1yIjpbInB3ZCIsIm1mYSJdLCJhcHBpZCI6Ijc5NjJhMjk0LTg1ZTktNDRkNS1hOGM5LTUxOTlhZWEyNjgxMiIsImFwcGlkYWNyIjoiMSIsImZhbWlseV9uYW1lIjoiTGVlIiwiZ2l2ZW5fbmFtZSI6Ikphc29uIiwiaXBhZGRyIjoiMTM2LjE1OC4yOS4yNDIiLCJuYW1lIjoiSmFzb24gIExlZSIsIm9pZCI6ImIwMzFjYWUyLWI0ZjItNGJlMC1iYTBjLTdhMWE0MWNkNDlkNyIsInB1aWQiOiIxMDAzMjAwMTIzQkJEMDc4IiwicmgiOiIwLkFYRUFqRXRNdGRBQlVVbTBTQ0tsNUk2bzBwU2lZbm5waGRWRXFNbFJtYTZpYUJKeEFGTS4iLCJzY3AiOiJBcHAuUmVhZC5BbGwgQ2FwYWNpdHkuUmVhZC5BbGwgQ2FwYWNpdHkuUmVhZFdyaXRlLkFsbCBDb250ZW50LkNyZWF0ZSBEYXNoYm9hcmQuUmVhZC5BbGwgRGFzaGJvYXJkLlJlYWRXcml0ZS5BbGwgRGF0YWZsb3cuUmVhZC5BbGwgRGF0YWZsb3cuUmVhZFdyaXRlLkFsbCBEYXRhc2V0LlJlYWQuQWxsIERhdGFzZXQuUmVhZFdyaXRlLkFsbCBHYXRld2F5LlJlYWQuQWxsIEdhdGV3YXkuUmVhZFdyaXRlLkFsbCBSZXBvcnQuUmVhZC5BbGwgUmVwb3J0LlJlYWRXcml0ZS5BbGwgU3RvcmFnZUFjY291bnQuUmVhZC5BbGwgU3RvcmFnZUFjY291bnQuUmVhZFdyaXRlLkFsbCBXb3Jrc3BhY2UuUmVhZC5BbGwgV29ya3NwYWNlLlJlYWRXcml0ZS5BbGwiLCJzdWIiOiJ4VVpraUlNcGRyMldqV3lXdW14N3N5cEJveVAwaTNQaWYwcXdXNWFYeFBFIiwidGlkIjoiYjU0YzRiOGMtMDFkMC00OTUxLWI0NDgtMjJhNWU0OGVhOGQyIiwidW5pcXVlX25hbWUiOiJhZG1pbkBLSElORE1BUktFVElORy5vbm1pY3Jvc29mdC5jb20iLCJ1cG4iOiJhZG1pbkBLSElORE1BUktFVElORy5vbm1pY3Jvc29mdC5jb20iLCJ1dGkiOiJfTzliTFlMbEZFMkRmQmxLeFV0cEFBIiwidmVyIjoiMS4wIiwid2lkcyI6WyI2MmU5MDM5NC02OWY1LTQyMzctOTE5MC0wMTIxNzcxNDVlMTAiLCJiNzlmYmY0ZC0zZWY5LTQ2ODktODE0My03NmIxOTRlODU1MDkiXX0.MPmc7uTPq6OJv5V6qaX6k4aM17cNBaIZ6qbUwjr-ID3hFXXX-SYKQb8hkiy8g1aipbIqBgR8RukXgNd3V4bJHqQDh7kqjlbocTu-gGx3BOPzXId_6OXMVevS5IwuOXJw3aOmhz3i1fegPeJ9PyGU7gZCrbnKJ5GNXUM7TX_xHbsMZcMzFNel285L6tnz4r-ZXTOmpaj9Lf8aHnB4Q-SfNkjDearDinm0gnxbEtVJHAzLEaukoRv9ip_Lj9fWDO34nBSzWaeDOnTxWPj-s5ZOc322V2qjZfwYccKeNZ0GVKKJFr7XsoMoT_DUPy5X604_ATdYxX38-fO6VQYcL89IhQ";
    //dd($embeddedToken);

    $curlGetUrl = curl_init();

    curl_setopt_array($curlGetUrl, array(

        CURLOPT_URL => "https://api.powerbi.com/v1.0/myorg/groups/".env('POWERBI_WORKSPACE_GROUP_ID')."/reports",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: $embeddedToken",
            "Cache-Control: no-cache",
            ),
        )
    );

    $embedResponse = curl_exec($curlGetUrl);
    $embedError = curl_error($curlGetUrl);
    //dd($embedResponse);

    curl_close($curlGetUrl);

    if ($embedError) {
        dd("cURL Error #:" . $embedError);
    } else {
        $embedResponse = json_decode($embedResponse, true);
        //dd($embedResponse);
        $embedUrl = $embedResponse['value'][0]['embedUrl'];
        //dd("Embed URL: " . $embedUrl);
    }

    ////////////////////////////////////////////////////////////////////////
@endphp

@section('salesContent')

    <script>
        // Get models. models contains enums that can be used.
        var models = window['powerbi-client'].models;

        // https://github.com/Microsoft/PowerBI-JavaScript/wiki/Embed-Configuration-Details.
        var embedConfiguration= {
            type: 'report',
            id: "<?php echo $reportId ?>", // the report ID
            embedUrl: "<?php echo $embedUrl ?>",
            accessToken: "<?php echo $token; ?>" ,
            settings: {
                panes:{
                    bookmarks: {
                        visible: false
                    },
                    fields: {
                        expanded: false
                    },
                    filters: {
                        expanded: false,
                        visible: false
                    },
                    pageNavigation: {
                        visible: false
                    },
                    selection: {
                        visible: true
                    },
                    syncSlicers: {
                        visible: true
                    },
                    visualizations: {
                        expanded: false
                    }
                }
            }
        };
        var $reportContainer = $('#reportContainer');
        var report = powerbi.embed($reportContainer.get(0), embedConfiguration);
    </script>
    
@endsection
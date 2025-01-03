<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Relógio ponto</title>

    <meta name="robots" content="noindex, nofollow" >
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="https://team.lvengine.com/img/clock_icon.png">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--<script src="/plugins/screensaver/screensaver.js" timeout="30000" message="LVENGINE"></script>-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">


    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden
        }
        body {
            background-color: #272a2a;
        }
        a {
            text-decoration: none;
        }
        .user-tile {
            min-width: 160px;
            min-height: 160px;
            background-color: #1d3349;
            color: #fff;
            position: relative;
            padding-top: 30px;
            margin: 0 20px 20px 0;
        }
        .user-tile:active {
            background-color: #2f6396;
            color: #fff;
        }
        .user-tile.in {
            background-color: green;
        }
        .user-tile.error {
            background-color: #ec0b0b;
        }
        .user-tile strong {
            display: block;
            position: absolute;
            bottom: 15px;
            left: 15px;
        }
        h1 {
            font-size: 20px;
            color: silver;
            padding: 20px 0;
        }
        .btn-success {
            background-color: #28bd1b;
        }
        .btn-lg {
            min-height: 100px;
            font-size: 30px;
            font-weight: bold;
        }
        .acesso .bi {
            font-size: 60px;
            display: block;
            margin-bottom: 10px;
        }
        .alert {
            text-align: center;
            font-size: 30px;
            background-color: transparent;
            color: #fff;
            border: none;
        }
        #id {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #4e5459;
            color: black;
            padding: 2px 15px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
        }

    </style>

    <script>
        function loadContent(url) {
            $.ajax({
                url : url,
                method : 'GET',
                success : function (response) {
                    if (response.redirect_url != undefined) {
                        return loadContent(response.redirect_url);
                    }
                    $("#content").html(response.html);
                }
            });
            return false;
        }
    </script>
</head>
<body>
<div class="container-fluid p-3 align-self-center" id="content">
    {{ content() }}
</div>
<div id="id">{{ id_bar }}</div>
</body>
</html>
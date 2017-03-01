<!DOCTYPE html>
<html ng-app="conversionReport">
    <head>
        <title>Conversion Report</title>

        <base href="/">

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/noUiSlider/9.2.0/nouislider.min.css" rel="stylesheet" type="text/css">
        <link href="/css/spinner.css" rel="stylesheet" type="text/css">
        <link href="/css/style.css" rel="stylesheet" type="text/css">

        <script src="//code.highcharts.com/highcharts.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/noUiSlider/9.2.0/nouislider.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.4.2/angular-ui-router.js"></script>
        <script src="/js/vendor/nouislider.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div ui-view="app"></div>
            </div>
        </div>

        <script src="/js/app.js"></script>
    </body>
</html>

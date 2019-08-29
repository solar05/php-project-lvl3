<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<html>
    <body>
    <nav class="navbar navbar-dark bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navigation</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
        </nav>
    </nav>
    <div class="jumbotron">
        <h1 class="display-4" align="center">Page Analyzer</h1>
        <p class="lead" align="center">Web-application to analyze sites for SEO suitability. Please input site URL that you want to analyze.</p>
        <hr class="my-4">
        <p class="lead">
            <form method="post" action="/domains">
            <input type="text" id="domain" name="domain" class="form-control mb-3" placeholder="Site URL">
            <button class="btn btn-primary btn-lg btn-block" type="submit" role="button">Analyze</button>
            </form>
        </p>
    </div>
    </body>
</html>

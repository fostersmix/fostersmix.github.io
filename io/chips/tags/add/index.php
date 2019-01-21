<html>
  <head>
    <title>Чипс</title>
    <meta charset="utf-8">
    <link rel="icon" sizes="128x128" href="../favicon.png">
    <link rel="stylesheet" href="../styles.css" type="text/css">
    <link rel="stylesheet" href="../flexboxgrid.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400&amp;subset=cyrillic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:600">
  </head>
  <body>
    <div class="row center-xs center-sm center-md center-lg top-lg screen blue">
      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 bottom-20">
        <div class="row center-xs center-sm center-md start-lg middle-lg">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 top-20">
            <a href="/" class="logo-white">чипс</a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 top-20">
            <div class="row center-xs center-sm center-md end-lg">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="../" class="white">Закрыть</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-11 col-sm-11 col-md-10 col-lg-9">
        <form method="get" action="../">
          <div class="row center-xs center-sm center-md center-lg middle-lg -around-lg">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bottom">
              <h1>Соединяй чипсы.</h1>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 top">
              <input type="text" placeholder="это слово" id="field" name="keys[]" pattern="[\S]+$" required="required" />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 top">
              <input type="text" placeholder="с этим словом" id="field" name="keys[]" pattern="[\S]+$" required="required" />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-20 bottom-20">
              <button type="submit" href="#" class="connect">Соединить</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-xs-9 col-sm-9 col-md-10 col-lg-11"></div>
    </div>
  </body>
</html>

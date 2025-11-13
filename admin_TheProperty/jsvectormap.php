<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Jsvectormap - Kaiadmin Bootstrap 5 Admin Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="./assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="./assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["./assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/plugins.min.css" />
    <link rel="stylesheet" href="./assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="./assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <?php include './include/side_nav.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
         <?php include './include/nav.php'; ?>

        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-transparent">
                <div class="card-header">
                  <h4 class="card-title text-center">Vector Maps</h4>
                  <p class="card-category text-center">
                    We use the
                    <a
                      href="https://github.com/themustafaomar/jsvectormap"
                      target="_blank"
                      >Jsvectormap</a
                    >
                    plugin to create vector maps.
                  </p>
                </div>
                <div class="card-body">
                  <div class="col-md-10 ms-auto me-auto">
                    <div class="mapcontainer">
                      <div
                        id="world-map"
                        class="w-100"
                        style="height: 450px"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

       <?php include './include/footer.php'; ?>
      </div>

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <!-- jsvectormap -->
    <script src="./assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="./assets/js/plugin/jsvectormap/world.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="./assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="./assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="./assets/js/setting-demo2.js"></script>
    <script>
      var world_map = new jsVectorMap({
        selector: "#world-map",
        map: "world",
        zoomOnScroll: false,
        regionStyle: {
          hover: {
            fill: "#435ebe",
          },
        },
        markers: [
          {
            name: "Indonesia",
            coords: [-6.229728, 106.6894311],
            style: {
              fill: "#435ebe",
            },
          },
          {
            name: "United States",
            coords: [38.8936708, -77.1546604],
            style: {
              fill: "#28ab55",
            },
          },
          {
            name: "Russia",
            coords: [55.5807481, 36.825129],
            style: {
              fill: "#f3616d",
            },
          },
          {
            name: "China",
            coords: [39.9385466, 116.1172735],
          },
          {
            name: "United Kingdom",
            coords: [51.5285582, -0.2416812],
          },
          {
            name: "India",
            coords: [26.8851417, 75.6504721],
          },
          {
            name: "Australia",
            coords: [-35.2813046, 149.124822],
          },
          {
            name: "Brazil",
            coords: [-22.9140693, -43.5860681],
          },
          {
            name: "Egypt",
            coords: [26.834955, 26.3823725],
          },
        ],
        onRegionTooltipShow(event, tooltip) {
          tooltip.css({ backgroundColor: "#435ebe" });
        },
      });
    </script>
  </body>
</html>

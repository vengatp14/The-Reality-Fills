<?php
include '../admin_TheProperty/include/db_connection.php';

// Handle delete request if it comes via POST (from fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int) $_POST['id'];
    if ($id > 0) {
        $deleteQuery = "DELETE FROM properties WHERE id = $id";
        if ($conn->query($deleteQuery)) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'invalid';
    }
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Datatables - Kaiadmin Bootstrap 5 Admin Dashboard</title>
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
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Client Details</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Tables</a>
                </li>
               
              </ul>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                           <th>Client Name</th>
                           <th>Mobile Number</th>
                           <th>Listing Type</th>
                           <th>Property Type</th>
                           <th>Property Size</th>
                           <th>Document</th>
                           <th>Address</th>
                           <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
<?php
    include '../admin_TheProperty/include/db_connection.php'; 

    // Fetch all properties
    $sql = "SELECT id, client_name, client_mobile, listing_type, property_type, property_size, property_documents, full_address FROM properties ORDER BY client_name";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $prevClient = "";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        
    // Show client name 
    if ($row['client_name'] != $prevClient) {
      echo "<td>{$row['client_name']}</td>";
      echo "<td>{$row['client_mobile']}</td>";
      $prevClient = $row['client_name'];
    } else {
      echo "<td></td><td></td>"; 
    }
    
    echo "<td>{$row['listing_type']}</td>";
    echo "<td>{$row['property_type']}</td>";
    echo "<td>{$row['property_size']}</td>";

    $docs = json_decode($row['property_documents'], true);
    if (!empty($docs)) {
      echo "<td>";
      foreach ($docs as $doc) {
        $doc = trim($doc);
        $filePath = "/TheProperty/admin_TheProperty/include/documents/$doc"; 
        $serverPath = $_SERVER['DOCUMENT_ROOT'] . $filePath;

        if (file_exists($serverPath)) {
            $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                echo "<a href='$filePath' target='_blank' class='btn btn-sm btn-primary mb-1'>View PDF</a><br>";
            } elseif (in_array($ext, ['doc','docx'])) {
              $docViewer = "https://view.officeapps.live.com/op/embed.aspx?src=" . urlencode("http://localhost$filePath");
                echo "<a href='$docViewer' target='_blank' class='btn btn-sm btn-secondary mb-1'>View DOC</a><br>";
            } else {
                //download button
                echo "<a href='$filePath' target='_blank' class='btn btn-sm btn-warning mb-1'>Download</a><br>";
            }
        } else {
            // File does not exist on server
            echo "<span class='text-muted'>File not found</span><br>";
        }
    }
    echo "</td>";
} else {
    echo "<td><span class='text-muted'>No file</span></td>";
}

// Property address column
echo "<td>{$row['full_address']}</td>";

//Action
echo "<td><button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'>Delete</button></td>";
echo "</tr>";
}
}
?>
                        <!--tfoot>
                          <tr>
                         <th>Client Name</th>
    <th>Mobile Number</th>
    <th>Property Title</th>
    <th>Property Type</th>
    <th>Property Size</th>
    <th>Document</th>
    <th>Address</th>
                          </tr>
                        </tfoot>
                        <tbody>
                    
                          <tr>
    <td>John Doe</td>
    <td>9876543210</td>
    <td>Luxury 3BHK Apartment</td>
    <td>Apartment</td>
    <td>1800 sqft</td>
        <th><a href="">Open</a></th>
    <td>123 Main Street, Mumbai</td>
  </tr-->
                        
                        </tbody>
                      </table>
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

    <!-- jQuery Scrollbar -->
    <script src="./assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="./assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="./assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="./assets/js/setting-demo2.js"></script>
    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });

      //delete
      document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
      if (!confirm('Are you sure you want to delete this record?')) return;

      const id = this.getAttribute('data-id');
      const row = this.closest('tr');

      // Send delete request to the same page
      fetch(window.location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=delete&id=' + encodeURIComponent(id)
      })
      .then(res => res.text())
      .then(res => {
        console.log('Delete response:', res); 
        if (res.trim() === 'success') {
          row.style.transition = 'opacity 0.3s';
          row.style.opacity = 0;
          setTimeout(() => row.remove(), 300);
        } else {
          alert('Failed to delete. Server said: ' + res);
        }
      })
      .catch(err => alert('Error deleting record: ' + err));
    });
  });
});
      
    </script>
  </body>
</html>

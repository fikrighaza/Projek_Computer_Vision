<!doctype html>
<html lang="en">

<head>
  <style media="screen">
    .bg-kirei {
      /* background-color: #31348d; */
      background-image: radial-gradient(circle 966px at 6.9% 10.8%, rgba(92, 123, 237, 1) 0%, rgba(147, 186, 252, 1) 90%);
      color: #fff
    }

    .navbar-kirei .navbar-brand {
      color: #fff !important
    }


    .p-card-primary {
      background-image: radial-gradient(circle 966px at 6.9% 10.8%, rgba(92, 123, 237, 1) 0%, rgba(147, 186, 252, 1) 90%);
      color: #fff
    }

    .p-card-danger {
      background-color: #FF9A8B;
      background-image: linear-gradient(90deg, #FF9A8B 0%, #FF6A88 55%, #FF99AC 100%);
    }

    .icon {
      color: #fff
    }

    .nav-kirei {
      color: rgba(92, 123, 237, 1) !important
    }
  </style>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="white" />
  <!-- <link rel="shortcut icon" href="./assets/128px.png" /> -->
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ url_for('static',filename='css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ url_for('static',filename='css/datatables.min.css')}}">
  <link rel="stylesheet" href="{{ url_for('static',filename='css/all.css')}}">
  <!-- <link rel="stylesheet" href="{{ url_for('static',filename='navbar.css')}}"> -->
  <link rel="stylesheet" href="{{ url_for('static',filename='css/Chart.css')}}">
  <link rel="stylesheet" href="{{ url_for('static',filename='fa/css/all.css')}}">
  <!-- Bootstrap CSS 3 for table -->
  <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type = "text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
  <link rel="stylesheet" type = "text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
  

  <title>KIREI</title>
</head>

<body>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-kirei bg-kirei">
      <a class="navbar-brand" href="#">KIREI</a>
    </nav>
    <div class="row mt-4">
      <div class="col-md-8">
        <img id="camera" class="border" alt="img-failed">
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            Camera Control
          </div>
          <div class="card-body">
            <p>Socket : <span id="socket"></span></p>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="stream">
              <label class="form-check-label" for="stream">
                Stream Video
              </label>
            </div>
            <button class="btn btn-primary" onclick="captureImage()">Capture Image</button>

          </div>
        </div>
      </div>
    </div>
    <br>
    <div class = "row mt-8">
      <div class = "col-md-12">
          <div class="card">
            <div class="card-header bg-secondary text-white">
              Table Timestamp Objek
            </div>
            <div class="card-body">
              <table id="tabel-data" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Timestamp</th>
                            <th>Tipe</th>
                            <th>file_foto</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                        include 'koneksi.php'; 
                        $data = mysqli_query($koneksi,"select * from tablecoba"); // harus diatur  di php admin
                        while($row = mysqli_fetch_array($data))
                        {
                            echo "<tr>
                            <td>".$row['id']."</td>
                            <td>".$row['timestamp']."</td>
                            <td>".$row['tipe']."</td>
                            <td>".$row['file_foto']."</td></tr>";
                        }
                    ?>
                    </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                    $('#tabel-data').DataTable();
                  });
              </script>
            </div>
          </div>
      </div>

    </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="{{ url_for('static',filename='js/jquery-3.3.1.min.js')}}" charset="utf-8"></script>
  <script src="{{ url_for('static',filename='js/popper.min.js')}}" charset="utf-8"></script>
  <script src="{{ url_for('static',filename='js/bootstrap.min.js')}}" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script src="{{ url_for('static',filename='js/moment.min.js')}}" charset="utf-8"></script>
  <script src="{{ url_for('static',filename='js/socket.io.js')}}" charset="utf-8"></script>

  <script type="text/javascript">
    const socket = io(`http://localhost:5000/`);

    socket.on("kirei", (x) => {
      console.log(x);
      $("#camera").attr("src", `data:image/jpeg;base64, ${ab2str(x)}`)
    })

    socket.on("japri", (x) => {
      console.log(x);
    })

    $("#stream").change(() => {
      if ($('#stream').is(":checked")) {
        socket.emit(`camera`, "ON")
      }
      else {
        socket.emit(`camera`, "OFF")
      }
    })
    $(document).ready(function () {

    })

    function captureImage() {
      Swal.fire({
        title: "Mengambil gambar",
        allowOutsideClick: false,
        text: "Jangan menutup browser sebelum proses selesai !",
        icon: "warning"
      })
      Swal.showLoading();
      $.ajax({
        url: 'http://localhost:5000/gambar',
        success: (x) => {
          Swal.hideLoading()
          var html = `<img width="75%" src="data:image/jpeg;base64, ${x}"><p>Captured : ${moment().format(`DD MM YYYY - HH:mm`)}</p>`
          Swal.fire({
            title: "Captured image",
            html: html,
          })
        }
      })

    }
    function ab2str(buf) {
      var result = '';
      if (buf) {
        var bytes = new Uint8Array(buf);
        for (var i = 0; i < bytes.byteLength; i++) {
          result = result + String.fromCharCode(bytes[i]);
        }
      }
      return result;
    }


  </script>



</body>

</html>
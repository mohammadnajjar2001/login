<!DOCTYPE html>
<html lang="en">
<head>
  <title>قائمة المشتركين</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .loader {
      position: relative;
      border-style: solid;
      box-sizing: border-box;
      border-width: 20px 30px 15px 30px;
      border-color: #3760C9 #96DDFC #96DDFC #36BBF7;
      animation: envFloating 1s ease-in infinite alternate;
    }

    .loader:after {
      content: "";
      position: absolute;
      right: 62px;
      top: -40px;
      height: 70px;
      width: 50px;
      background-image:
        linear-gradient(#fff 45px, transparent 0),
        linear-gradient(#fff 45px, transparent 0),
        linear-gradient(#fff 45px, transparent 0);
      background-repeat: no-repeat;
      background-size: 30px 4px;
      background-position: 0px 11px, 8px 35px, 0px 60px;
      animation: envDropping 0.75s linear infinite;
    }

    @keyframes envFloating {
      0% { transform: translate(-2px, -5px) }
      100% { transform: translate(0, 5px) }
    }

    @keyframes envDropping {
      0% { background-position: 100px 11px, 115px 35px, 105px 60px; opacity: 1; }
      50% { background-position: 0px 11px, 20px 35px, 5px 60px; }
      60% { background-position: -30px 11px, 0px 35px, -10px 60px; }
      75%, 100% { background-position: -30px 11px, -30px 35px, -30px 60px; opacity: 0; }
    }

    .custom-table {
      border-spacing: 30px; /* إضافة مسافة بين الخلايا */
    }

    .navbar {
      background-color: #000;
      padding: 20px;
    }

    .navbar a {
      color: gray;
      margin-left: 20px;
    }

    .navbar-toggler-icon {
      
      color: gray;
    }

    .container {
      
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-top: 30px;
    }

    h2 {
      margin: 20px;
    }

  </style>
</head>
<body>
    <div class="container">
      <nav class="navbar navbar-expand-sm navbar-dark bg-dark" > 
        <div class="container-fluid">
          <span class="loader"></span>
          <a class="nav-link" href="index.php" style="padding: 0 70px; color: gray">HOME</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="navbar-brand" href="display.php" target="_blank" style="color:white">THE ACCOUTS</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <h2><i class="text-dark" style="text-align: center;position: relative;
      left: 36%;">ALL ACCOUNTS</i></h2>
      <br>
      <div class="input-group mb-3">
        <input type="text" id="searchName" class="form-control" placeholder="NAME" style="text-align: center; border-color: black; border-radius: 20px;" >
        <button class="btn btn-dark" onclick="searchByName()" style="border-radius: 20px;">Search</button>
      </div>
    
      <table class="table table-dark custom-table" >
        <thead >
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $file = "contacts.txt";
          // قراءة محتوى الملف
          $jsonData = file_get_contents($file);
          $data = json_decode($jsonData, true);

          // فحص إذا كان في محتوى
          if ($data != null) {
            foreach ($data as $contact) {
              //وحذف الفراغات الطويلة
              echo '<tr>';
              echo '<td>' . htmlspecialchars($contact['name']) . '</td>';
              echo '<td>' . htmlspecialchars($contact['email']) . '</td>';
              echo '<td>' . htmlspecialchars($contact['phone']) . '</td>';
              echo '</tr>';
            }
          } 
          //ما اشتغل معي
          else {
            echo 'No contacts found.';
          }
          ?>
        </tbody>
      </table>
      </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    //دالة البحث
    function searchByName() {
      // جيب  الاسم الي تم إدخاله
      var nameToSearch = document.getElementById('searchName').value.toLowerCase();

      // جيب الصف
      var rows = document.querySelectorAll('table tbody tr');

      // اخفِ كل الصفوف اول الشي
      for (var i = 0; i < rows.length; i++) {
        rows[i].style.display = 'none';
      }

      //  اظهر الصف الي بيحوي على الاسم المبحوث عنه
      for (var i = 0; i < rows.length; i++) {
        var nameCell = rows[i].getElementsByTagName('td')[0]; // الخلية التي تحتوي على الاسم
        if (nameCell) {
          //استخراج الاسم باحرف صغيرة
          var name = nameCell.textContent.toLowerCase();
          //اذا الاسم المبحوث عنه من مضمون البحث اعرضه
          if (name.includes(nameToSearch)) {
            rows[i].style.display = '';
          }
        }
      }
    }
  </script>
</body>
</html>

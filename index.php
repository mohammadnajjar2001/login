<?php
// تمثيل جهة الاتصال
class Contact
{
    private $name;
    private $email;
    private $phone;

    //   تابع باني 
    public function __construct($name, $email, $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    // دوال  جيتر
    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}

// تعريف الكلاس  لإدارة  
class ContactManager
{
    //[جهات الاتصال]
    private $contacts = [];

    // إضافة جهة اتصال جديدة
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;
    }

    // الحصول على جميع جهات الاتصال
    public function getAllContacts()
    {
        return $this->contacts;
    }
}

// للتعامل مع الملف 
class FileHandler
{
    //تعريف ملف
    private $filename;
     
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    // حفظ جهات الاتصال في الملف
    public function saveContactsToFile($contacts)
    {
        //البيانات
        $data = [];
          //بتمرق على كل جهة اتصال وبتجيبو بالجيت
        foreach ($contacts as $contact) {
            $data[] = [
                'name' => $contact->getName(),
                'email' => $contact->getEmail(),
                'phone' => $contact->getPhone()
            ];
        }
         //تخزين فالملف
        $json = json_encode($data);
         // تنسيق غير صحيح او في مشكلة فالاذونات مثلا
        if ($json === false) {
            throw new Exception('Error encoding contacts to JSON.');
        }

        if (file_put_contents($this->filename, $json) === false) {
            throw new Exception('Error saving contacts to file.');
        }
    }

    // قراءة جهات الاتصال من الملف
    public function readContactsFromFile()
    {
        //اذا الملف غير موجود برجع مصفوفة فاضي
        if (!file_exists($this->filename)) {
            return [];
        }

        $json = file_get_contents($this->filename);
         //اذا قراءة الملف فشلت
        if ($json === false) {
            throw new Exception('Error reading contacts from file.');
        }

        $data = json_decode($json, true);
        //فشل في فهم النص 
        if ($data === null) {
            throw new Exception('Error decoding contacts.');
        }

        return $data;
    }
}

// اسم ملف البيانات
$filename = 'contacts.txt';

// إنشاء  كائنين    
$contactManager = new ContactManager();
$fileHandler = new FileHandler($filename);

//بيقرأ جهة الاتصال من الملف وبضيفها فالكائن الجديد 
try {
    $data = $fileHandler->readContactsFromFile();
    foreach ($data as $contactData) {
        // استخدم البيانات لإنشاء كائن Contact وقم بإضافته إلى ContactManager
        $contact = new Contact($contactData['name'], $contactData['email'], $contactData['phone']);
        $contactManager->addContact($contact);
    }
} catch (Exception $e) {
    echo 'An error occurred while reading contacts: ' . $e->getMessage();
}

// مستخدمين بوست هون 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // فحص البيانات المدخلة ان كانت فارغة واستخدمت رسالة توضيحية باللون الاحمر
    if (empty($name) || empty($email) || empty($phone)) {
        echo '<div class="alert alert-danger" role="alert" style="text-align: center; padding: 20px;">
        <strong>pleas enter your information!</strong><br>
        <img src="false.png" alt="Failed Image" style="display: block; margin: 0 auto;">
      </div>';
      
    } 
     //الفلترة لازم يحوي على @ ويكون مقبول
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address.';
    } else {
        $existingContacts = $contactManager->getAllContacts();

        // للتحقق من عدم وجود بريد إلكتروني مكرر
        foreach ($existingContacts as $existingContact) {
            if ($existingContact->getEmail() === $email) {
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
                <div class="alert alert-danger" role="alert" style="text-align: center; padding: 20px;">
                <strong>Email Already exists!</strong><br>
                <img src="alredy.png" alt="Failed Image" style="display: block; margin: 0 auto;">
                <a href="index.php" type="button" class="btn btn-danger" >Home</a>
              </div>';
                return;
            }
        }

        //  إضافة جهة اتصال جديدة اذا مافي بريد مكرر  
        $contact = new Contact($name, $email, $phone);
        $contactManager->addContact($contact);

        try {
            // حفظ البيانات  في الملف واضافة رسالة توضيحية باللون الاخضر يعني تمام
            $fileHandler->saveContactsToFile($contactManager->getAllContacts());
            
            echo '<div id="alert-message" class="alert alert-success" style="text-align: center; padding: 20px;">
            <strong style="padding: 20px">Success!</strong><br>
            <img src="true.png" alt="Success Image" style="display: block; margin: 0 auto;">
                 </div>';
    
 } catch (Exception $e) {
            echo 'An error occurred while saving the contact: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .loader {
  position: relative;
  border-style: solid;
  box-sizing: border-box;
  border-width: 20px 30px 15px 30px;
  border-color: #3760C9 #96DDFC #96DDFC #36BBF7;
  animation: envFloating 1s ease-in infinite alternate;
}

.loader2 {
  width: fit-content;
  font-size: 17px;
  font-family: monospace;
  line-height: 1.4;
  font-weight: bold;
  --c: no-repeat linear-gradient(#000 0 0); 
  background: var(--c),var(--c),var(--c),var(--c),var(--c),var(--c),var(--c);
  background-size: calc(1ch + 1px) 100%;
  border-bottom: 10px solid #0000; 
  position: relative;
  animation: l8-0 3s infinite linear;
  clip-path: inset(-20px 0);
}
.loader2::before {
  content:"Teacher";
}
.loader2::after {
  content: "";
  position: absolute;
  width: 10px;
  height: 14px;
  background: #25adda;
  left: -10px;
  bottom: 100%;
  animation: l8-1 3s infinite linear;
}
@keyframes l8-0{
   0%,
   12.5% {background-position: calc(0*100%/6) 0   ,calc(1*100%/6)    0,calc(2*100%/6)    0,calc(3*100%/6)    0,calc(4*100%/6)    0,calc(5*100%/6)    0,calc(6*100%/6) 0}
   25%   {background-position: calc(0*100%/6) 40px,calc(1*100%/6)    0,calc(2*100%/6)    0,calc(3*100%/6)    0,calc(4*100%/6)    0,calc(5*100%/6)    0,calc(6*100%/6) 0}
   37.5% {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6)    0,calc(3*100%/6)    0,calc(4*100%/6)    0,calc(5*100%/6)    0,calc(6*100%/6) 0}
   50%   {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6) 40px,calc(3*100%/6)    0,calc(4*100%/6)    0,calc(5*100%/6)    0,calc(6*100%/6) 0}
   62.5% {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6) 40px,calc(3*100%/6) 40px,calc(4*100%/6)    0,calc(5*100%/6)    0,calc(6*100%/6) 0}
   75%   {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6) 40px,calc(3*100%/6) 40px,calc(4*100%/6) 40px,calc(5*100%/6)    0,calc(6*100%/6) 0}
   87.4% {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6) 40px,calc(3*100%/6) 40px,calc(4*100%/6) 40px,calc(5*100%/6) 40px,calc(6*100%/6) 0}
   100%  {background-position: calc(0*100%/6) 40px,calc(1*100%/6) 40px,calc(2*100%/6) 40px,calc(3*100%/6) 40px,calc(4*100%/6) 40px,calc(5*100%/6) 40px,calc(6*100%/6) 40px}
}
@keyframes l8-1{
  100% {left:115%}
}

.loader:after{
  content:"";
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
  background-position: 0px 11px , 8px 35px, 0px 60px;
  animation: envDropping 0.75s linear infinite;
}

@keyframes envFloating {
  0% { transform: translate(-2px, -5px)}
  100% { transform: translate(0, 5px)}
}

@keyframes envDropping {
  0% {background-position: 100px 11px , 115px 35px, 105px 60px; opacity: 1;}
  50% {background-position: 0px 11px , 20px 35px, 5px 60px; }
  60% {background-position: -30px 11px , 0px 35px, -10px 60px; }
  75%, 100% {background-position: -30px 11px , -30px 35px, -30px 60px; opacity: 0;}
}

 
      
        body {
            background-color: #f0f0f0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("button").click(function(){
                $("#name, #email, #phone, #image").fadeIn();
            });
        });
    </script>
</head>
<body>


    <div class="container">
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark" >
            <div class="container-fluid">
            <span class="loader"></span>
                <a class="navbar-brand" href="index.php" style="padding:0 70px;">HOME</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="display.php" target="_blank">THE ACCOUTS</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h2>Welcome <span class="loader2"></span></h2> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="name" class="form-label" required>Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label" required>Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
           
        </form>
        <br>
        <h1 style="text-align: center;">ENG: Mohammad Nassan Najjar</h1>
    </div>
</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<?php
require_once("../utils/dbconnect.php"); 
require_once("../utils/functions.php"); 
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/login/login.css">
</head>

<body>
    <div class="background center">
        <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
        <div class="content width-2">
            <h1>Verified account</h1>
            <p>This is where professional programmers can submit their information to possibly get a professional account. This account will be free of charge and you will have a checkmark next to your name.</p>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                <div class="flex">
                    <div class="half-form">
                        <!-- Voledige naam -->
                        <input class="formfield" type="text" name="fullname" id="fullname" placeholder="Full name...">
                        <p class="errortext" id="nameerror">Please enter a valid name.</p>

                        <!-- Email veld -->
                        <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                        <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>
                        <p class="errortext" id="comboerror">This combination of username and email is not in our system. Consider creating a account first.</p>

                        <!-- Wachtwoord veld -->
                        <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                        <p class="errortext" id="passworderror">Please enter your password.</p>
                        <p class="errortext" id="passwordincorrect">This password is incorrect, please try again.</p>

                        <!-- Submit -->
                        <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                        <!-- Remember me + help -->
                        <div class="content1">
                            <a href="./loginhelp.php">Need help?</a>
                        </div>
                    </div>
                    <div class="half-form">
                        <!-- File upload -->
                        <img id="uploadedFoto" class="uploadedFoto" src="../assets/img/image_placeholder.png" alt="Your uploaded picture">
                        <input type="file" name="uploadedFile" accept="image/*" onchange="loadFile(event)">

                        <!-- Zet de afbeelding uit de input file in de img -->
                        <script>
                            var loadFile = function(event) {
                                var output = document.getElementById('uploadedFoto');
                                output.src = URL.createObjectURL(event.target.files[0]);
                                output.onload = function() {
                                    console.log("test");
                                    URL.revokeObjectURL(output.src);
                                }
                            };
                        </script>
                        <p class="errortext" id="uploaderror">Please upload a picture of your id.</p>
                        <p class="errortext" id="uploadsizeerror">Your image is too large, it must be less then 60 KB.</p>
                        <p class="errortext" id="uploadexterror">Your image must be of type: png, jpg or jpeg.</p>
                        <p class="errortext" id="uploaduploaderror">A file was already uploaded with your credentials.</p>
                    </div>
                </div>
            </form>
            <!-- Sign up link -->
            <div class="content2">
                <p>New to Null Pointer Videos? <a href="./choosemembership.php">Sign up now</a>.</p>
                <p>Already have an account? <a href="./login.php">Sign in now</a>.</p>
            </div>
        </div>
    </div>
    <?php
    // Form input valideren
    if (isset($_POST['login'])) {
        if (!empty($_POST['fullname'])) {
            if (!empty($_POST['password'])) {
                $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS);
                if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                    if (!empty($_FILES["uploadedFile"]["tmp_name"])) {
                        // Kijk of het account bestaat door naar de gegevens te zoeken in de database.
                        $sql = "SELECT account.`password` FROM account WHERE account.`name` = ? AND email = ?";
                        $result = stmtExecute($sql, 1, 'ss', $fullname, $email);

                        // Kijk of er resultaten zijn.
                        if ($result['account.`password`'] > 0) {
                            if (password_verify($_POST['password'], $result['account.`password`'][0])) {
                                // Grootte kleiner dan 60 kb
                                if ($_FILES["uploadedFile"]["size"] < 60000) {
                                    $acceptedFileTypes = ["image/png", "image/jpg", "image/jpeg"];
                                    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
                                    $uploadedFileType = finfo_file($fileinfo, $_FILES["uploadedFile"]["tmp_name"]);

                                    // De plek waar de uploads heen gaan
                                    $saveDir = "../assets/upload/verify-request/";

                                    // Check of de bestandsextentie een van de boven genoemde extenties is
                                    if (in_array($uploadedFileType, $acceptedFileTypes)) {
                                        // Bestaat het bestand al?
                                        if (!file_exists($saveDir . $_FILES["uploadedFile"]["name"])) {
                                            // Haal een naam en email uit de form en geef het bestand die naam
                                            $fullname = str_replace(" ", "-", filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS));
                                            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

                                            $newFileName = "REQUEST_" . $fullname . "_" . $email;

                                            // Haal de extentie van het bestand op
                                            $ext = explode(".", $_FILES["uploadedFile"]["name"])[1];

                                            // Upload het bestand
                                            if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $saveDir . $newFileName . "." . $ext)) {
                                                header("Location: ./verifyredirect.php");
                                                exit();
                                            } else {
                                                echo "An error occured, try again later";
                                            }
                                        } else {
                                            echo
                                            "<script>
                                                document.getElementById('uploaduploaderror').style.display = 'block';
                                            </script>";
                                        }
                                    } else {
                                        echo
                                        "<script>
                                            document.getElementById('uploadexterror').style.display = 'block';
                                        </script>";
                                    }
                                } else {
                                    echo
                                    "<script>
                                        document.getElementById('uploadsizeerror').style.display = 'block';
                                    </script>";
                                }
                            } else {
                                echo
                                "<script>
                                    document.getElementById('passwordincorrect').style.display = 'block';
                                </script>";
                            }
                        } else {
                            // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                            echo
                            "<script>
                                document.getElementById('comboerror').style.display = 'block';
                                document.getElementById('email').value = '" . $_POST['email'] . "';
                                document.getElementById('fullname').value = '" . $_POST['fullname'] . "';    
                            </script>";
                        }
                        Mysqli_stmt_close($stmt);
                    } else {
                        // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                        echo
                        "<script>
                            document.getElementById('uploaderror').style.display = 'block';
                            document.getElementById('email').value = '" . $_POST['email'] . "';
                            document.getElementById('fullname').value = '" . $_POST['fullname'] . "';    
                        </script>";
                    }
                } else {
                    // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    echo
                    "<script>
                        document.getElementById('emailerror').style.display = 'block';
                        document.getElementById('email').value = '" . $_POST['email'] . "';
                        document.getElementById('fullname').value = '" . $_POST['fullname'] . "';                        
                    </script>";
                }
            } else {
                // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                echo
                "<script>
                    document.getElementById('passworderror').style.display = 'block';
                </script>";
            }
        } else {
            // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
            echo
            "<script>
                document.getElementById('nameerror').style.display = 'block';
                document.getElementById('email').value = '" . $_POST['email'] . "';
            </script>";
        }
    }
    ?>

</body>

</html>
<?php
include_once("../utils/dbclose.php");
ob_end_flush();
?>
<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/profile/profile.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
</head>
<body>
<?php include_once('../assets/components/header.php') ?>
<?php include("../utils/dbconnect.php") ?>
    <div class="container">
        <div class="contentBlock">
            <div class="profileBlock">
                <h1>Profile</h1>
                <form action="">
                    <?php 
                        $records = mysqli_query($conn,"SELECT * FROM account"); 

                        while($data = mysqli_fetch_array($records)){

                    ?>

                    <label for="name">Name</label>
                    <input type="text" name="name" value="<?php echo $data["Name"];?>">

                    <label for="name">Username</label>
                    <input type="text" name="username" value="<?php echo $data["Username"];?>">

                    <label for="name">Email</label>
                    <input type="email" name="email" value="<?php echo $data["Email"];?>">

                    <label for="name">Password</label>
                    <input type="password" name="password" value="<?php echo $data["password"];?>">
                    <?php 
                        } 
                        include("../utils/dbclose.php")
                    ?>
                </form>
                <div class="buttons">
                    <input type="submit" name="submit" value="Save profile">
                    <a href="deleteprofile.php"><button>Delete profile</button></a>
                </div>
            </div>
            <div class="membershipBlock">
                <h1>Select membership</h1>
                <table>
                    <tr>
                        <td></td>
                        <td><h2>Junior</h2></td>
                    </tr>
                    <tr>
                        <td>Monthly price</td>
                        <td>&#8364;9,99</td>
                    </tr>
                    <tr>
                        <td>HD available</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>4K available</td>
                        <td><i class="fas fa-times"></i></td>
                    </tr>
                    <tr>
                        <td>Watch on your laptop or TV</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Unlimited videos</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Cancel anytime</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                </table>
                <button>Upgrade membership</button>
            </div>
        </div>
    </div>
    
</body>

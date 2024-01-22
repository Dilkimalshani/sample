<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<ul>
<li><a href="index.php">HOME</a></li>
  <li><a href="login.php">LOGIN</a></li>
  <li><a href="signup.php">SIGNUP</a></li>
</ul>
<br><br>
<h1>CREATE AN ACCOUNT</h1>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $Name = $_POST["name"];
            $Email = $_POST["email"];
            $Password = $_POST["password"];
            $RepeatPassword = $_POST["passwordrepeat"];
            $passwordHash = password_hash($Password, PASSWORD_DEFAULT);

            $errors = array();
            if (empty($Name) || empty($Email) || empty($Password) || empty($RepeatPassword)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($Password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($Password !== $RepeatPassword) {
                array_push($errors, "Password does not match");
            }

            require_once "database.php";

            // Check if email already exists
            $sql = "SELECT * FROM users WHERE email = '$Email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exists");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users(name,email,password) VALUES (?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $Name, $Email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>

        <form action="signup.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="passwordrepeat" placeholder="Repeat Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
            <br><br>
            <p>Already have an account? <a href="login.php">Login.</a></p>
        </form>
    </div>
</body>
</html>

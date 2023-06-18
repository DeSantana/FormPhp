<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($_POST["submit"])) {
       $fullname = $_POST["fullname"];
       $email = $_POST["email"];
       $password = $_POST["password"];
       $passwordRepeat = $_POST["repeat_password"];

       $passwordHash = password_hash($password, PASSWORD_DEFAULT);

       $errors = array();
       if (empty($fullname) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
          array_push($errors, "Todos os campos são necessários");
       }

       if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "E-mail não é válido");
       }
       if (strlen($password)<8) {
        array_push($errors, "A senha deve ter pelo menos 8 caracteres");
       }
       if ($password !== $passwordRepeat) {
        array_push($errors, "Senha não corresponde");
       }


       require_once "database.php";
       $sql = "SELECT * FROM users WHERE email = '$email' ";
       $result = mysqli_query($conn, $sql);
       $rowCount = mysqli_num_rows($result);
       if ($rowCount>0 ) {
        array_push($errors,"E-mail já existe!");
       }

       if (count($errors)>0) {
        foreach($errors as $error) {
         echo "<div class='alert alert-danger'>$error</div>";
        }
       }else {
         
         $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
         $stmt = mysqli_stmt_init($conn);
         $prepareStmt = mysqli_stmt_prepare($stmt,$sql);


         if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"sss", $fullname, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>Você está registrado com sucesso.</div>";
         }else{
            die("Algo deu errado");
         }
       }
    }
    ?>
    <div class="container">
        <form action="registro.php" method = "post">
            <div class="form-group">
                <input type="text" class="form-control" name = "fullname" placeholder = "Nome Completo:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name = "email" placeholder = "Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name = "password" placeholder = "Senha:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name = "repeat_password" placeholder = "Repita a Senha:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value = "Registre-se" name = "submit">
            </div>
        </form>
        <div><p>Já registrado?<a href="login.php">Faça o login Aqui</a></p></div>
    </div>
</body>
</html>
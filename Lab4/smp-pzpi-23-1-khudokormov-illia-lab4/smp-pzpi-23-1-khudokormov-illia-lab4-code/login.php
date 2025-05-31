<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shop - Login</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .login-form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            flex-direction: column;
        }
        .login-input {
            font-size: 24px;
            height: 50px;
            width: 400px;
            margin: 10px;
            padding: 5px;
        }
        .login-input::placeholder {
            font-size: 20px !important;
            font-family: Arial, sans-serif;
            color: black;
        }
        .login-submit {
            font-size: 24px;
            margin-left: 150px;
            margin-top: 10px;
            width: 250px;
            padding: 5px;
        }
        .error-message {
            font-size: 30px;
            margin: 50px;
            color: red;
        }
        .hidden {
            visibility: collapse;
        }
        .visible {
            visibility: visible;
        }
    </style>
</head>
<body>
    <form method="POST" action="index.php?signin" class="login-form">
        <input type="text" name="customerName" placeholder="User Name" class="login-input">
        <input type="text" name="password" placeholder="Password" class="login-input">
        <input type="submit" value="Login / Register" class="login-submit">
        <p class="error-message <?php echo !isset($dataSuccess) || $dataSuccess ? 'hidden' : 'visible'?>">
            Логін і пароль повинні складатися з двох або більше символів
        </p>
        <p class="error-message <?php echo !isset($passwordSuccess) || $passwordSuccess ? 'hidden' : 'visible'?>">
            Пароль невірний
        </p>
    </form>
</body>
</html>
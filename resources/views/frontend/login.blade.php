<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            background-color: transparent;
            background-image: url("https://cdn.pixabay.com/photo/2016/06/02/02/33/triangles-1430105_960_720.png");
            background-size: cover;
        }

        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
            background-color: transparent;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .avatar {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        .avatar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .form {
            position: relative;
            z-index: 1;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
        }

        .form input[type="email"],
        .form input[type="password"] {
            font-family: 'Roboto', sans-serif;
            outline: none;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .form button {
            font-family: 'Roboto', sans-serif;
            text-transform: uppercase;
            outline: none;
            background-color: #2196F3;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 20px;
        }

        .form button:hover,
        .form button:active,
        .form button:focus {
            background-color: #1976D2;
        }

        .form h2 {
            color: #333333;
            font-size: 28px;
            margin: 0 0 30px;
        }

        .form p.message {
            color: #666666;
            font-size: 14px;
            margin-top: 15px;
        }

        .form p.message a {
            color: #2196F3;
            text-decoration: none;
        }

        .form p.message a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="login-page">
        <div class="avatar">
            <img src="https://cdn.pixabay.com/photo/2014/04/02/14/10/female-306407_960_720.png" alt="Avatar">
        </div>
        <div class="form">
            <h2>Sign In</h2>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="UserEmail">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Sign In</button>
                <p class="message" style="color: black">Not registered? <a href="{{ route('auth.register') }}"
                        style="color: red;text-decoration: none;font-size: 15px;">Create an account</a></p>
            </form>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

</html>

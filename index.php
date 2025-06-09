<?php
    require 'vendor/autoload.php';
?>
<html>
    <head>
        <title>Form</title>
        <link rel="stylesheet" href="./assets/style.css">
    </head>
    <body>
        <form action="/" method="POST" id="amo_form">
            <input type="text" name="name" placeholder="Имя">
            <input type="email" name="email" placeholder="Электронная почта">
            <input type="text" name="phone" placeholder="Номер телефона">
            <input type="number" name="price" placeholder="Цена">
            <button>Отправить</button>
            <div id="response"></div>
        </form>
        <script src="./assets/script.js"></script>
    </body>
</html>
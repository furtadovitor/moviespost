<?php

    $db_name = "moviespost";
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "barata123";

    $conn = new PDO("mysql:dbname=". $db_name .";host=". $db_host, $db_user, $db_pass);

    //Habilitando os erros PDO

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
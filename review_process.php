<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Movie.php");
    require_once("models/Review.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");
    require_once("dao/ReviewDAO.php");


    $message = new Message($BASE_URL);
    $userDao = new USerDAO($conn, $BASE_URL);
    $movieDao = new MovieDAO($conn, $BASE_URL);
    $reviewDao = new ReviewDAO($conn, $BASE_URL);


    //Resgata dados do usuário
    $userData = $userDao->verifyToken();

    //recebendo o tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    if($type === "create"){

        //REcebendo dados do POST

    
        $rating = filter_input(INPUT_POST, "rating");
        $review = filter_input(INPUT_POST, "review");
        $movies_id = filter_input(INPUT_POST, "movies_id");
        $users_id = $userData->id;

        $reviewObject = new Review();
       
        $movieData = $movieDao->finById($movies_id);

       
         //Verificando se o filme existe 
         if($movieData){

            //Vericicando dados minimos
            if(!empty($rating) && !empty($review) && !empty($movies_id)){

                $reviewObject->rating = $rating;
                $reviewObject->review = $review;
                $reviewObject->users_id = $users_id;
                $reviewObject->movies_id = $movies_id;
               

                $reviewDao->create($reviewObject);


            }else{
                $message->setMessage("Você precisa inserir a nota e o comentário", "error", "back");


            }


         }else{

            $message->setMessage("Informações inválidas", "error", "index.php");


         }
    


    }else{

        $message->setMessage("Informações inválidas", "error", "index.php");

    }


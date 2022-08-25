<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Movie.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new USerDAO($conn, $BASE_URL);
    $movieDao = new MovieDAO($conn, $BASE_URL);


    //Resgata o tipo 
    $type = filter_input(INPUT_POST, "type");

    //Resgata dados do usuário
    $userData = $userDao->verifyToken();


    if($type === "create"){

        //recebe os dados do input
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $movie = new Movie();

        //Validação mínima de dados 
        if(!empty($title) && !empty($description) && !empty($category)){

            $movie->title = $title;
            $movie->description = $description;
            $movie->trailer = $trailer;
            $movie->category = $category;
            $movie->length = $length;
            $movie->users_id = $userData->id;

            //Upload de imagem do filme

            if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){

                $image = $_FILES["image"];
                $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                $jpgArray = ["image/jpeg", "image/jpg"];

                //Checando tipos da imagem

                if(in_array($image["type"], $imageTypes)){

                    //Checa se imagem é jpg
                    if(in_array($image["type"], $jpgArray)){
                        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                    }else{
                        $imageFile = imagecreatefrompng($image["tmp_name"]);

                    }

                    //GErando o nome da imagem
                    $imageName = $movie->imageGenerateName();

                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                    $movie->image = $imageName;

                }else{

                    $message->setMessage("Informações inválidas", "error", "index.php");

                }

            }

            $movieDao->create($movie);

            
        }else{

            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
        }



    
    
    }else if($type === "delete"){
        
        //Recebendo os dados do formulário
        $id = filter_input(INPUT_POST, "id");

        $movie = $movieDao->finById($id);

        if($movie){

            //Verificando se o filme é do usuário
            if($movie->users_id === $userData->id){

                $movieDao->destroy($movie->id);

            }else{
                $message->setMessage("Informações inválidas", "error", "index.php");
            }

        }else{
            $message->setMessage("Informações inválidas", "error", "index.php");

        }

    }else if($type === "update"){

        //recebe os dados do input
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
        $id = filter_input(INPUT_POST, "id");

        $movieData = $movieDao->finById($id);

        //Verifica se encontrou algum filme

        if($movieData){

            //Verifica se o id bate com o suuário
            if($movieData->users_id === $userData->id){
                
                //Validação mínima de dados 
                 if(!empty($title) && !empty($description) && !empty($category)){
            
                //Edição do filme
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;

                //Upload da imagem do filme
                if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){

                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];
    
                    //Checando tipos da imagem
    
                    if(in_array($image["type"], $imageTypes)){
    
                        //Checa se imagem é jpg
                        if(in_array($image["type"], $jpgArray)){
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        }else{
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
    
                        }
    
                        //GErando o nome da imagem
                        $movie = new Movie();
                        $imageName = $movie->imageGenerateName();
    
                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
    
                        $movieData->image = $imageName;
    
                    }else{
    
                        $message->setMessage("Informações inválidas", "error", "index.php");
    
                    }
    
                }

                $movieDao->update($movieData);
    

                 }else{
                    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

                 }
                



            }else{
                $message->setMessage("Informações inválidas", "error", "index.php");

            }
            
        }else{

            $message->setMessage("Informações inválidas", "error", "index.php");

        }

    }else {
        $message->setMessage("Informações inválidas", "error", "index.php");

    }
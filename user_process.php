<?php 

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);

    //Resgata o tipo de perfil

    $type = filter_input(INPUT_POST, "type");

    //Atualizar usuário 
    if($type === "update"){

        // Resgatando dados do usuário
        $userData = $userDao->verifyToken();

        //recebendo dados do post 
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");

        //Criando um novo objeto de usuário
        $user = new User();

        //Preenchendo os dados do usuário
        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

        //Upload da imagem
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
            
            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            //Checagem de tipo de imagens
            if(in_array($image["type"], $imageTypes)){
                
                //Checando se é JPG
                if(in_array($image, $jpgArray)){

                    //função que cria a imagem no sistema
                    $imageFile= imagecreatefromjpeg($image["tmp_name"]);

                //IMagem é png
                }else{
                    $imageFile= imagecreatefrompng($image["tmp_name"]);

                }

                //Gera um nome para a imagem
                $imageName = $user->imageGenerateName();


                //Criando imagem jpeg (qlqr coisa que venha) 
                //variavel / arquivo / qualidade 
                imagejpeg($imageFile, "/img/users/" . $imageName, 100);

                //Assim que sabemos como vai ser salvo o nome da image.
                $userData->image = $imageName;

            }else{
                $message->setMessage("Tipo inválido de imagem, insita PNG ou JPG.", "error", "back");


            }
        }

        $userDao->update($userData);

        


    //Atualizar senha do usuário
    }else if($type === "changepassword"){

        //REceber dados dopost
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
        $id = filter_input(INPUT_POST, "id");

        if($password == $confirmpassword){

            $user = new User();

            $finalPassword = $user->generatePassword($password);

            $user->password = $finalPassword;
            $user->id = $id;

            $userDao->changePassword($user);
        }else{
            $message->setMessage("As senhas não são iguais!", "error", "back");
        }

    }else{
        //usuário malicioso 
        $message->setMessage("Informações inválidas", "error", "index.php");

    }

<?php

    class User{

        public $id;
        public $name;
        public $lastname;
        public $email;
        public $password;
        public $image;
        public $token;
        public $bio;

        public function getFullName($user){
            return $user->name . " " . $user->lastname;
        }

        public function generateToken(){
            //função que cria uma string e dps modifica um pouco para deixar mais difícil de duplicar
            return bin2hex(random_bytes(50)); 
        }

        public function generatePassword($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }

        public function imageGenerateName(){
            return bin2hex(random_bytes(60)) . ".jpg"; 

        }

    }

    interface UserDAOInterface{
        
        
        public function buildUser($data);
        
        //criar usuario e fazer login
        public function create(User $user, $authUser = false);
        //atualizar usuário
        public function update(User $user, $redirect = true);
        //receber um token  
        public function verifyToken($protected = false);
        public function setTokenToSession($token, $redirect = true);
        //autenticao completa
        public function authenticateUser($email, $password);
        //encontrar user por email
        public function findByEmail($email);
        //encontrar user por id
        public function finById($id);
        //encontrar por token
        public function findByToken($token);
        //método para fazerr logout
        public function destroyToken();
        //utilizar só um metodo p troca da senha
        public function changePassword(User $user);


    }

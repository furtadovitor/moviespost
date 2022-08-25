<?php

    class Movie{

        public $id;
        public $title;
        public $description;
        public $image;
        public $trailer;
        public $category;
        public $length;
        public $users_id;

        public function imageGenerateName(){
            return bin2hex(random_bytes(60)) . ".jpg";
        }


    }

    interface MoviesDAOInterface {

        //recebe os dados e faz um objeto de filme
        public function buildMovie($data);

        //todos os filmes do banco de dados
        public function findAll();

        //pega todos os filmes em ordem decrescente 
        public function getLatestMovies();

        //Pego os filmes por determinada categoria
        public function getMoviesByCategory($category);

        //Pego os filmes do usuário específico 
        public function getMoviesByUserId($id);

        //Pego o filme pelo ID dele
        public function finById($id);

        //PEgo o filme por um título específico
        public function finByTitle($title);

        //criar o filme
        public function create(Movie $movie);

        //alterar o filme
        public function update(Movie $movie);

        //deletar o filme
        public function destroy($id);


    }
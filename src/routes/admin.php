<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

   // get books
   $app->get('/books/',function(Request $request , Response $response,array $args){
    $query = "SELECT*FROM book";
    $stmt =$this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson([
    "status" => "success",
    "data"=> $result
    ],200);
 });
   $app->post('/books/insert/', function(Request $request,Response $response,array $args){
    $new_book = $request->getParsedBody();
    $query = "INSERT INTO book (book_name,writer,publisher,sinopsis,dt_published,cover,stock,rating,status) VALUE (:book_name,:writer,:publisher,:sinopsis,:dt_published,:cover,:stock,:rating,:status)";
$stmt=$this->db->prepare($query);
$stmt->execute();
    return $response->withJson([
        'status' => 'success',
        'message' => 'masuk'
    ]);
   });


};

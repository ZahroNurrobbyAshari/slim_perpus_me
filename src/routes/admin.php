<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;

return function (App $app) {

    // container

    $container = $app->getContainer();

    // get books

    $app->get('/books/', function (Request $request, Response $response, array $args) {
        $query = "SELECT*FROM book";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson([
            "status" => "success",
            "data" => $result
        ], 200);
    });

    //  insert book
    $app->post('/book/insert/', function (Request $request, Response $response, array $args) use ($container) {

        // get cover
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['cover'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
            $filename = sprintf('%s.%0.8s', $basename, $extension);

            $directory = $this->get('settings')['cover_directory'];
            log($directory);
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        }


        $new_book = $request->getParsedBody();

        $query = "INSERT INTO book (book_name,writer,publisher,sinopsis,dt_published,cover,stock,rating,status) VALUE (:title,:author,:publisher,:sinopsis,:date_published,:cover,:stock,:status)";
        $data = [
            ':book_name'        => $new_book['title'],
            ':author'           => $new_book['author'],
            ':publisher'        => $new_book['publisher'],
            ':sinopsis'         => $new_book['sinopsis'],
            ':date_published'   => $new_book['date_published'],
            ':cover'            => $filename,
            ':stock'            => $new_book['stock'],
            ':status'           => $new_book['status'],
        ];

        $stmt = $this->db->prepare($query);
        if ($stmt->execute($data)) {
            $url = __DIR__ . "public/cover" . $filename;
            return $response->withJson(["status" => "success", "data" => $url], 200);
        }
        return $response->withJson([
            'status' => 'failed',
            'message' => 'gagal ',
            'data' => '0',
        ], 200);
    });
};

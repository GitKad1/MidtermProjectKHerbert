<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $author->author = $data->author;

    $result = $author->create();

    // Create post
    if($result) {
        echo json_encode(
            array('message' => 'Created Author(' . $author->id . ', ' . $author->author . ')')
        );
    } else {
        echo json_encode(
            array('message' => 'Post Not Created',
            'message' => 'Missing Required Parameters')
        );
    }
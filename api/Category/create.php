<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $category->category = $data->category;

    $result = $category->create();

    // Create post
    if($result) {
        echo json_encode(
            array('message' => 'Created Category(' . $category->id . ', ' . $category->category . ')')
        );
    } else {
        echo json_encode(
            array('message' => 'Post Not Created',
            'message' => 'Missing Required Parameters')
        );
    }
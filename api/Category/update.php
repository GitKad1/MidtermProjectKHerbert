<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    // Set ID to update
    $category->id = $data->id;
    $category->category = $data->category;

    $returnValue = $category->update();

    $successMessage = 'Updated Category (' . $category->id . ', ' . $category->category . ')';

    // Update category
    if($returnValue == 1) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } elseif($returnValue == 2) {
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    } elseif($returnValue == 3) {
        echo json_encode(
            array('message' => $successMessage)
        );
    } else {
        echo json_encode(
            array('message' => 'Category Not Updated')
        );
    }
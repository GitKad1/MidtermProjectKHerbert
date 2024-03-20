<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));


    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $returnValue = $quote->create();

    $successMessage = 'Created Author(' . $quote->id . ', ' . $quote->quote . ', ' . $quote->author_id . ', ' . $quote->category_id . ')';

    // Create quote
    if($returnValue == 1) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } elseif($returnValue == 2) {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    } elseif($returnValue == 3) {
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    } elseif($returnValue == 4) {
        echo json_encode(
            array('message' => $successMessage)
        );
    } else {
        echo json_encode(
            array('message' => 'Quote not created')
        );
    }
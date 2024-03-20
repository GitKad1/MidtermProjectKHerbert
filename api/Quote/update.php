<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    // Set ID to update
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $returnValue = $quote->update();

    $successMessage = 'Updated Quote (' . $quote->id . ', ' . $quote->quote . ', ' . $quote->author_id . ', ' . $quote->category_id . ')';

    // Update quote
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
            array('message' => 'Update not completed')
        );
    }
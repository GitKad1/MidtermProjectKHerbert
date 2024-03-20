<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $quote = new Quote($db);

    // Get ID
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get post
    $quote->read_single();

    if ($quote->quote) {
        // Create array
        $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author,
        'category' => $quote->category,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id,
        );

        // Make JSON
        print_r(json_encode($quote_arr));
    } else {
        echo json_encode(
            array('message' => 'No Quote Found')
        );
    }
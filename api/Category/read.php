<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $category = new Category($db);

    // author query
    $result = $category->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if($num > 0) {
        // Author array
        $categories_arr = array();
        $categories_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $categories_item = array(
                'id' => $id,
                'category' => html_entity_decode($category)
            );

            // Push to "data"
            array_push($categories_arr['data'], $categories_item);
        }

        // Turn to JSON & output
        echo json_encode($categories_arr);
    } else {
        // No Posts
        echo json_encode(
            array('message' => 'No Posts Found')
        );
    }
<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $quote = new Quote($db);

    if(strpos($request, "?author_id=")) {
        // Get ID
        $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : die();

        // Get Author
        $result = $quote->read_by_author_id();

        // Get row count
        $num = $result->rowCount();

        // Check if any authors
        if($num > 0) {
            // Author array
            $quotes_arr = array();
            $quotes_arr['data'] = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $quote_item = array(
                    'id' => $id,
                    'quote' => html_entity_decode($quote),
                    'author' => $author,
                    'category' => $category,
                    'author_id' => $author_id,
                    'category_id' => $category_id
                );

                // Push to "data"
                array_push($quotes_arr['data'], $quote_item);
            }

            // Turn to JSON & output
            echo json_encode($quotes_arr);
        } else {
            // No Posts
            echo json_encode(
                array('message' => 'author_id not found')
            );
        }
    } elseif(strpos($request, "?category_id=")) {
        // Get ID
        $quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die();

        // Get Author
        $result = $quote->read_by_category_id();

        // Get row count
        $num = $result->rowCount();

        // Check if any quotes
        if($num > 0) {
            // Category array
            $quotes_arr = array();
            $quotes_arr['data'] = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $quote_item = array(
                    'id' => $id,
                    'quote' => html_entity_decode($quote),
                    'author' => $author,
                    'category' => $category,
                    'author_id' => $author_id,
                    'category_id' => $category_id
                );

                // Push to "data"
                array_push($quotes_arr['data'], $quote_item);
            }

            // Turn to JSON & output
            echo json_encode($quotes_arr);
        } else {
            // No Posts
            echo json_encode(
                array('message' => 'category_id not found')
            );
        }
    } else {
        // author query
        $result = $quote->read();
        // Get row count
        $num = $result->rowCount();

        // Check if any posts
        if($num > 0) {
            // Author array
            $quotes_arr = array();
            $quotes_arr['data'] = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $quote_item = array(
                    'id' => $id,
                    'quote' => html_entity_decode($quote),
                    'author' => $author,
                    'category' => $category,
                    'author_id' => $author_id,
                    'category_id' => $category_id
                );

                // Push to "data"
                array_push($quotes_arr['data'], $quote_item);
            }

            // Turn to JSON & output
            echo json_encode($quotes_arr);
        } else {
            // No Posts
            echo json_encode(
                array('message' => 'No Quotes Found')
            );
        }
    }
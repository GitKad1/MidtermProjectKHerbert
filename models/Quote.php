<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Post Properties
        public $id;
        public $quote;
        public $author;
        public $category;
        public $author_id;
        public $category_id;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quotes
        public function read() {
            // Create Query
            $query = 'SELECT
                    c.category as category,
                    a.author as author,
                    q.id,
                    q.quote,
                    q.category_id,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                Order BY
                    q.id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Post
        public function read_single() {
            // Create Query
            $query = 'SELECT
                    c.category as category,
                    a.author as author,
                    q.id,
                    q.quote,
                    q.category_id,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                WHERE
                    q.id = ?
                    LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Set properties
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
                $this->author_id = $row['author_id'];
                $this->category_id = $row['category_id'];
            }
        }

        // Get Single Post
        public function read_by_author_id() {
            // Create Query
            $query = 'SELECT
                    c.category as category,
                    a.author as author,
                    q.id,
                    q.quote,
                    q.category_id,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                WHERE
                    a.id = ?';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Bind ID
            $stmt->bindParam(1, $this->author_id);
        
            // Execute query
            $stmt->execute();
        
            return $stmt;
        }

        // Get Single Post
        public function read_by_category_id() {
            // Create Query
            $query = 'SELECT
                    c.category as category,
                    a.author as author,
                    q.id,
                    q.quote,
                    q.category_id,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                WHERE
                    c.id = ?';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Bind ID
            $stmt->bindParam(1, $this->category_id);
        
            // Execute query
            $stmt->execute();
        
            return $stmt;
        }

        // Create Quote
        public function create() {
            // Create queries
            $query = 'INSERT INTO ' . 
                $this->table . ' (quote, author_id, category_id)
                Values (:quote, :author_id, :category_id)';

            $DBAuthor = 'Select id From authors';
            $DBCategory = 'Select id From categories';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($DBAuthor);
            $stmt3 = $this->conn->prepare($DBCategory);

            // Execute statements
            $stmt2->execute();
            $stmt3->execute();

            $row2 = $stmt2->fetchALL(PDO::FETCH_COLUMN);
            $row3 = $stmt3->fetchALL(PDO::FETCH_COLUMN);
        
            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        
            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            

            //Check if the quote, author id or category id is missing
            if (!$this->quote || !$this->author_id || !$this->category_id) {
                return 1;
            }

            //Check if author id exists
            if (!in_array($this->author_id, $row2)) {
                return 2;
            }

            //Check if category id exists
            if (!in_array($this->category_id, $row3)) {
                return 3;
            }

            // Execute query
            if($stmt->execute() && $stmt->rowCount() > 0) {
                // Execute query
                if($stmt->execute()) {
                    $query2 = 'Select id 
                        FROM ' .
                            $this->table .'
                        Where
                            quote = :quote';

                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->bindParam(':quote', $this->quote);
                    $stmt2->execute();
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $this->id = $row['id'];
                    return 4;
                }
            }

            return 5;
        }

        // Update Quote
        public function update() {
            // Create queries
            $query = 'UPDATE ' . 
                $this->table . '
            SET 
                quote = :quote, 
                author_id = :author_id, 
                category_id = :category_id
            WHERE 
                id = :id';

            $DBAuthor = 'Select id From authors';
            $DBCategory = 'Select id From categories';

            // Prepare statements
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($DBAuthor);
            $stmt3 = $this->conn->prepare($DBCategory);

            // Execute statements
            $stmt2->execute();
            $stmt3->execute();

            $row2 = $stmt2->fetchALL(PDO::FETCH_COLUMN);
            $row3 = $stmt3->fetchALL(PDO::FETCH_COLUMN);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            //Check if the id, quote, author id or category id is missing
            if (!$this->id || !$this->quote || !$this->author_id || !$this->category_id) {
                return 1;
            }

            //Check if author id exists
            if (!in_array($this->author_id, $row2)) {
                return 2;
            }
            
            //Check if category id exists
            if (!in_array($this->category_id, $row3)) {
                return 3;
            }

            // Execute query
            if($stmt->execute() && $stmt->rowCount() > 0) {
                return 4;
            }
            
        
            return 5;
        }

        // Delete Quote
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return $stmt;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
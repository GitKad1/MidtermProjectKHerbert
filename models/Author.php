<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Post Properties
        public $id;
        public $author;


        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Authors
        public function read() {
            // Create Query
            $query = 'SELECT
                    author,
                    id
                FROM
                    ' . $this->table . '
                Order BY id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Author
        public function read_single() {
            // Create query
            $query = 'SELECT 
                author, 
                id
            FROM 
                ' . $this->table . '
            WHERE
                id = ?
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
                $this->author = $row['author'];
                $this->id = $row['id'];
            }
        }

        // Create Author
        public function create() {
            // Create queries
            $query = 'INSERT INTO ' . 
                $this->table . ' (author)
                Values (:author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
        
            // Bind data
            $stmt->bindParam(':author', $this->author);
            

            if ($this->author) {
                // Execute query
                if($stmt->execute()) {
                    $query2 = 'Select id 
                        FROM ' .
                            $this->table .'
                        Where
                            author = :author';

                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->bindParam(':author', $this->author);
                    $stmt2->execute();
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $this->id = $row['id'];
                    return $stmt;
                }
            }
        
            return false;
        }

        // Update Post
        public function update() {
            // Create query
            $query = 'UPDATE ' . 
                $this->table . '
                SET 
                    author = :author
                WHERE 
                    id = :id';

            $DBAuthor = 'Select id From authors';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($DBAuthor);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Execute statements
            $stmt2->execute();
        
            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            $row2 = $stmt2->fetchALL(PDO::FETCH_COLUMN);
        
            //Check if the id or author exist 
            if (!$this->id || !$this->author) {
                return 1;
            }

            //Check if author id exists
            if (!in_array($this->id, $row2)) {
                return 2;
            }

            // Execute query
            if($stmt->execute() && $stmt->rowCount() > 0) {
                return 3;
            }

            return 4;
        }

        // Delete Author
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            if ($this->id) {
                // Execute query
                if($stmt->execute() && $stmt->rowCount() > 0) {
                    return true;
                }
            }

            return false;
        }
    }
<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Post Properties
        public $id;
        public $category;


        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Authors
        public function read() {
            // Create Query
            $query = 'SELECT
                    category,
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
                category, 
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
                $this->category = $row['category'];
                $this->id = $row['id'];
            }
        }

        // Create Author
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . 
                $this->table . ' (category)
                Values (:category)';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
        
            // Bind data
            $stmt->bindParam(':category', $this->category);            

            if ($this->category) {
                // Execute query
                if($stmt->execute()) {
                    $query2 = 'Select id 
                        FROM ' .
                            $this->table .'
                        Where
                            category = :category';

                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->bindParam(':category', $this->category);
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
                    caegory = :category
                WHERE 
                    id = :id';

            $DBCategory = 'Select id From categories';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt3 = $this->conn->prepare($DBCategory);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Execute statements
            $stmt3->execute();
        
            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            $row3 = $stmt3->fetchALL(PDO::FETCH_COLUMN);
        
            //Check if the id or category exists 
            if (!$this->id || !$this->category) {
                return 1;
            }

            //Check if category id exists
            if (!in_array($this->id, $row3)) {
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
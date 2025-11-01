<?php
require_once "Database.php";
require_once "Coffee.php";

class CoffeeManager {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllCoffees() {
        $stmt = $this->conn->prepare("SELECT * FROM coffees ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchCoffees($keyword) {
        $stmt = $this->conn->prepare("SELECT * FROM coffees WHERE name LIKE ? ORDER BY id DESC");
        $stmt->execute(['%'.$keyword.'%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCoffee(Coffee $coffee, $imageName='') {
        $stmt = $this->conn->prepare(
            "INSERT INTO coffees (name, price, category, image) VALUES (?,?,?,?)"
        );
        $stmt->execute([$coffee->getName(), $coffee->getPrice(), $coffee->getCategory(), $imageName]);
    }

    public function getCoffeeById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM coffees WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCoffee($id, Coffee $coffee, $imageName='') {
        if($imageName){
            $stmt = $this->conn->prepare(
                "UPDATE coffees SET name=?, price=?, category=?, image=? WHERE id=?"
            );
            $stmt->execute([$coffee->getName(), $coffee->getPrice(), $coffee->getCategory(), $imageName, $id]);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE coffees SET name=?, price=?, category=? WHERE id=?"
            );
            $stmt->execute([$coffee->getName(), $coffee->getPrice(), $coffee->getCategory(), $id]);
        }
    }

    public function deleteCoffee($id) {
        $stmt = $this->conn->prepare("DELETE FROM coffees WHERE id=?");
        $stmt->execute([$id]);
    }
}

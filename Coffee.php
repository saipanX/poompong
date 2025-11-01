<?php
class Coffee {
    private $name;
    private $price;
    private $category;

    public function __construct($name, $price, $category){
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getName(){ return $this->name; }
    public function getPrice(){ return $this->price; }
    public function getCategory(){ return $this->category; }
}

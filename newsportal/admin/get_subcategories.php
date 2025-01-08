<?php
require_once '../config/database.php';

if (isset($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];
    
    $query = "SELECT id, name FROM subcategories 
              WHERE category_id = $category_id AND is_deleted = 0 
              ORDER BY name";
    $result = $conn->query($query);
    
    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($subcategories);
} 
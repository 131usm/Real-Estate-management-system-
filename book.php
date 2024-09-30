<?php

if (isset($_POST['start_date'], $_POST['end_date'], $_POST['amount'])) {

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $amount = $_POST['amount'];
    $property_id = $_POST['property_id'];
    $tenant_id = Auth::user()['id'];
    // Convert start_date and end_date strings to DateTime objects
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);
    // Calculate the difference in days
    $months = floor($start_date_obj->diff($end_date_obj)->days / 30);

    
    
    $sql = "SELECT rent, tenancy, rent * tenancy * 12 as total_rent FROM properties WHERE property_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $property_id);
    $stmt->bind_result($rent, $tenancy, $total_rent);
    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();
    $stmt->close();
    $amount_owed = $total_rent - $amount;
    
    $sql = "INSERT INTO tenancy (property_id, tenant_id, rental_fee, tenancy_length, start_date, end_date, amount_paid, amount_owed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('iidissdd', $property_id, $tenant_id, $rent, $tenancy, $start_date, $end_date, $amount, $amount_owed);
    $stmt->execute();
    $stmt->close();
    include 'success.php';

}else{
    header('location: /properties');
}


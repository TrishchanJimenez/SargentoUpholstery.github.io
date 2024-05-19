<?php
    session_start();
    require '../database_connection.php'; 

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }

    $id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    $addresses = [];
    $stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $addresses[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/account.css">
</head>
<body>
    <?php require '../header.php'; ?>
    <h1 class="profile-title">Personal Info</h1>
    <table class="profile-container">
        <tr>
            <td>Name:</td>
            <td data-type="name">
                <span><?php echo $result['name']; ?></span>
                <img src="/websiteimages/icons/edit-icon.svg" alt="" class="edit-icon">
            </td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td data-type="contact_number">
                <span><?php echo $result['contact_number']; ?></span>
                <img src="/websiteimages/icons/edit-icon.svg" alt="" class="edit-icon">
            </td>
        </tr>
        <tr>
            <td>Email Address:</td>
            <td data-type="email">
                <span><?php echo $result['email']; ?></span>
                <img src="/websiteimages/icons/edit-icon.svg" alt="" class="edit-icon">
            </td>
        </tr>
        <tr>
            <td>Addresses:</td>
            <td class="address-container">
                <div class="all-address">
                    <?php foreach ($addresses as $address): ?>
                        <div class="address" data-address-id="<?php echo $address['address_id']; ?>">
                            <span><?php echo $address['address'] ?></span>
                            <div class="action-icons">
                                <img src="/websiteimages/icons/edit-icon.svg" alt="" class="edit-icon">
                                <img src="/websiteimages/icons/remove-icon.svg" alt="" class="delete-icon">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="add-address">
                    <img src="/websiteimages/icons/add-icon.svg" alt="" class="add-icon">
                    <span>Add Address</span> 
                </div>
            </td>
        </tr>
    </table>
    <script src="../js/globals.js"></script>
    <script src="../js/account.js"></script>
</body>
</html>
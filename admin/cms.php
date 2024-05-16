<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }
    
    $query = "
    SELECT category, color, img_path
    FROM Works
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $works = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/cms.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php require 'sidebar.php' ?>

        <div class="body">
            <p>Gallery Images</p>
            <div class="contents-container">
                <div class="add_container">
                    <button class="add" onclick="openModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M6 8H0V6H6V0H8V6H14V8H8V14H6V8Z" fill="white"/>
                        </svg>
                        Add
                    </button>
                </div>
                <div>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th class="hidden">Works id</th>
                                <th>Furniture Type</th>
                                <th>Furniture Color</th>
                                <th>Picture</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Display existing gallery images here -->
                            <?php foreach ($works as $index => $work) : ?>
                                <tr>
                                    <td class="hidden"><?php echo ($work['works_id'])?></td>
                                    <td><?php echo ucfirst($work['category']); ?></td>
                                    <td><?php echo ucfirst($work['color']); ?></td>
                                    <td>
                                        <div class="im">
                                            <img src="<?php echo $work['img_path']; ?>" class="images">
                                        </div>

                                    </td>
                                    <td>
                                        <div class="ED">
                                            <!-- Assuming this button is generated within a loop to handle multiple furniture items -->
                                            <?php
                                            $img_path = isset($work['img_path']) ? htmlspecialchars($work['img_path']) : '';
                                            $works_id = isset($work['works_id']) ? htmlspecialchars($work['works_id']) : '';
                                            $color = isset($work['color']) ? htmlspecialchars($work['color']) : '';
                                            $type = isset($work['category']) ? htmlspecialchars($work['category']) : '';
                                            ?>
                                            <button class="edit" onclick="editFurniture('<?php echo $type; ?>', '<?php echo $color; ?>', '<?php echo $img_path; ?>', '<?php echo $works_id; ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 19H6.425L16.2 9.225L14.775 7.8L5 17.575V19ZM3 21V16.75L16.2 3.575C16.4 3.39167 16.6208 3.25 16.8625 3.15C17.1042 3.05 17.3583 3 17.625 3C17.8917 3 18.15 3.05 18.4 3.15C18.65 3.25 18.8667 3.4 19.05 3.6L20.425 5C20.625 5.18333 20.7708 5.4 20.8625 5.65C20.9542 5.9 21 6.15 21 6.4C21 6.66667 20.9542 6.92083 20.8625 7.1625C20.7708 7.40417 20.625 7.625 20.425 7.825L7.25 21H3ZM15.475 8.525L14.775 7.8L16.2 9.225L15.475 8.525Z" fill="white"/>
                                                </svg>
                                                Edit
                                            </button>

                                            <?php $works_id = isset($work['works_id']) ? htmlspecialchars($work['works_id']) : ''; ?>
                                            <button class="delete" onclick="deleteFurniture('<?php echo $works_id; ?>')">
                                                <img src="../websiteimages/icons/cancel.png" class="del">
                                                Delete      
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

     
    <!-- Modal for Add Furniture -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add Furniture</h2>
            <form id="addForm" action="add_furniture.php" method="post" class="forms" enctype="multipart/form-data">
                <label for="category">Category:</label><br>
                <input type="text" id="category" name="category" class="input" required><br>
                <label for="color">Color:</label><br>
                <input type="text" id="color" name="color" class="input" required><br><br>
                <!-- File input for image upload -->
                <input type="file" id="fileInput" name="file[]" accept="image/*" class="Images_button" multiple><br>
                <!-- Preview container for uploaded images -->
                <div id="imagePreviews"></div><br>
                <div class="button_container">
                    <button type="submit" class="button_S">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M5 19H6.425L16.2 9.225L14.775 7.8L5 17.575V19ZM3 21V16.75L16.2 3.575C16.4 3.39167 16.6208 3.25 16.8625 3.15C17.1042 3.05 17.3583 3 17.625 3C17.8917 3 18.15 3.05 18.4 3.15C18.65 3.25 18.8667 3.4 19.05 3.6L20.425 5C20.625 5.18333 20.7708 5.4 20.8625 5.65C20.9542 5.9 21 6.15 21 6.4C21 6.66667 20.9542 6.92083 20.8625 7.1625C20.7708 7.40417 20.625 7.625 20.425 7.825L7.25 21H3ZM15.475 8.525L14.775 7.8L16.2 9.225L15.475 8.525Z" fill="white"/>
                        </svg>
                        Save
                    </button>
                    <button type="button" class="button_C" onclick="cancelButton()">
                        <img src="../websiteimages/icons/cancel.png">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>


    
    <!-- Modal for editing -->
    <?php $works_id = isset($work['works_id']) ? htmlspecialchars($work['works_id']) : ''; ?>
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="editcancelbutton()">&times;</span>
            <h2>Edit Furniture</h2>
            <form id="editForm" action="update_furniture.php" method="post" class="forms" enctype="multipart/form-data">
                <input type="hidden" id="editFurnitureId" name="editFurnitureId">
                <label for="editCategory">Category:</label><br>
                <input type="text" id="editCategory" name="editCategory" class="input" value='<?php echo $work['category']; ?>' required><br>
                <label for="editColor">Color:</label><br>
                <input type="text" id="editColor" name="editColor" class="input" value='<?php echo $work['color']; ?>'  required><br><br>
                <!-- Current Image -->
                <img src="<?php echo $work['img_path']; ?>" id="currentImage" width="150" height="150"><br>
                <!-- File input for new image upload -->
                <label for="editFileInput" class="fileLabel">Select New Image:</label>
                <input type="file" id="editFileInput" name="editFile" accept="image/*" class="Images_button"><br>
                <div class="button_container">
                    <button type="submit" class="button_S">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M5 19H6.425L16.2 9.225L14.775 7.8L5 17.575V19ZM3 21V16.75L16.2 3.575C16.4 3.39167 16.6208 3.25 16.8625 3.15C17.1042 3.05 17.3583 3 17.625 3C17.8917 3 18.15 3.05 18.4 3.15C18.65 3.25 18.8667 3.4 19.05 3.6L20.425 5C20.625 5.18333 20.7708 5.4 20.8625 5.65C20.9542 5.9 21 6.15 21 6.4C21 6.66667 20.9542 6.92083 20.8625 7.1625C20.7708 7.40417 20.625 7.625 20.425 7.825L7.25 21H3ZM15.475 8.525L14.775 7.8L16.2 9.225L15.475 8.525Z" fill="white"/>
                        </svg>
                        Save
                    </button>
                    <button type="button" class="button_C" onclick="editcancelbutton()">
                        <img src="../websiteimages/icons/cancel.png">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>




    <script src="../js/modal.js"></script>
</body>
</html>





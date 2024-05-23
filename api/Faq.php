<?php
    // Faqs Table;
    // +----------+------+------+-----+---------+----------------+
    // | Field    | Type | Null | Key | Default | Extra          |
    // +----------+------+------+-----+---------+----------------+
    // | faq_id   | int  | NO   | PRI | NULL    | auto_increment |
    // | question | text | NO   |     | NULL    |                |
    // | answer   | text | NO   |     | NULL    |                |
    // +----------+------+------+-----+---------+----------------+
    require '../database_connection.php';
    function updateFaq() {
        global $conn;
        $faq_id = $_POST['faq_id'];
        $question = $_POST['faq-question'];
        $answer = $_POST['faq-answer'];

        $sql = "UPDATE faqs SET question = :question, answer = :answer WHERE faq_id = :faq_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':faq_id', $faq_id);
        $stmt->execute();
    }

    function deleteFaq() {
        global $conn;
        $faq_id = $_POST['faq_id'];
        $sql = "DELETE FROM faqs WHERE faq_id = :faq_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':faq_id', $faq_id);
        $stmt->execute();
    }
    
    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            // case 'insert':
            //     addFaq();
            //     break;
            case 'update':
                updateFaq();
                break;
            case 'delete':
                deleteFaq();
                break;
        }
    }
?>
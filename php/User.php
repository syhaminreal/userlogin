<?php 

/**
 * Retrieve user details by user ID.
 *
 * @param int $id The user ID.
 * @param PDO $db The PDO database connection object.
 * @return array|null The user details as an associative array, or null if no user is found.
 */
function getUserById($id, $db) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    
    try {
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Log error message and rethrow or handle as needed
        error_log("Database query failed: " . $e->getMessage());
        return null;
    }

    // Fetch the user data as an associative array or return null if no user found
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return null;
    }
}

?>

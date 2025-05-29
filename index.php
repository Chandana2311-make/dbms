<?php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Inventory</title>
</head>
<body>
    <h1>Product Inventory</h1>
    <a href="add_product.php">Add New Product</a>
    <br><br>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Name</th><th>Quantity</th><th>Price</th><th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= $row["name"] ?></td>
            <td><?= $row["quantity"] ?></td>
            <td><?= $row["price"] ?></td>
            <td><a href="delete_product.php?id=<?= $row["id"] ?>">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

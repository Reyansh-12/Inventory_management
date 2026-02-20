<?php
include BASE_PATH . "/src/controllers/dbConnection.php";

if (!isset($_GET['order_id'])) {
    die("Invalid Order");
}

$order_id = mysqli_real_escape_string($con, $_GET['order_id']);

$query = "SELECT * FROM order_list WHERE order_id = '$order_id'";
$result = mysqli_query($con, $query);
?>

<h3>Order #<?= htmlspecialchars($order_id) ?></h3>

<table border="1" cellpadding="10">
    <tr>
        <th>Product</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= htmlspecialchars($row['product']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['brand']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>₹<?= $row['price'] ?></td>
    </tr>
<?php } ?>

</table>
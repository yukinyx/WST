<?php
include "check_ath.php";
include "functions.php";
$pdo = get_connection();

// --- HANDLE ADD PRODUCT ---
if(isset($_POST["sumbit1"])) {
    $product_name = $_POST["product_name"];
    $category_name = $_POST["category_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    
    // Image Upload
    $fnm = $_FILES["product_image"]["name"];
    $dst = "product-image/default.jpg"; // Fallback
    
    if($fnm) {
        $v1 = rand(1111,9999);
        $v2 = rand(1111,9999);
        $v3 = md5($v1.$v2);
        $dst = "product-image/".$v3.$fnm;
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $dst);
    }

    // Check duplicate
    $stmt = $pdo->prepare("SELECT * FROM product where product_name=?");
    $stmt->execute([$product_name]);
    if($stmt->rowCount() > 0) {
        echo "<script>alert('Product already exists!');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO product (product_name, product_price, quantity, image_file_name, category_name) VALUES (?,?,?,?,?)");
        $stmt->execute([$product_name, $price, $quantity, $dst, $category_name]);
        echo "<script>alert('Product added successfully!'); window.location.href='products.php';</script>";
    }
}

// --- HANDLE EDIT PRODUCT ---
if(isset($_POST["edit_product"])) {
    $pid = $_POST["pid"];
    $product_name = $_POST["e_product_name"];
    $category_name = $_POST["e_category_name"];
    $price = $_POST["e_product_price"];
    $quantity = $_POST["e_product_qty"];
    
    // Update basic info
    $sql = "UPDATE product SET product_name=?, category_name=?, product_price=?, quantity=? WHERE product_id=?";
    $params = [$product_name, $category_name, $price, $quantity, $pid];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Update Image if provided
    if(!empty($_FILES["e_product_image"]["name"])) {
        $fnm = $_FILES["e_product_image"]["name"];
        $v1 = rand(1111,9999);
        $v2 = rand(1111,9999);
        $v3 = md5($v1.$v2);
        $dst = "product-image/".$v3.$fnm;
        move_uploaded_file($_FILES["e_product_image"]["tmp_name"], $dst);

        $stmt = $pdo->prepare("UPDATE product SET image_file_name=? WHERE product_id=?");
        $stmt->execute([$dst, $pid]);
    }
    
    echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
}

// --- HANDLE DELETE PRODUCT ---
if(isset($_POST["sum_delete"])) {
    $id_to_delete = $_POST["the_id"]; // Using ID now for safety
    $stmt = $pdo->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->execute([$id_to_delete]);
    echo "<script>alert('Product deleted successfully!'); window.location.href='products.php';</script>";
}
?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>
<div class="container-fluid">
  <div class="row">
    <?php include "./templates/sidebar.php"; ?>
      <div class="row">
          <div class="col-10">
              <h2>Product List</h2>
          </div>
          <div class="col-2">
              <a href="#" data-toggle="modal" data-target="#add_product_modal" class="btn btn-warning btn-sm">Add Product</a>
          </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="product_list">
			<?php 
                $res = $pdo->query("SELECT * FROM product");
                while($row = $res->fetch()){
            ?>
            <tr>
              <form action="" method="post">
                  <td style="vertical-align: middle;"><?php echo $row["product_id"]; ?></td>
                  <td style="vertical-align: middle;"><?php echo $row["product_name"]; ?></td>
                  <td style="vertical-align: middle;"><?php echo $row["category_name"]; ?></td>
                  <td style="vertical-align: middle;"><?php echo $row["quantity"]; ?></td>
                  <td style="vertical-align: middle;"><?php echo $row["product_price"]; ?></td>
                  <td style="vertical-align: middle;">
                      <?php $img = (strpos($row['image_file_name'], '/') === false) ? 'product-image/'.$row['image_file_name'] : $row['image_file_name']; ?>
                      <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" width="50">
                  </td>
                  
                  <td style="vertical-align: middle;">
                      <button type="button" class="btn btn-sm btn-info edit-btn" 
                          data-toggle="modal" 
                          data-target="#edit_product_modal"
                          data-id="<?php echo $row['product_id']; ?>"
                          data-name="<?php echo $row['product_name']; ?>"
                          data-category="<?php echo $row['category_name']; ?>"
                          data-price="<?php echo $row['product_price']; ?>"
                          data-qty="<?php echo $row['quantity']; ?>"
                          data-img="<?php echo $img; ?>">
                          Edit
                      </button>
                      
                      <input type="hidden" name="the_id" value="<?php echo $row["product_id"]; ?>">
                      <button type="submit" name="sum_delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                  </td>
              </form>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Category Name</label>
                        <select class="form-control" name="category_name">
                            <?php
                            $stmt = $pdo->query("SELECT category_name FROM category");
                            while ($row = $stmt->fetch()){
                                echo "<option value='".$row['category_name']."'>".$row['category_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Product Image <small>(jpg, jpeg, png only)</small></label>
                        <input type="file" name="product_image" class="form-control" accept=".jpg, .jpeg, .png">
                    </div>
                    <button type="submit" name="sumbit1" class="btn btn-primary" onclick="return confirm('Add this product?');">Add product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="pid" id="edit_pid">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="e_product_name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Category Name</label>
            <select class="form-control" name="e_category_name" id="edit_category">
                <?php
                $stmt = $pdo->query("SELECT category_name FROM category");
                while ($row = $stmt->fetch()){
                    echo "<option value='".$row['category_name']."'>".$row['category_name']."</option>";
                }
                ?>
            </select>
          </div>
          <div class="form-group">
            <label>Price</label>
            <input type="number" name="e_product_price" id="edit_price" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="e_product_qty" id="edit_qty" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Change Image <small>(Leave blank to keep current)</small></label>
            <input type="file" name="e_product_image" class="form-control" accept=".jpg, .jpeg, .png">
            <img id="edit_img_preview" src="" width="50" style="margin-top:10px;">
          </div>
          <button type="submit" name="edit_product" class="btn btn-primary" onclick="return confirm('Save changes?');">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>

<script>
// JS to Populate Edit Modal
$(document).on("click", ".edit-btn", function () {
    var pid = $(this).data('id');
    var name = $(this).data('name');
    var category = $(this).data('category');
    var price = $(this).data('price');
    var qty = $(this).data('qty');
    var img = $(this).data('img');

    $("#edit_pid").val(pid);
    $("#edit_name").val(name);
    $("#edit_category").val(category); // Selects the option with this value
    $("#edit_price").val(price);
    $("#edit_qty").val(qty);
    $("#edit_img_preview").attr("src", img);
});
</script>
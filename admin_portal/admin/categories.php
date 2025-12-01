<?php
include "check_ath.php";
include "functions.php";
$pdo = get_connection();

// --- HANDLE ADD CATEGORY ---
if(isset($_POST["sumbit1"])) {
    $category_name = $_POST["category"];
    $check = $pdo->prepare("SELECT * FROM category where category_name=?");
    $check->execute([$category_name]);
    
    if($check->rowCount() > 0) {
        echo "<script>alert('Category already exists!');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO category (category_name) VALUES (?)");
        $stmt->execute([$category_name]);
        echo "<script>alert('Category added successfully!'); window.location.href='categories.php';</script>";
    }
}

// --- HANDLE EDIT CATEGORY ---
if(isset($_POST["edit_category"])) {
    $cid = $_POST["cat_id"];
    $new_name = $_POST["e_category"];
    
    $stmt = $pdo->prepare("UPDATE category SET category_name=? WHERE category_id=?");
    $stmt->execute([$new_name, $cid]);
    echo "<script>alert('Category updated successfully!'); window.location.href='categories.php';</script>";
}

// --- HANDLE DELETE CATEGORY ---
if(isset($_POST["sum_delete"])) {
    $cat_name = $_POST["the_id"];
    $stmt = $pdo->prepare("DELETE FROM category WHERE category_name = ?");
    $stmt->execute([$cat_name]);
    echo "<script>alert('Category deleted successfully!'); window.location.href='categories.php';</script>";
}
?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>
<div class="container-fluid">
  <div class="row">
    <?php include "./templates/sidebar.php"; ?>
      <div class="row">
      	<div class="col-10">
      		<h2>Manage Category</h2>
      	</div>
      	<div class="col-2">
      		<a href="#" data-toggle="modal" data-target="#add_category_modal" class="btn btn-warning btn-sm">Add Category</a>
      	</div>
      </div>
      
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="category_list">
            <?php
            $res=$pdo->query("SELECT * FROM category");
            while($row = $res->fetch()){
            ?>
            <tr>
              <form action="" method="post">
                  <td><?php echo $row["category_id"]; ?></td>
                  <td><?php echo $row["category_name"]; ?></td>
                  <td>
                      <button type="button" class="btn btn-sm btn-info edit-category-btn"
                          data-toggle="modal"
                          data-target="#edit_category_modal"
                          data-id="<?php echo $row['category_id']; ?>"
                          data-name="<?php echo $row['category_name']; ?>">
                          Edit
                      </button>
                      
                      <input type="hidden" name="the_id" value="<?php echo $row["category_name"]; ?>">
                      <button type="submit" name="sum_delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
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

<div class="modal fade" id="add_category_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
        		<label>Category Name</label>
        		<input type="text" name="category" class="form-control" required>
        	</div>
        	<button type="submit" name="sumbit1" class="btn btn-primary" onclick="return confirm('Add category?');">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <input type="hidden" name="cat_id" id="edit_cat_id">
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="e_category" id="edit_cat_name" class="form-control" required>
          </div>
          <button type="submit" name="edit_category" class="btn btn-primary" onclick="return confirm('Save changes?');">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>

<script>
$(document).on("click", ".edit-category-btn", function () {
    $("#edit_cat_id").val($(this).data('id'));
    $("#edit_cat_name").val($(this).data('name'));
});
</script>
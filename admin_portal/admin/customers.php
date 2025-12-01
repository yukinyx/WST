<?php
include "check_ath.php";
include "functions.php";
$pdo = get_connection();

// --- HANDLE EDIT CUSTOMER ---
if(isset($_POST["edit_customer"])) {
    $uid = $_POST["uid"];
    $name = $_POST["e_name"];
    $phone = $_POST["e_phone"];
    
    $stmt = $pdo->prepare("UPDATE user SET customerName=?, phone_number=? WHERE UserId=?");
    $stmt->execute([$name, $phone, $uid]);
    echo "<script>alert('Customer updated successfully!'); window.location.href='customers.php';</script>";
}

// --- HANDLE DELETE CUSTOMER ---
if(isset($_POST["sum_delete"])) {
    $user_to_delete = $_POST["the_id"];
    $stmt = $pdo->prepare("DELETE FROM user WHERE email = ?");
    $stmt->execute([$user_to_delete]);
    echo "<script>alert('Customer deleted successfully!'); window.location.href='customers.php';</script>";
}
?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>
<div class="container-fluid">
  <div class="row">
    <?php include "./templates/sidebar.php"; ?>
      <div class="row">
      	<div class="col-10">
      		<h2>Customers</h2>
      	</div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="customer_list">
            <?php
            $res=$pdo->query("SELECT UserId,customerName,email,phone_number FROM user");
            while($row = $res->fetch()){
            ?>
            <tr>
              <form action="" method="post">
                  <td><?php echo $row["UserId"]; ?></td>
                  <td><?php echo $row["customerName"]; ?></td>
                  <td><?php echo $row["email"]; ?></td>
                  <td><?php echo $row["phone_number"]; ?></td>
    
                  <td>
                      <button type="button" class="btn btn-sm btn-info edit-customer-btn"
                          data-toggle="modal"
                          data-target="#edit_customer_modal"
                          data-id="<?php echo $row['UserId']; ?>"
                          data-name="<?php echo $row['customerName']; ?>"
                          data-phone="<?php echo $row['phone_number']; ?>">
                          Edit
                      </button>
                      
                      <input type="hidden" name="the_id" value="<?php echo $row["email"]; ?>">
                      <button type="submit" name="sum_delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</button>
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

<div class="modal fade" id="edit_customer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <input type="hidden" name="uid" id="edit_uid">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="e_name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="e_phone" id="edit_phone" class="form-control">
          </div>
          <div class="form-group">
          <button type="submit" name="edit_customer" class="btn btn-primary" onclick="return confirm('Save changes?');">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>

<script>
$(document).on("click", ".edit-customer-btn", function () {
    $("#edit_uid").val($(this).data('id'));
    $("#edit_name").val($(this).data('name'));
    $("#edit_phone").val($(this).data('phone'));

});
</script>
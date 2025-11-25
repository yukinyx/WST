 <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">RedMarket Admin Portal</a>

  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
    	<?php
    		if (isset($_SESSION['admin_id'])) {
    			?>
    				<button onclick="openLogoutModal()">Sign out</button>
    			<?php
    		}else{
    			$uriAr = explode("/", $_SERVER['REQUEST_URI']);
    			$page = end($uriAr);
    			if ($page === "login_form.php") {
    				?>
	    				<a class="nav-link" href="../admin/login.php">login</a>
	    			<?php
    			}else{
    				?>
	    				<a class="nav-link" href="../admin/login.php">login</a>
	    			<?php
    			}


    			
    		}

    	?>
      
    </li>
  </ul>
</nav>

<style>
.modal2 {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
</style>

        <div id="logoutModal" class="modal2" style="display: none;">
    <div class="modal-content2" style="
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        font-family: 'Inter', sans-serif;
        color: black;
    ">
        <h2>Confirm Logout</h2>
        <p>Do you really want to logout?</p>
        <div style="margin-top: 20px;">
            <button class="confirm-button2" onclick="confirmLogout()" style="
                background:rgba(159, 35, 30, 1);
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-right: 10px;
            ">Yes, Logout</button>
            <button class="cancel-button2" onclick="closeLogoutModal()" style="
                background: #ddd;
                color: black;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            ">Cancel</button>
        </div>
    </div>
</div>

    </div>
  </div>

  <script>
    function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

function confirmLogout() {
    window.location.href = 'logout.php'; 
}
</script>
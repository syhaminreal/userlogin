<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
    	
    	<form class="shadow w-450 p-3" 
    	      action="php/signup.php" 
    	      method="post"
    	      enctype="multipart/form-data">

    		<h4 class="display-4 fs-1">Create Account</h4><br>
    		
    		<?php if(isset($_GET['error'])){ ?>
    		<div class="alert alert-danger" role="alert">
			  <?php echo htmlspecialchars($_GET['error']); ?>
			</div>
		    <?php } ?>

		    <?php if(isset($_GET['success'])){ ?>
    		<div class="alert alert-success" role="alert">
			  <?php echo htmlspecialchars($_GET['success']); ?>
			</div>
		    <?php } ?>
		  
		  <div class="mb-3">
		    <label class="form-label" for="fname">Full Name</label>
		    <input type="text" 
		           class="form-control"
		           id="fname"
		           name="fname"
		           value="<?php echo htmlspecialchars((isset($_GET['fname'])) ? $_GET['fname'] : "") ?>"
		           required>
		  </div>

		  <div class="mb-3">
		    <label class="form-label" for="uname">User Name</label>
		    <input type="text" 
		           class="form-control"
		           id="uname"
		           name="uname"
		           value="<?php echo htmlspecialchars((isset($_GET['uname'])) ? $_GET['uname'] : "") ?>"
		           required>
		  </div>

		  <div class="mb-3">
		    <label class="form-label" for="pass">Password</label>
		    <input type="password" 
		           class="form-control"
		           id="pass"
		           name="pass"
		           required>
		  </div>

		  <div class="mb-3">
		    <label class="form-label" for="pp">Profile Picture</label>
		    <input type="file" 
		           class="form-control"
		           id="pp"
		           name="pp">
		  </div>
		  
		  <button type="submit" class="btn btn-primary">Sign Up</button>
		  <a href="login.php" class="link-secondary">Login</a>
		</form>
    </div>
</body>
</html>

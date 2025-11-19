<?php 

?>

<h2>Register</h2>

<?php if (!empty($register_error)): ?>
  <div class="alert alert-danger"></div>
<?php endif; ?>

<form method="post">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Full Name</label>
    <input type="text" name="full_name" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input type="password" name="confirm_password" class="form-control">
  </div>

  <input type="hidden" name="action" value="register">
  <button class="btn btn-primary">Create Account</button>
</form>
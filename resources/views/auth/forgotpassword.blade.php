<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    a{
        text-decoration: none;
    }
    a:hover{
        text-decoration: underline;
    }
</style>
</head>
<body class="bg-light">

  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="col-md-4">
      
      <div class="card shadow-sm">
        <div class="card-body p-4">
          
          <h4 class="text-center mb-4">Reset Password</h4>

          <form method="post">
            
            <div class="mb-3">
              <label for="email" class="form-label">Enter your email to reset password</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>

           <div class="d-grid mb-3">
              <a class="text-center" href='/login'>Back to login</a>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require_once "Model.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="css/bootstrap.min.js"></script>
  <title>Index</title>
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">Employees MIS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php if (!isset($_SESSION['user'])) : ?>
            <li class="nav-item">
              <button class="nav-link text-white btn btn-primary" data-bs-toggle="modal" data-bs-target="#login-form-modal">Login</button>
            </li>
            <li class="nav-item">
              <button class="nav-link text-white btn btn-primary" data-bs-toggle="modal" data-bs-target="#sign-up-form-modal">Register</button>
            </li>

          <?php else : ?>
            <li class="nav-item text-white">
              <form method="POST">
                <button name="logout" class="btn btn-primary">Logout</button>
              </form>
            </li>

          <?php endif ?>

      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <?php include_once('message.php') ?>
    <?php if (isset($_SESSION['user'])) : ?>
      <div class="card">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card shadow">
            <div class="card-header">
              <h3>List of Employees
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#create-form-modal">
                  New
                </button>
              </h3>

            </div>
            <div class="card-body">
              <div class="modal fade" id="create-form-modal" tabindex="-1" aria-labelledby="Form-modal" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                      <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="form-modal-label">Add Employee</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="form-floating mb-2">
                          <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First name">
                          <label for="first_name">First name</label>
                        </div>
                        <div class="form-floating mb-2">
                          <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last name">
                          <label for="last_name">Last name</label>
                        </div>
                        <div class="form-floating mb-2">
                          <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                          <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-2">
                          <input type="file" name="photo" id="photo" class="form-control">
                          <label for="photo">Photo</label>
                        </div>
                        <div class="form-floating mb-2">
                          <input type="text" name="address" id="address" class="form-control" placeholder="Address">
                          <label for="address">Address</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" name="save">Save</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <table class="table table-hover table-responsive">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Photo</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $i = 1;
                  foreach ($records as $key => $val) {
                  ?>
                    <tr>
                      <td class="text-center"><?= $i++; ?></td>
                      <td>
                        <img src="images/<?= $val['photo'] ?>" alt="<?= $val['photo'] ?>" width="50" height="50" style="border-radius: 50%">
                      </td>
                      <td><?= $val['first_name'] ?></td>
                      <td><?= $val['last_name'] ?></td>
                      <td><?= $val['email'] ?></td>
                      <td><?= $val['address'] ?></td>
                      <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#update-form-modal<?= $val['id'] ?>">
                          Edit
                        </button>
                        <div class="modal fade" id="update-form-modal<?= $val['id'] ?>" tabindex="-1" aria-labelledby="update-form-modal-label" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header bg-primary text-white">
                                  <h5 class="modal-title" id="update-form-modal-label">Edit</h5>
                                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-floating mb-2">
                                    <input type="hidden" name="edit_id" value="<?= $val['id']; ?>" class="form-control">
                                  </div>
                                  <div class="form-floating mb-2">
                                    <input type="text" name="first_name" id="first_name" value="<?= $val['first_name']; ?>" class="form-control" placeholder="First name">
                                    <label for="first_name">First name</label>
                                  </div>
                                  <div class="form-floating mb-2">
                                    <input type="text" name="last_name" id="last_name" value="<?= $val['last_name']; ?>" class="form-control" placeholder="Last name">
                                    <label for="last_name">Last name</label>
                                  </div>
                                  <div class="form-floating mb-2">
                                    <input type="email" name="email" id="email" value="<?= $val['email']; ?>" class="form-control" placeholder="Email">
                                    <label for="email">Email</label>
                                  </div>
                                  <div class="form-floating mb-2">
                                    <input type="file" name="photo" id="photo" class="form-control" placeholder="Photo">
                                    <label for="photo">Photo</label>
                                  </div>
                                  <div class="form-floating mb-2">
                                    <input type="text" name="address" id="address" value="<?= $val['address']; ?>" class="form-control" placeholder="Address">
                                    <label for="address">Address</label>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                  <button class="btn btn-primary" name="edit">Save changes</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-modal<?= $val['id'] ?>">
                          Delete
                        </button>
                        <div class="modal fade" id="delete-modal<?= $val['id'] ?>" tabindex="-1" aria-labelledby="delete-modal-label" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form method="POST">
                                <div class="modal-header bg-danger text-white">
                                  <h5 class="modal-title" id="delete-modal-label">Delete</h5>
                                  <button class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p class="text-lead text-muted">Are you sure to delete user <span class="text-danger"><?= $val['first_name'] . " " . $val['last_name'] ?></span></p>
                                  <input type="hidden" name="delete_id" value="<?= $val['id'] ?>">
                                </div>
                                <div class="modal-footer">
                                  <button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                                  <button name="delete" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    <?php else : ?>
      <h1>Welcome Dear User! Please Log in</h1>
    <?php endif ?>
    <div class="card-body">
      <div class="modal fade" id="login-form-modal" tabindex="-1" aria-labelledby="Form-modal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST">
              <div class="modal-header text-white bg-primary">
                <h5 class="modal-title" id="form-modal-label">Sign In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <div class="form-floating mb-2">
                  <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                  <label for="email">Email</label>

                </div>
                <div class="form-floating mb-2">
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                  <label for="password">Password:</label>

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" name="login">Sign In</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="modal fade" id="sign-up-form-modal" tabindex="-1" aria-labelledby="Form-modal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST">
              <div class="modal-header text-white bg-success">
                <h5 class="modal-title" id="form-modal-label">Sign Up</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-floating mb-2">
                  <input type="text" name="user_name" id="user_name" class="form-control" placeholder="User Name">
                  <label for="user_name">User Name</label>
                </div>

                <div class="form-floating mb-2">
                  <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                  <label for="email">Email</label>

                </div>
                <div class="form-floating mb-2">
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                  <label for="password">Password:</label>

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" name="register">Sign Up</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>
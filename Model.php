<?php
require_once('Connection.php');

class Model
{
  public function sign_up()
  {

    $database = new Connection();
    $db = $database->open();

    if (isset($_POST['register'])) {
      $user_name = $_POST['user_name'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      if (empty($user_name)) {
        $_SESSION['message'] = 'User name is required!';
      } else {
        $user_name = htmlspecialchars($user_name);
      }

      if (empty($email)) {
        $_SESSION['message'] = 'Email is required!';
      } else {
        $email = htmlspecialchars($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $_SESSION['message'] = 'Please provide a valid email!';
        }
      }
      if (empty($password)) {
        $_SESSION['message'] = 'Password is required!';
      } else {
        $password = htmlspecialchars($password);
      }

      if (!isset($_SESSION['message'])) {
        $sql = "SELECT * FROM users WHERE email= :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam('email', $email);
        $stmt->execute();

        if ($stmt->rowCount()) {
          $_SESSION['message'] = 'Email already exist. Please try again';
        } else {
          $password = md5($password);
          $sql = 'INSERT INTO users (user_name , email, password)
          VALUES (:user_name, :email, :password)';
          $stmt = $db->prepare($sql);
          $stmt->bindParam('user_name', $user_name);
          $stmt->bindParam('email', $email);
          $stmt->bindParam('password', $password);
          $stmt->execute();

          $last_id = $db->lastInsertId();
          $sql = "SELECT * FROM users WHERE id =:id";
          $stmt = $db->prepare($sql);
          $stmt->bindParam('id', $last_id);
          $query = $stmt->execute();
          $user = $stmt->fetch();
          if ($query) {
            $_SESSION['user'] = $user;
            
          }
        }
      }
    }
  }
  public function sign_in()
  {
    $database = new Connection();
    $db = $database->open();
    if (isset($_POST['login'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];
      if (empty($email)) {
        $_SESSION['message'] = 'Email is required!';
      } else {
        $email = htmlspecialchars($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $_SESSION['message'] = 'Please provide a valid email!';
        }
      }
      if (empty($password)) {
        $_SESSION['message'] = 'Password is required!';
      } else {
        $password = htmlspecialchars($password);
      }
      if (!isset($_SESSION['message'])) {
        $password = md5($password);
        $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
        $stmt = $db->prepare($sql);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('password', $password);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($stmt->rowCount()) {
          $_SESSION['user'] = $user;
          $_SESSION['message'] = 'Welcome dear ' . $user['user_name'];
        }
      }
    }
  }
  public function sign_out()
  {
    if (isset($_POST['logout'])) {
      session_destroy();
      header("refresh:0;index.php");
    }
  }
  public function insert_data()
  {
    $database = new Connection();
    $db = $database->open();
    if (isset($_POST['save'])) {
      try {
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $image_file  = $_FILES["photo"]["name"];
        $type    = $_FILES["photo"]["type"];
        $size    = $_FILES["photo"]["size"];
        $temp    = $_FILES["photo"]["tmp_name"];
        $path = "images/" . $image_file;

        if (empty($first_name)) {
          $_SESSION['message'] = "Please enter first name";
        } else if (empty($last_name)) {
          $_SESSION['message'] = "Please enter last name";
        } else if (empty($email)) {
          $_SESSION['message'] = "Please enter email";
        } else if (empty($address)) {
          $_SESSION['message'] = "Please enter address";
        } else if (empty($image_file)) {
          $_SESSION['message'] = "Please Select Image";
        } else if ($type == "image/jpg" || $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif') //check file extension
        {
          if (!file_exists($path)) {
            if ($size < 5000000) {
              move_uploaded_file($temp, "images/" . $image_file);
            } else {
              $_SESSION['message'] = "Your File To large Please Upload 5MB Size";
            }
          } else {
            $_SESSION['message'] = "File Already Exists...Check Upload Folder";
          }
        } else {
          $_SESSION['message'] = "Upload JPG , JPEG , PNG & GIF File Formate.....CHECK FILE EXTENSION";
        }

        if (!isset($_SESSION['message'])) {
          $query = "INSERT INTO employees(first_name, 
          last_name, email, photo, address) VALUES(:f_name, :l_name,:email,:ph,:addr)";
          $insert_stmt = $db->prepare($query);
          $insert_stmt->bindParam(':f_name', $first_name);
          $insert_stmt->bindParam(':l_name', $last_name);
          $insert_stmt->bindParam(':email', $email);
          $insert_stmt->bindParam(':ph', $image_file);
          $insert_stmt->bindParam(':addr', $address);

          if ($insert_stmt->execute()) {
            header("refresh:1;index.php");
            $_SESSION['message'] = "Employee added successfully........";
          }
        }
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
  }

  public function fetch_all()
  {
    $database = new Connection();
    $db = $database->open();
    $query = "SELECT * FROM employees";
    try {
      $stm = $db->prepare($query);
      $stm->execute();
      return $stm->fetchAll();
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function update()
  {
    $database = new Connection();
    $db = $database->open();
    if (isset($_POST['edit_id'])) {
      try {
        $id = $_POST['edit_id'];
        $select_stmt = $db->prepare('SELECT * FROM employees WHERE id =:id');
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch();
        extract($row);
      } catch (PDOException $e) {
        $e->getMessage();
      }
    }

    if (isset($_POST['edit'])) {
      try {

        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $image_file  = $_FILES["photo"]["name"];
        $type    = $_FILES["photo"]["type"];
        $size    = $_FILES["photo"]["size"];
        $temp    = $_FILES["photo"]["tmp_name"];
        $path = "images/" . $image_file;

        $directory = "images/";
        if ($image_file) {
          if ($type == "image/jpg" || $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif') {
            if (!file_exists($path)) {
              if ($size < 5000000) {
                unlink($directory . $row['photo']);
                move_uploaded_file($temp, "images/" . $image_file);
              } else {
                $_SESSION['message'] = "Your File To large Please Upload 5MB Size";
              }
            } else {
              $_SESSION['message'] = "File Already Exists...Check Upload Folder";
            }
          } else {
            $_SESSION['message'] = "Upload JPG, JPEG, PNG & GIF File Formate.....CHECK FILE EXTENSION";
          }
        } else {
          $image_file = $row['photo'];
        }

        if (!isset($_SESSION['message'])) {
          $query = "UPDATE employees SET first_name=:f_name, last_name=:l_name, email=:email, 
          photo=:ph, address=:addr WHERE id=:id";
          $update_stmt = $db->prepare($query);
          $update_stmt->bindParam(':f_name', $first_name);
          $update_stmt->bindParam(':l_name', $last_name);
          $update_stmt->bindParam(':email', $email);
          $update_stmt->bindParam(':ph', $image_file);
          $update_stmt->bindParam(':addr', $address);
          $update_stmt->bindParam(':id', $id);

          if ($update_stmt->execute()) {
            header("refresh:1;index.php");
            $_SESSION['message'] = "Employee updated successfully.......";
          }
        }
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
  }

  public function delete()
  {
    $database = new Connection();
    $db = $database->open();
    if (isset($_POST['delete_id'])) {
      $id = $_POST['delete_id'];

      $select_stmt = $db->prepare('SELECT * FROM employees WHERE id =:id');
      $select_stmt->bindParam(':id', $id);
      $select_stmt->execute();
      $row = $select_stmt->fetch();
      unlink("images/" . $row['photo']);

      $delete_stmt = $db->prepare('DELETE FROM employees WHERE id =:id');
      $delete_stmt->bindParam(':id', $id);
      $delete_stmt->execute();
      $delete_stmt->fetchAll();
      header("refresh:1;index.php");
      $_SESSION['message'] = "Employee deleted successfully.......";
    }
  }
}
$data = new Model();
$records = $data->fetch_all();
$data->sign_up();
$data->sign_in();
$data->sign_out();
$data->insert_data();
$data->update();
$data->delete();

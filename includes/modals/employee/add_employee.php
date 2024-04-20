<?php
session_start();
include_once('includes/config.php');
include_once("includes/functions.php");

// Redirect user to login page if not logged in
if(empty($_SESSION['userlogin'])) {
    header('location: login.php');
    exit;
}

// Initialize error variable
$error = '';

// Check if form is submitted
if(isset($_POST['add_employee'])) {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_pass = $_POST['confirm_pass'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];

    // Generate random 6-digit employee ID
    $employee_id = 'EMP-' . substr(str_shuffle('1234567890'), 0, 6);

    // File upload handling
    $target_directory = "uploads/";
    $target_file = $target_directory . basename($_FILES["picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["picture"]["tmp_name"]);
    if($check === false) {
        $error = "File is not an image.";
        $_SESSION['error'] = $error;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $error = "File already exists.";
        $_SESSION['error'] = $error;
    }

    // Check file size
    if ($_FILES["picture"]["size"] > 500000) {
        $error = "File is too large.";
        $_SESSION['error'] = $error;
    }

    // Check if there were any errors during file upload
    if (!empty($error)) {
        header('location: add_employee.php'); // Redirect back to the form page
        exit;
    }

    // If no errors, proceed with database insertion
    $query = "INSERT INTO employees (firstname, lastname, username, email, password, employee_id, phone, department, designation, picture) 
              VALUES (:firstname, :lastname, :username, :email, :password, :employee_id, :phone, :department, :designation, :picture)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':designation', $designation);
    $stmt->bindParam(':picture', $target_file);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to success page
        header('location: employees.php');
        exit;
    } else {
        // Display error message
        $error = "Error occurred while adding employee.";
        $_SESSION['error'] = $error;
        header('location: add_employee.php'); // Redirect back to the form page
        exit;
    }
}
?>


<!-- Your HTML form goes here -->
<div id="add_employee" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                <input name="firstname" required class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name</label>
                                <input name="lastname" required class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Username <span class="text-danger">*</span></label>
                                <input name="username" required class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input name="email" required class="form-control" type="email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <input class="form-control" required name="password" type="password">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Confirm Password</label>
                                <input class="form-control" required name="confirm_pass" type="password">
                            </div>
                        </div>
                        <div class="col-sm-6">  
                            <div class="form-group">
                                <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                <input name="employee_id" readonly type="text" value="<?php echo $employee_id; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone </label>
                                <input name="phone" required class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Department <span class="text-danger">*</span></label>
                                <select required name="department" class="select form-control">
                                    <option value="">Select Department</option>
                                    <?php 
                                    $sql2 = "SELECT * from departments";
                                    $query2 = $dbh->prepare($sql2);
                                    $query2->execute();
                                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                    foreach($result2 as $row): ?>
                                        <option value="<?php echo htmlentities($row->department); ?>">
                                            <?php echo htmlentities($row->department); ?>
                                        </option>
                                    <?php endforeach; ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation <span class="text-danger">*</span></label>
                                <select required name="designation" class="select form-control">
                                    <option value="">Select Designation</option>
                                    <?php 
                                    $sql3 = "SELECT * from designations";
                                    $query3 = $dbh->prepare($sql3);
                                    $query3->execute();
                                    $result3 = $query3->fetchAll(PDO::FETCH_OBJ);
                                    foreach($result3 as $row): ?>
                                        <option value="<?php echo htmlentities($row->designation); ?>">
                                            <?php echo htmlentities($row->designation); ?>
                                        </option>
                                    <?php endforeach; ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Employee Picture</label>
                                <input class="form-control" required name="picture" type="file">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button type="submit" name="add_employee" class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

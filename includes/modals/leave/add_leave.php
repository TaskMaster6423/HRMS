<?php
session_start();
error_reporting(0);
include_once('../../../includes/config.php');
include_once("../../../includes/functions.php");

// Check if the form is submitted
if(isset($_POST['add_leave'])) {
    // Retrieve form data
    $employee_id = $_POST['employee'];
    $starting_at = $_POST['starting_at'];
    $ends_on = $_POST['ends_on'];
    $days_count = $_POST['days_count'];
    $reason = $_POST['reason'];

    // Validate form data (you may add more validation)
    if(empty($employee_id) || empty($starting_at) || empty($ends_on) || empty($days_count) || empty($reason)) {
        // Handle validation errors
        // Redirect back to the form with an error message or display error message
        exit;
    }

    // Insert leave request into the database
    $sql = "INSERT INTO leaves (employee_id, starting_at, ends_on, days_count, reason) VALUES (:employee_id, :starting_at, :ends_on, :days_count, :reason)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $query->bindParam(':starting_at', $starting_at, PDO::PARAM_STR);
    $query->bindParam(':ends_on', $ends_on, PDO::PARAM_STR);
    $query->bindParam(':days_count', $days_count, PDO::PARAM_INT);
    $query->bindParam(':reason', $reason, PDO::PARAM_STR);
    
    if($query->execute()) {
        // Leave request successfully added
        // Redirect to a success page or display a success message
        // Optionally, you can include a success message here
        echo "Leave request submitted successfully.";
    } else {
        // Failed to insert leave request
        // Handle the error accordingly
        // Optionally, you can include an error message here
        echo "Error: Failed to submit leave request.";
    }
}
?>

<!-- Rest of your HTML code here -->

<div id="add_leave" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action=""> <!-- Set action to empty string -->
                    <div class="form-group">
                        <label for="employee">Employee Leaving <span class="text-danger">*</span></label>
                        <select name="employee" id="employee" class="form-control select">
                            <option value="">Select Employee</option>
                            <?php 
                            $sql2 = "SELECT * FROM employees";
                            $query2 = $dbh->prepare($sql2);
                            $query2->execute();
                            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                            foreach($result2 as $row) {
                            ?>  
                            <option value="<?php echo htmlentities($row->id); ?>"><?php echo htmlentities($row->firstname . ' ' . $row->lastname); ?></option>
                            <?php } ?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="starting_at">From <span class="text-danger">*</span></label>
                        <input id="starting_at" name="starting_at" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label for="ends_on">To <span class="text-danger">*</span></label>
                        <input id="ends_on" name="ends_on" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label for="days_count">Number of days <span class="text-danger">*</span></label>
                        <input id="days_count" name="days_count" class="form-control" type="number">
                    </div>
                    <div class="form-group">
                        <label for="reason">Leave Reason <span class="text-danger">*</span></label>
                        <textarea id="reason" name="reason" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="submit-section">
                        <button type="submit" name="add_leave" class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

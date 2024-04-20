<?php
// Include database connection and any necessary functions
include_once('../../includes/config.php');
include_once('../../includes/functions.php');

// Check if holiday ID is set
if(isset($_GET['holiday_id'])) {
    // Retrieve holiday ID from URL parameter
    $holiday_id = $_GET['holiday_id'];

    // Query the database to fetch holiday details based on ID
    $sql = "SELECT * FROM holidays WHERE id = :holiday_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':holiday_id', $holiday_id, PDO::PARAM_INT);
    $query->execute();
    $holiday = $query->fetch(PDO::FETCH_ASSOC);

    // Check if holiday exists
    if($holiday) {
        // Holiday found, populate form fields with holiday details
        $holiday_name = $holiday['holiday_name'];
        $holiday_date = $holiday['holiday_date'];
?>
<div class="modal custom-modal fade" id="edit_holiday" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Holiday</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="edit_holiday_process.php" method="post"> <!-- Update the form action -->
                    <input type="hidden" name="holiday_id" value="<?php echo $holiday_id; ?>"> <!-- Hidden input field to pass holiday ID -->
                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="holiday_name" value="<?php echo htmlentities($holiday_name); ?>" type="text"> <!-- Populate holiday name -->
                    </div>
                    <div class="form-group">
                        <label>Holiday Date <span class="text-danger">*</span></label>
                        <div class="cal-icon"><input class="form-control datetimepicker" name="holiday_date" value="<?php echo htmlentities($holiday_date); ?>" type="text"></div> <!-- Populate holiday date -->
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit">Update Holiday</button> <!-- Change button text -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        // Holiday not found, display an error message or redirect
        echo "Holiday not found.";
    }
}
?>

<?php
// Include database connection and any necessary functions
include_once('includes/config.php');
include_once('includes/functions.php');

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $asset_name = $_POST['asset_name'];
    $purchase_date = $_POST['purchase_date'];
    $purchase_from = $_POST['purchase_from'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $supplier = $_POST['supplier'];
    $condition = $_POST['condition'];
    $warranty = $_POST['warranty'];
    $value = $_POST['value'];
    $asset_user = $_POST['asset_user'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Prepare and execute SQL query to insert form data into the database
    $sql = "INSERT INTO assets (asset_name, purchase_date, purchase_from, manufacturer, model, serial_number, supplier, asset_condition, warranty, value, asset_user, description, status) 
            VALUES (:asset_name, :purchase_date, :purchase_from, :manufacturer, :model, :serial_number, :supplier, :asset_condition, :warranty, :value, :asset_user, :description, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':asset_name', $asset_name, PDO::PARAM_STR);
    $query->bindParam(':purchase_date', $purchase_date);
    $query->bindParam(':purchase_from', $purchase_from, PDO::PARAM_STR);
    $query->bindParam(':manufacturer', $manufacturer, PDO::PARAM_STR);
    $query->bindParam(':model', $model, PDO::PARAM_STR);
    $query->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
    $query->bindParam(':supplier', $supplier, PDO::PARAM_STR);
    $query->bindParam(':asset_condition', $condition, PDO::PARAM_STR);
    $query->bindParam(':warranty', $warranty, PDO::PARAM_INT);
    $query->bindParam(':value', $value, PDO::PARAM_STR);
    $query->bindParam(':asset_user', $asset_user, PDO::PARAM_STR);
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    
    // Execute the query
    if($query->execute()) {
        // Data inserted successfully
        echo "<script>alert('Asset added successfully');</script>";
        echo "<script>window.location.href ='assets.php'</script>";
    } else {
        // Error occurred while inserting data
        echo "<script>alert('Error adding asset');</script>";
    }
}
?>
<div id="edit_asset" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form with PHP added for processing -->
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Asset Name</label>
                                <input class="form-control" type="text" name="asset_name" value="Dell Laptop">
                            </div>
                        </div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Asset Id</label>
												<input class="form-control" type="text" value="#AST-0001" readonly="">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Purchase Date</label>
												<input class="form-control datetimepicker" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Purchase From</label>
												<input class="form-control" type="text">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Manufacturer</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Model</label>
												<input class="form-control" type="text">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Serial Number</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Supplier</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Condition</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Warranty</label>
												<input class="form-control" type="text" placeholder="In Months">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Value</label>
												<input placeholder="$1800" class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Asset User</label>
												<select class="select">
													<option>John Doe</option>
													<option>Richard Miles</option>
												</select>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Description</label>
												<textarea class="form-control"></textarea>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Status</label>
												<select class="select">
													<option>Pending</option>
													<option>Approved</option>
													<option>Deployed</option>
													<option>Damaged</option>
												</select>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
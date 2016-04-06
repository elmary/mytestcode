<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

extract($_REQUEST);
$data[0] = "";
switch($func){
	case "add":
		if(empty($name))
		{
			$data[0] .= "*The name field is required.<br />";
		}
		
		if(empty($message))
		{
			$data[0] .= "*The message field is required.<br />";
		}
		
		if(empty($msg_date))
		{
			$data[0] .= "*The date field is required.<br />";
		}
		
		if($data[0] == "")
		{
			$mdate = date("Y-m-d",strtotime($msg_date));
			$sql = "INSERT INTO messages (name, message, date)
					VALUES ('".$name."','".$message."','".$mdate."')";
					
			$conn->query($sql);
			$conn->close();
			$data[0] = "saved";
		}
	break;
	
	case "display":
		$data[0] .= "<table class='table table-striped table-bordered table-condensed'>
			<tr>
				<th>Name</th>
				<th>Message</th>
				<th>Date</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>";
			
		$sql = "SELECT * FROM messages";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$data[0] .= "<tr>
					<td>".$row["name"]."</td>
					<td>".$row["message"]."</td>
					<td>".date("m-d-Y",strtotime($row["date"]))."</td>
					<td><a data-toggle='modal' href='#form-content'><button  class='btn btn-primary btn-small edit' mid = '".$row["id"]."'>Edit</button></a></td>
					<td><button class = 'delete btn btn-primary btn-small' mid = '".$row["id"]."'>Delete</button></td>
				</tr>";
			}
		} 
			
		$data[0] .= "</table>";
		$conn->close();
	break;
	
	case "delete":
		$sql = "DELETE FROM messages WHERE id = '".$id."'";
		$conn->query($sql);
		$conn->close();
	break;
	
	case "edit":
		$mdate = date("Y-m-d",strtotime($msg_date));
		$sql = "UPDATE messages SET 
			name='".$name."',message='".$message."',date='".$mdate."' WHERE id='".$mid."'";
		$conn->query($sql);
		$conn->close();
		$data[0] = "saved";
	break;
}

echo json_encode($data);
?>
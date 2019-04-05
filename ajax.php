<?php include("db_conexion.php"); ?>
<?php
	if (isset($_POST['key'])) {
		if ($_POST['key'] == 'getRowData') {
			$rowId=$conn->real_escape_string($_POST['rowId']);
			$sql = $conn->query("SELECT countryName,shortDesc,longDesc FROM country WHERE id = '$rowId'");
			$data=$sql->fetch_array();
			$jsonArray=array(
				'countryName'=>$data['countryName'],
				'shortDesc'=>$data['shortDesc'],
				'longDesc'=>$data['longDesc'],
			);
			exit(json_encode($jsonArray));
		}
		if ($_POST['key'] == 'getExistingData') {
			$start = $conn->real_escape_string($_POST['start']);
			$limit = $conn->real_escape_string($_POST['limit']);

			$sql = $conn->query("SELECT id, countryName FROM country LIMIT $start, $limit");
			if ($sql->num_rows > 0) {
				$response = "";
				while($data = $sql->fetch_array()) {
					$response .= '
						<tr>
							<td>'.$data["id"].'</td>
							<td id="country_'.$data["id"].'">'.$data["countryName"].'</td>
							<td>
								<input type="button" onclick="viewORedit('.$data["id"].',\'edit\')" value="Edit" class="btn btn-warning">
								<input type="button" onclick="viewORedit('.$data["id"].',\'view\')" value="View" class="btn btn-primary">
								<input type="button" onclick="deleteRow('.$data["id"].')" value="Delete" class="btn btn-danger">
							</td>
						</tr>
					';
				}
				exit($response);
			} else
				exit('reachedMax');
		}
		$rowId=$conn->real_escape_string($_POST['rowId']);

		if ($_POST['key'] == 'deleteRow') {
			$sql = $conn->query("DELETE FROM country WHERE id = '$rowId'");
			exit('The Country Has Been Deleted!');
		}
		$name =strtoupper($conn->real_escape_string($_POST['name']));
		$shortDesc =strtoupper( $conn->real_escape_string($_POST['shortDesc']));
		$longDesc = strtoupper($conn->real_escape_string($_POST['longDesc']));

		if ($_POST['key'] == 'UpdateRow') {
			
			$sql = $conn->query("UPDATE country SET countryName='$name',shortDesc='$shortDesc',longDesc='$longDesc' WHERE id = '$rowId'");
			exit('success');
		}
		if ($_POST['key'] == 'addNew') {
			$sql = $conn->query("SELECT id FROM country WHERE countryName = '$name'");
			if ($sql->num_rows > 0)
				exit("Country With This Name Already Exists!");
			else {
				$conn->query("INSERT INTO country (countryName, shortDesc, longDesc) 
							VALUES ('$name', '$shortDesc', '$longDesc')");
				exit('Country Has Been Inserted!');
			}
		}
	}
?>
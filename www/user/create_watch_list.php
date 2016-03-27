<?php

	//require the framework
	require_once "../requires/initialize.php";
	
	$page = new Page();
	$page->name = "Add New Watch Lists";
	$page->is_user_only = true;
	
	//process the form
	if(isset($_POST['submit'])) {
	
		//first thing's first, make the watch list entry
		$new_watch_list = new Watch_List();
		$new_watch_list->name = $_POST['name'];
		$new_watch_list->user_wk = $user->user_wk;
		if(!$new_watch_list->save()) {
			$session->message("There was an issue, please try again.");
			redirect_head(current_url());
		}
		$new_watch_list->watch_list_wk = $database->insert_id();
		
		foreach($_POST as $key => $value) {
			//skip the submit field and name field
			if($key != 'submit' && $key != 'name') {
				if(!empty($value)) {
					//only process if the value is not empty
	
					//instantiate the new object
					$new_value = new Watch_List_Detail();
					//set it the watch list key
					$new_value->watch_list_wk = $new_watch_list->watch_list_wk;
					//set it the column name
					$new_value->column_name = $key;
					
					if(is_array($value)) {
						//the value we're processing is an array
						
						//loop through the array of values
						foreach($value AS $items) {
							//set the value
							$new_value->value = $items;
							//save it
							$new_value->save();
						}
					} else {
						//the value we're processing is not an array
						$new_value->value = $value;
						//save it
						$new_value->save();
					}
					
				}
			}
			
		}
		//at this point, everything is in the database
		//escape
		$session->message("Your new watch list was created successfully!");
		redirect_head(ROOT_URL."user/manage_watch_lists.php");
	}
		
	//header template
	require_once ("../requires/template/header.php");
		
?>

<p><em>Please enter your new list criteria.</em></p>
<form action="<?php echo file_name_without_get(); ?>" method="post">
		Watch List Name <input type="text" name="name" /><br /><br />
		Pet Type <br /><?php
				  		
				  		//we need to display all available items
				  		//do a concatenation of the pet type and the breed
				  		$sql = "SELECT DISTINCT `pt`.* FROM `breed` AS `b` ";
				  		$sql .= "INNER JOIN `pet_type` AS `pt` ON `pt`.`pet_type_wk` = `b`.`pet_type_wk` ";
				  		$sql .= "ORDER BY `pt`.`name` ASC;";

				  		$to_display = Pet_Type::find_by_sql($sql);
				  		
				  		//loop through all items
				  		foreach($to_display AS $value) {
				  			echo "<input style=\"margin-left: 1.5em;\" type=\"checkbox\" name=\"pet_type[]\" value=\"".$value->pet_type_wk."\">".$value->name."<br />";
				  		}
				  		
				  		echo '</select>';
				  		
				  ?>
		Breed <br /><?php
				  		
				  		//we need to display all available items
				  		//do a concatenation of the pet type and the breed
				  		$sql = "SELECT DISTINCT `b`.`breed_wk`, `b`.`pet_type_wk`, CONCAT(`pt`.`name`, ' - ', `b`.`name`) AS `name`, `b`.`create_dt` ";
				  		$sql .= "FROM `breed` AS `b` INNER JOIN `pet_type` AS `pt` ON `pt`.`pet_type_wk` = `b`.`pet_type_wk` ";
						$sql .= "ORDER BY `pt`.`name` ASC, `b`.`name` ASC;";

				  		$to_display = Breed::find_by_sql($sql);
				  		
				  		//loop through all items
				  		foreach($to_display AS $value) {
				  			echo "<input style=\"margin-left: 1.5em;\" type=\"checkbox\" name=\"breed[]\" value=\"".$value->breed_wk."\">".$value->name."<br />";
				  		}
				  		
				  		echo '</select>';
				  		
				  ?>
		Color <br /><?php
				  		
				  		//we need to display all available items
				  		//do a concatenation of the pet type and the breed
				  		$sql = "SELECT DISTINCT `c`.* FROM `color` AS `c` ";
				  		$sql .= "ORDER BY `c`.`name` ASC;";

				  		$to_display = Color::find_by_sql($sql);
				  		
				  		//loop through all items
				  		foreach($to_display AS $value) {
				  			echo "<input style=\"margin-left: 1.5em;\" type=\"checkbox\" name=\"color[]\" value=\"".$value->color_wk."\">".$value->name."<br />";
				  		}
				  		echo '</select>';
				  		
				  ?>
		Status <br /><?php
				  		
				  		//we need to display all available items
				  		//do a concatenation of the pet type and the breed
				  		$sql = "SELECT DISTINCT `s`.* FROM `status` AS `s` ";
				  		$sql .= "ORDER BY `s`.`name` ASC;";

				  		$to_display = Status::find_by_sql($sql);
				  		
				  		//loop through all items
				  		foreach($to_display AS $value) {
				  			echo "<input style=\"margin-left: 1.5em;\" type=\"checkbox\" name=\"status[]\" value=\"".$value->status_wk."\">".$value->name."<br />";
				  		}
				  		echo '</select>';
				  		
				  ?>
		Age <input type="text" name="age_min" maxlength="4" size="5" value="" > years to <input type="text" name="age_max" maxlength="4" size="5" value="" > years<br>
		Weight <input type="text" name="weight_min" maxlength="5" size="5" value="" > lbs to <input type="text" name="weight_max" maxlength="5" size="5" value="" > lbs<br>
		<input type="submit" name="submit" value="submit">
</form>
<?php

	//footer template
	require_once "../requires/template/footer.php";

?>
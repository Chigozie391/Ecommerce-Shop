<?php 
	function display_errors($errors){
	$display = '<ul class = "bg-danger red lighten-4">';
	foreach($errors as $error){
		$display .= '<li class = "text-danger">'.$error.'</li>';
	}
	$display .= '</ul>';
	return $display;
}

	function sanitize($dirt){
		return htmlentities($dirt,ENT_QUOTES,"UTF-8");
	}
	function money($price){
		return '$'.number_format($price,2);

	}

	//login function
	function login($userID){
		$_SESSION['SBUser'] = $userID;
		global $db;
		$date = date("Y-m-d H:i:s");
		$db->query("UPDATE users SET last_login = '$date' WHERE id = '$userID'");
		$_SESSION['success_flash'] = 'You are now logged in.';
		header('Location: index.php');
	}

   //checks if the user is logged in
	function is_logged_in(){
		if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
			return true;
		}
		return false;
	}
	//redirects to login page
	function login_error_redirect($url = 'login.php'){
		$_SESSION['error_flash'] = 'You must be logged in to access that page';
		header('Location:'.$url);
	}

	//redirect to if not s=admin
	function permission_error_redirect($url = 'login.php'){
		//set the session
		$_SESSION['error_flash'] = 'You don\'t have access to that page';
		header('Location:'.$url);
	}
	//checks if the user have admin right
	function has_permission($perm = 'admin'){
		global $userData;
		$permArray = explode(',', $userData['permission']);
		if(in_array($perm, $permArray, true)){
			return true;
		}
		return false;
	}

	function pretty_date($date){
		return date("M d, Y h:i A",strtotime($date));
	}
	//for getting the name of each category
	function get_category($child_id){
		global $db;
		$id = sanitize($child_id);
		$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid',c.category AS 'child' 
			FROM categories c 
			INNER JOIN categories p 
			ON c.parent = p.id WHERE c.id = $id";
		$query = $db->query($sql);
		$category =mysqli_fetch_assoc($query);
		return $category;
	}
	//for details
	function get_details($ids){
		global $db;
		$productQ = $db->query(
		"SELECT p.id as 'id', p.title as 'title', c.id as 'cid', c.category as child, i.category as parent
		FROM products p 
		LEFT JOIN categories c ON p.categories = c.id
		LEFT JOIN categories i ON c.parent = i.id 
		WHERE p.id IN ({$ids}) ");
		return $productQ;
	}

	function sizesToArray($string){
		$sizesArray = explode(',',$string);
		$returnArray = array();
		foreach($sizesArray as $size){
			$s = explode(':', $size);
			$returnArray[] = array('size' => $s[0], 'quantity' => $s[1], 'threshold' => $s[2]);
		}
		return $returnArray;
	}

	function sizesToString($sizes){
		$sizeString = '';
		foreach($sizes as $size){
			$sizeString .= $size['size'].':'.$size['quantity'].':'.$size['threshold'].',';
		}

		$trimmed = rtrim($sizeString,',');
		return $trimmed; 
	}

?>

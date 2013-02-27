<?php 

class Form{
	
	function textInput($type, $name, $id = null, $class = null){
		 echo '<input type="'.$type.'" name="'.$name.'" id="'.$class.'" />';	
	}
	
	function textArea($cols, $rows, $id = null, $class = null){
		$markUp .= '<textarea cols="'.$cols.'" rows="'.$row.'" id="'.$id.'" class="'.$class.'">';
		$markUp .= '</textarea>';	
		
		return $markUp;
	}
	
	function label($id, $value){
		$markUp .= '<label for="'.$id.'">';
		$markUp .= '"'.$value.'"';
		$markUp .= '</label>';
		
		return $markUp;
	}
	
	
	

}



?>
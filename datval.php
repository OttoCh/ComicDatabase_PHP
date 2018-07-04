<?php
	class Data_Sanitization {
		function data_test($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			$data = addslashes($data);	//tambah escape ke semua char yg diperlukan
			return $data;
		}

		function string_length($data, $max_length) {
			if((is_string($data) ? 'true' : 'false') == false) return false;

			if(strlen($data) < $max_length) {
				return true;
			}
			return false;
		}

		function number_range($data, $min_value, $max_value) {
			if((is_numeric($data) ? 'true' : 'false') == false) return false;

			if($data >= $min_value and $data <= $max_value) return true;
			else return false;
		}
	}

?>
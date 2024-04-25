<?php
class Functions
{
	public $now;
	public function createData($data, $crud = 'i')
	{

		switch ($crud) {
			case 'i':
				$res = $data.", '$this->now', '$_SESSION[id]', NULL, NULL, '0', NULL, NULL, '0', NULL, NULL";
				break;
			case 'u':
				$res = $data . ", updated_user = '$_SESSION[id]', updated_at = '$this->now'";
				break;

			default:
				break;
		}

		return $res;
	}
}

$Functions = new Functions();
$Functions->now = $now;
<?php

class Query
{
	public $db;
	protected $sql;

	/* Query example
	$Query->getN("table", [
		'fields' => '*', || ['from' | 'until' => 'title', 'u.name']
		'join' => [
			'table' => 'users', 'on' => 'id = 5',
			'table' => 'subjects', 'on' => 's.id = g.subject_id',
		],
		'where' => [
			['column' => 'id', 'operand' => '<>', 'value' => 'Michael'],
			['column' => 'id', 'value' => $id],
			['column' => 'active', 'value' => "'1'"],
		],
		'group' => 'title, surname',
		'order' => ['title', 'created_at DESC'],
		'idAsKey' => true,
		'fieldAsKey' => 'title',
		'getOnlyRow' => 'title',
		'fetch' => 'one',
		'ignore' => ['join' => " AND mp.deleted <> '1'"],
		'groupInArray' => ['type' => 'sum', 'fields' => ['id', 'title']], // type: sum, array;
	]);
	*/
	public function getN($table, $inputData = [])
	{

		$fields = '';

		if(array_key_exists('fields', $inputData)){

			if(is_array($inputData['fields'])){

				// foreach ($inputData['fields'] as $key => $v) {
				// 	if($key == 'from' || $key == 'until'){
				// 		// if($)
				// 		$fields .= $v.', ';
				// 	}else{
				// 		$fields .= $v.', ';
				// 	}
				// }
				// $fields = rtrim($fields, ', ');

			}else{

				$inputData['fields'] = str_replace(', ', ',', $inputData['fields']);

				foreach (explode(',', $inputData['fields']) as $key => $value) {
					$fields .= ''. $value.', ';
				}
				$fields = rtrim($fields, ', ');

			}

		}else{
			$fields = '*';
		}

		$additional = '';
		$join = '';

		if (array_key_exists('join', $inputData)) {
			foreach ($inputData['join'] as $value) {
				if(array_key_exists('type', $value)) {
					$join .= ' '.strtoupper($value['type']) . ' JOIN ' . PREFIX . $value['table'] . ' ON ' . $value['on'] . ' ';
				}else{
					$join .= ' LEFT JOIN ' . PREFIX . $value['table'] . ' ON ' . $value['on'] . ' ';
				}
			}
			$additional .= substr($join, 0, -1);

			if (array_key_exists('where', $inputData) && !empty($inputData['where'])) {
				$where = ' WHERE ';
				foreach ($inputData['where'] as $value) {
					if (array_key_exists('is_bracket', $value) && $value['is_bracket'] == true) {
						if (array_key_exists('operand', $value)) {
							if($value['operand'] == 'in' || $value['operand'] == 'IN'){
								$where .= '('.$value['column'] . ' IN (' . $value['value'] . ')) AND ';
							}else{
								$where .= '('.$value['column'] . ' ' . $value['operand'] . " " . $value['value'] . ") AND ";
							}
						} else {
							$where .= '('.$value['column'] . " = " . $value['value'] . ") AND ";
						}
					}else{
						if (array_key_exists('operand', $value)) {
							if($value['operand'] == 'in' || $value['operand'] == 'IN'){
								$where .= $value['column'] . ' IN (' . $value['value'] . ') AND ';
							}else{
								$where .= $value['column'] . ' ' . $value['operand'] . " " . $value['value'] . " AND ";
							}
						} else {
							$where .= $value['column'] . " = " . $value['value'] . " AND ";
						}
					}
				}

				if (count(explode(' ', $table)) > 1) {
					$additional .= substr($where, 0, -5) . "";
				} else {
					$additional .= substr($where, 0, -5) . "";
				}

				if (count($inputData['join']) > 0) {
					$joinWhere = '';
					foreach ($inputData['join'] as $v) {
						// $joinWhere .= explode(' ', $v['table'])[1] . ".deleted <> '1' AND ";
					}
					$additional .= ' AND '.substr($joinWhere, 0, -5);
				}
				if($inputData['ignore']['join']){
					if(is_array($inputData['ignore']['join'])){
						foreach ($inputData['ignore']['join'] as $v) {
							$additional = '';
						}
					}else{
						$additional = str_replace($inputData['ignore']['join'], '', $additional);
					}
				}

			} else {
				if(count(explode(' ', $table)) > 1){
					// $additional .= " WHERE ".explode(' ', $table)[1].".deleted <> '1'";
				}else{
					// $additional .= " WHERE deleted <> '1'";
				}

				if (count($inputData['join']) > 0) {
					$joinWhere = '';
					foreach ($inputData['join'] as $v) {
						// $joinWhere .= explode(' ', $v['table'])[1] . ".deleted <> '1' AND ";
					}
					$additional .= ' AND '.substr($joinWhere, 0, -5);
				}
				if($inputData['ignore']['join']){
					$additional = str_replace($inputData['ignore']['join'], '', $additional);
				}
			}
		}else{
			if (array_key_exists('where', $inputData) && !empty($inputData['where'])) {
				$where = ' WHERE ';
				foreach ($inputData['where'] as $value) {
					if (array_key_exists('operand', $value)) {
						if($value['operand'] == 'in' || $value['operand'] == 'IN'){
							$where .= $value['column'] . ' IN (' . $value['value'] . ') AND ';
						}else{
							$where .= $value['column'] . ' ' . $value['operand'] . " " . $value['value'] . " AND ";
						}
					} else {
						$where .= $value['column'] . " = " . $value['value'] . " AND ";
					}
				}

				if (count(explode(' ', $table)) > 1) {
					$additional .= substr($where, 0, -5) . "";
				} else {
					$additional .= substr($where, 0, -5) . "";
				}

			}else{
				if (count(explode(' ', $table)) > 1) {
					// $additional .= " WHERE " . explode(' ', $table)[1] . ".deleted <> '1'";
				} else {
					// $additional .= " WHERE deleted <> '1'";
				}
			}
		}
		if (array_key_exists('group', $inputData) && !empty($inputData['group'])) {
			$additional .= ' GROUP BY ' . $inputData['group'];
		}

		if (array_key_exists('order', $inputData) && !empty($inputData['order'])) {
			$order = ' ORDER BY ';
			foreach ($inputData['order'] as $v) {
				if (strpos($v, 'desc') || strpos($v, 'DESC')) {
					$order .= str_replace([' desc', ' DESC'], '', $v) . ' DESC, ';
				}elseif(strpos($v, 'asc')){
					$order .= str_replace(' asc', '', $v) . ' ASC, ';
				}else{
					$order .= explode(' ', $v)[0] . ' ASC, ';
				}
			}
			$additional .= rtrim($order, ', ');
		}

		if (array_key_exists('limit', $inputData) && !empty($inputData['limit'])) {
			$additional .= ' LIMIT '.$inputData['limit'];
		}

		$additional = ($inputData['ignore']['where'] && !empty($inputData['ignore']['where'])) ? str_replace($inputData['ignore']['where'], '', $additional) : $additional;

		$sql = "SELECT $fields FROM ".PREFIX.$table.$additional;

		unset($fields, $additional, $where, $join);

		$res = [];
		$res['sql'] = $sql;

		$query = mysqli_query($this->db, rtrim($sql));

		if ($query) {

			if(array_key_exists('idAsKey', $inputData)){
				while ($row = $query->fetch_assoc()) {
					$res['res'][$row['id']] = $row;
				}
			}elseif(array_key_exists('fieldAsKey', $inputData) && $inputData['fieldAsKey'] != ''){
				while ($row = $query->fetch_assoc()) {
					if(is_array($inputData['fieldAsKey'])){
						$field = '';
						foreach ($inputData['fieldAsKey'] as $fields_key) {
							$field .= $row[$fields_key].'-';
						}
						$field = rtrim($field, '-');
						$res['res'][$field] = $row;
					}else{
						$res['res'][$row[$inputData['fieldAsKey']]] = $row;
					}
				}
			}elseif(array_key_exists('getOnlyRow', $inputData)){
				while ($row = $query->fetch_assoc()) {
					$res['res'][] = $row[$inputData['getOnlyRow']];
				}
			}elseif(array_key_exists('array_column', $inputData)){
				while ($row = $query->fetch_assoc()) {
					$res['res'][] = $row;
				}
				if($query->num_rows > 0){
					$res['res'] = array_column($res['res'], $inputData['array_column']);
				}
			}elseif(array_key_exists('groupInArray', $inputData)){
				while ($row = $query->fetch_assoc()) {
					if($inputData['groupInArray']['type'] === 'sum'){
						$res['res'][$row[$inputData['groupInArray']['fields'][0]]] += $row[$inputData['groupInArray']['fields'][1]];
					}else{
						$res['res'][$row[$inputData['groupInArray']['fields'][0]]][] = $row[$inputData['groupInArray']['fields'][1]];
					}
				}
			}else{
				while ($row = $query->fetch_assoc()) {
					$res['res'][] = $row;
				}
			}

			if($res['res'] == null){
				// $res['info'] = 'SQL responsed is NULL';
				// $res['status'] = 'empty';
				$res = [];
				return $res;
				exit;
			}

			if(array_key_exists('fetch', $inputData) && $inputData['fetch'] == 'one'){
				return $res['res'][0];
			}else{
				return $res['res'];
			}
			// if (count($res['res']) > 0) {
			// 	if (count($res['res']) > 1) {
			// 	} else {
			// 		return $res['res'][0];
			// 	}
			// }
		}else {
			if(array_key_exists('order',$inputData) && !is_array($inputData['order'])){
				$res['error'] = 'ORDER values must be array!';
			}else{
				$res['error'] = mysqli_error($this->db);
				$res['errno'] = mysqli_errno($this->db);
			}

			echo var_dump($res);
			exit;
		}
		mysqli_close($this->db);
	}

	public function get($table, $column = '', $operand = '', $value = '')
	{
		if($column != ''){
			$sql = "SELECT * FROM $table WHERE $column $operand $value";
		}else{
			$sql = "SELECT * FROM $table";
		}

		$query = mysqli_query($this->db, $sql);

		$res = [];

		while($row = $query->fetch_assoc()){
			$res[] = $row;
		}
		mysqli_close($this->db);

		return $res;
	}
	public function getById($table, $value, $column = 'id', $operand = '=', $fields = [])
	{
		if (is_array($value)) {
			if(!empty($fields)){
				$sql = "SELECT "; $sqlFields = ''; foreach($fields as $row) { $sqlFields .= $row.','; } $sql .= rtrim($sqlFields, ',') . " FROM ".PREFIX."$table WHERE $column IN ($value[0])";
			}else{
				$sql = "SELECT * FROM ".PREFIX."$table WHERE $column IN ($value[0])";
			}
		} else {
			if(!empty($fields)){
				$sql = "SELECT "; $sqlFields = ''; foreach($fields as $row) { $sqlFields .= $row.','; } $sql .= rtrim($sqlFields, ',') . " FROM ".PREFIX."$table WHERE $column $operand '$value'";
			}else{
				$sql = "SELECT * FROM ".PREFIX."$table WHERE $column $operand '$value'";
			}
		}

		$query = mysqli_query($this->db, $sql);

		if(!$query){
			echo 'SQL: '.$sql;
			echo '<br>Error: '.mysqli_error($this->db);
			exit;
		}

		while ($row = $query->fetch_assoc()) {
			$res[] = $row;
		}

		if ($res !== null) {
			if (count($res) > 1) {
				return $res;
			} else {
				return $res[0];
			}
		}
		// mysqli_close($this->db);
	}
	public function getOrder($table, $order, $sorting = 'ASC')
	{
		$sql = "SELECT * FROM $table ORDER BY $order $sorting";

		$query = mysqli_query($this->db, $sql);

		$res = [];

		while($row = $query->fetch_assoc()){
			$res[] = $row;
		}

		return $res;
	}
	public function count($table, $value = '', $column = '', $operand = '')
	{

		if($column != ''){
			$sql = "SELECT COUNT(id) FROM " . PREFIX . "$table WHERE $column $operand $value";
		}else{
			if ($value != '') {
				$sql = "SELECT COUNT(id) FROM " . PREFIX . "$table WHERE id = '$value'";
			}else{
				$sql = "SELECT COUNT(id) FROM " . PREFIX . "$table";
			}
		}

		$query = mysqli_query($this->db, $sql);

		return intval($query->fetch_array()[0]);

	}
	public function getAllIdAsKey($table, $column = '', $operand = '', $value = '')
	{
		if($column != ''){
			$sql = "SELECT * FROM " . PREFIX . "$table WHERE $column $operand $value";
		}else{
			$sql = "SELECT * FROM " . PREFIX . "$table";
		}

		$query = mysqli_query($this->db, $sql);

		$res = [];

		while($row = $query->fetch_assoc()){
			$res[$row['id']] = $row;
		}

		return $res;
	}
	public function bySql($sql, $options = [])
	{
		$query = mysqli_query($this->db, $sql);

		if(!$query){

			return mysqli_error($this->db);

		}else{

			$res = [];

			if($options['idAsKey'] === true){
				while ($row = $query->fetch_assoc()) {
					$res[$row['id']] = $row;
				}
			}else{
				while($row = $query->fetch_assoc()){
					$res[] = $row;
				}
			}

			return $res;
		}
	}

	public function createFields($table, $arr = [])
	{
		$sql = "SELECT * FROM ".PREFIX."$table";
		$query = mysqli_query($this->db, $sql);

		$fields = '';

		if($query) {
			while ($row = $query->fetch_field()) {
				if($row->name === 'id') continue;
				if(in_array($row->name, $arr)) continue;
				$fields .= '`'.$row->name.'`, ';
			}
		}
		return '('.rtrim($fields, ', ').')';
	}

	public function update($table, $set, $value)
	{
		$sql = "UPDATE " . PREFIX . "$table SET $set WHERE $value";

		if(mysqli_query($this->db, $sql)) {
			return true;
		}else{
			$res['sql'] = $sql;
			$res['error'] = mysqli_error($this->db);
			$res['errno'] = mysqli_errno($this->db);
			return $res;
		}
	}

	public function insert($table, $values)
	{
		$sql = 'INSERT INTO '.PREFIX.$table .' '.$this->createFields($table, ['user_group_id'])." VALUES $values";

		if(mysqli_query($this->db, $sql)) {
			return true;
		}else{
			if(DEVELOPING_MODE === true){
				$res['sql'] = $sql;
				$res['errno'] = mysqli_errno($this->db);
				$res['error'] = mysqli_errno($this->db).': '.mysqli_error($this->db).'<br>SQL: '.$sql;
			}else{
				$res['error'] = "Error insert to $table";
			}
			return $res;
		}
	}

	public function delete($table, $id, $operand = '=', $column = 'id')
	{
		$sql = 'DELETE FROM '.PREFIX.$table ." WHERE $column $operand $id";

		if(mysqli_query($this->db, $sql)) {
			return true;
		}else{
			$res['sql'] = $sql;
			$res['error'] = mysqli_error($this->db);
			$res['errno'] = mysqli_errno($this->db);
			return $res;
		}
	}
}

$Query = new Query;
$Query->db = $db;
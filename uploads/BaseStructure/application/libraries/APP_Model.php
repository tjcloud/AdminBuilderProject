<?php

/**
 * A base model to provide the basic CRUD 
 * actions for all models that inherit from it.
 *
 * @package CodeIgniter
 * @subpackage MY_Model
 * @license GPLv3 <http://www.gnu.org/licenses/gpl-3.0.txt>
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @version 1.1.1
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @modified Phil Sturgeon <http://philsturgeon.co.uk>
 * @copyright Copyright (c) 2009, Jamie Rumbelow <http://jamierumbelow.net>
 */

class APP_Model extends Model
{
	/**
	 * The database table to use, only
	 * set if you want to bypass the magic
	 *
	 * @var string
	 */
	protected $table;
		
	/**
	 * The primary key, by default set to
	 * `id`, for use in some functions.
	 *
	 * @var string
	 */
	protected $primary_key = 'id';
	
	/**
	 * An array of functions to be called before
	 * a record is created.
	 *
	 * @var array
	 */
	protected $before_create = array();
	
	/**
	 * An array of functions to be called after
	 * a record is created.
	 *
	 * @var array
	 */
	protected $after_create = array();

	/**
	 * The class constructer, tries to guess
	 * the table name.
	 *
	 * @author Jamie Rumbelow
	 */
	public function __construct() {
		parent::Model();
		$this->load->helper('inflector');
		$this->_fetch_table();
	}
	
	/**
	 * Get a single record by creating a WHERE clause with
	 * a value for your primary key
	 *
	 * @param string $primary_value The value of your primary key
	 * @return object
	 * @since 1.1.0
	 * @author Phil Sturgeon
	 */
	public function get($primary_value) {
		return $this->db->where($this->primary_key, $primary_value)
			->get($this->table)
			->row();
	}
	
	/**
	 * Get a single record by creating a WHERE clause with
	 * the key of $key and the value of $val.
	 *
	 * @param string $key The key to search by 
	 * @param string $val The value of that key
	 * @return object
	 * @author Jamie Rumbelow and Phil Sturgeon
	 */
	public function get_by() {
		$where =& func_get_args();
		$this->_set_where($where);
		
		return $this->db->get($this->table)
			->row();
	}
	
	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 * @author Jamie Rumbelow and Phil Sturgeon
	 */
	public function get_many_by() {
		$where =& func_get_args();
		$this->_set_where($where);
		
		return $this->get_all();
	}
	
	/**
	 * Get all records in the database
	 *
	 * @return array
	 * @author Jamie Rumbelow
	 */
	public function get_all() {
		return $this->db->get($this->table)
			->result();
	}
	
	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 * @since 1.1.0
	 * @author Phil Sturgeon
	 */
	public function count_by() {
		$where =& func_get_args();
		$this->_set_where($where);
		
		return $this->db->count_all_results($this->table);
	}
	
	/**
	 * Get all records in the database
	 *
	 * @return array
	 * @since 1.1.0
	 * @author Phil Sturgeon
	 */
	public function count_all() {
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Insert a new record into the database,
	 * calling the before and after create callbacks.
	 * Returns the insert ID.
	 *
	 * @param array $data Information
	 * @return integer
	 * @author Jamie Rumbelow
	 */
	public function insert($data) {
		$data = $this->_run_before_create($data);
			$this->db->insert($this->table, $data);
		$this->_run_after_create($data, $this->db->insert_id());
		
		return $this->db->insert_id();
	}
	
	/**
	 * Similar to insert(), just passing an array to insert
	 * multiple rows at once. Returns an array of insert IDs.
	 *
	 * @param array $data Array of arrays to insert
	 * @return array
	 * @author Jamie Rumbelow
	 */
	public function insert_many($data) {
		$ids = array();
		
		foreach ($data as $row) {
			$data = $this->_run_before_create($row);
				$this->db->insert($this->table, $row);
			$this->_run_after_create($row, $this->db->insert_id());
	
			$ids[] = $this->db->insert_id();
		}
		
		return $ids;
	}
	
	/**
	 * Update a record, specified by an ID.
	 *
	 * @param integer $id The row's ID
	 * @param array $array The data to update
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function update($primary_value, $array) {
		return $this->db->where($this->primary_key, $primary_value)
			->set($array)
			->update($this->table);
	}
	
	/**
	 * Update a record, specified by $key and $val.
	 *
	 * @param string $key The key to update with
	 * @param string $val The value
	 * @param array $array The data to update
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function update_by($key, $val, $array) {
		return $this->db->where($key, $val)
			->set($array)
			->update($this->table);
	}
	
	/**
	 * Updates many records, specified by an array
	 * of IDs.
	 *
	 * @param array $ids The array of IDs
	 * @param array $array The data to update
	 * @return bool
	 * @author Jamie Rumbelow and Phil Sturgeon
	 */
	public function update_many($primary_values, $array) {
		return $this->db->where_in($this->primary_key, $primary_values)
			->set($array)
			->update($this->table);
	}
	
	/**
	 * Updates many records, specified by an array
	 * of keys and values.
	 *
	 * @param array $array The array of key values
	 * @param array $data The data to update
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function update_many_by($where, $data) {
		return $this->db->where($where)
			->set($data)
			->update($this->table);
	}
	
	/**
	 * Delete a row from the database table by the
	 * ID.
	 *
	 * @param integer $id 
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function delete($id) {
		return $this->db->where($this->primary_key, $id)
			->delete($this->table);
	}
	
	/**
	 * Delete a row from the database table by the
	 * key and value.
	 *
	 * @param string $key
	 * @param string $value 
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function delete_by($key, $val) {
		return $this->db->where($key, $val)
			->delete($this->table);
	}
	
	/**
	 * Delete many rows from the database table by 
	 * an array of IDs passed.
	 *
	 * @param array $primary_values 
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function delete_many($primary_values) {
		return $this->db->where_in($this->primary_key, $primary_values)
			->delete($this->table);
	}
	
	/**
	 * Delete many rows from the database table by 
	 * an array of keys and values.
	 *
	 * @param array $array
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function delete_many_by($where) {
		return $this->db->where($where)
			->delete($this->table);
	}
	
	/**
	 * Limits the result set by the integer passed.
	 * Pass a second parameter to offset.
	 *
	 * @param integer $limit The number of rows
	 * @param integer $offset The offset
	 * @return void
	 * @since 1.1.1
	 * @author Jamie Rumbelow
	 */
	public function limit($limit, $offset = 0) {
		$this->db->limit($limit, $offset);
	}
	
	/**
	 * Orders the result set by the criteria,
	 * using the same format as CI's AR library.
	 *
	 * @param string $criteria The criteria to order by
	 * @return void
	 * @since 1.1.2
	 * @author Jamie Rumbelow
	 */
	public function order_by($criteria) {
		$this->db->order_by($criteria);
	}
	
	/**
	 * Runs the before create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _run_before_create($data) {
		foreach ($this->before_create as $method) {
			$data = call_user_func_array(array($this, $method), array($data));
		}
		
		return $data;
	}
	
	/**
	 * Runs the after create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _run_after_create($data, $id) {
		foreach ($this->after_create as $method) {
			call_user_func_array(array($this, $method), array($data, $id));
		}
	}
	
	/**
	 * Fetches the table from the pluralised model name.
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _fetch_table() {
		if ($this->table == NULL) {
			$class = preg_replace('/(_m|_model)?$/', '', get_class($this));
			
			$this->table = plural(strtolower($class));
		}
	}
	
	
	/**
	 * Sets where depending on the number of parameters
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _set_where($params) {
		if (count($params) == 1) {
			$this->db->where($params[0]);
		} else {
			$this->db->where($params[0], $params[1]);
		}
	}
}

/* End of file MY_Model.php */ 
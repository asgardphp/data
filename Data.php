<?php
namespace Asgard\Data;

/**
 * The data library.
 * 
 * @author Michel Hognerud <michel@hognerud.net>
*/
class Data implements \ArrayAccess {
	protected $types=[];
	protected $db;
	
	/**
	 * Constructor.
	 * 
	 * @param \Asgard\Db\DB db Database connection.
	*/
	public function __construct(\Asgard\Db\DB $db) {
		$this->db = $db;
	}
	
	/**
	 * Returns a value.
	 * 
	 * @param string key
	 * 
	 * @return mixed
	 * 
	 * @api 
	*/
	public function get($key) {
		$dal = new \Asgard\Db\Dal($this->db);
		$row = $dal->from('data')->where('key', $key)->first();
		if(isset($row['value'])) {
			$res = unserialize($row['value']);
			if(isset($res['_dataType']) && isset($res['input'])) {
				return $this->types[$res['_dataType']][1]($res['input']);
			}
			else
				return $res;
		}
	}
	
	/**
	 * Sets a value.
	 * 
	 * @param string key
	 * @param mixed value
	 * @param string type The type of data.
	 * 
	 * @api 
	*/
	public function set($key, $value, $type=null) {
		if($type === null)
			$res = serialize($value);
		else {
			$res = serialize(array(
				'_dataType' => $type,
				'input' => $this->types[$type][0]($value)
			));
		}
		$dal = new \Asgard\Db\Dal($this->db);
		if(!$dal->from('data')->where('key', $key)->update(array('value'=>$res)))
			$dal->into('data')->insert(array('key'=>$key, 'value'=>$res));
	}
	
	/**
	 * Register a type handler.
	 * 
	 * @param string type
	 * @param callback serializeCb Serializer.
	 * @param callback unserializeCb Unserializer.
	 * 
	 * @api 
	*/
	public function register($type, $serializeCb, $unserializeCb) {
		$this->types[$type] = array($serializeCb, $unserializeCb);
	}

	public function has($key) {
		$dal = new \Asgard\Db\Dal($this->db);
		$row = $dal->from('data')->where('key', $key)->first();
		return isset($row['value']);
	}

	public function delete($key) {
		$dal = new \Asgard\Db\Dal($this->db);
		$dal->from('data')->where('key', $key)->delete();
	}

    public function offsetSet($offset, $value) {
        if(is_null($offset))
            throw new \LogicException('Offset must not be null.');
        else
       		$this->set($offset, $value);
    }

    public function offsetExists($offset) {
        return $this->has($offset);
    }

    public function offsetUnset($offset) {
        return $this->delete($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }
}
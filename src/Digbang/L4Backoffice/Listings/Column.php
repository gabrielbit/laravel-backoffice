<?php namespace Digbang\L4Backoffice\Listings;

class Column
{
	protected $id;
	protected $label;
	protected $hidden = false;
	protected $sortable = true;

	function __construct($id, $label = '', $hidden = false, $sortable = true)
	{
		$this->id       = $id;
		$this->label    = $label;
		$this->hidden   = $hidden;
		$this->sortable = $sortable;
	}

	/**
	 * @param boolean $hidden
	 */
	public function setHidden($hidden)
	{
		$this->hidden = $hidden;
	}

	/**
	 * @return boolean
	 */
	public function isHidden()
	{
		return $this->hidden;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    public function sortable()
    {
        return $this->sortable;
    }
}

<?php

class TestPhase extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_phases';
	
	public $timestamps = false;

	/**
	 * TestStatus relationship
	 */
    public function testStatuses()
    {
        return $this->hasMany('TestStatus');
    }

}
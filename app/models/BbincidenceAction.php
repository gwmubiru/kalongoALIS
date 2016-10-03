<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BbincidenceAction extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'unhls_bbactions';

	public function bbincidence()
	{
		return $this->belongsToMany('Bbincidence', 'unhls_bbincidences_action', 'action_id', 'bbincidence_id');
	}
	

}
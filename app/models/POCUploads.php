<?php

class POCUploads extends Eloquent 
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_conent_poc_uploads';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('section_name', 'section_shortname','section_url','sub_section','file_url');

}

<?php

class POCSections extends Eloquent { 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_content_poc_sections';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('name_of_section','sub_section', 'shortname','section_url');

}

<?php

class POCPages extends Eloquent 
{ 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_content_poc_pages';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('page_description','page_name','page_shortname', 'type_of_page','page_title','page_subtitle','page_section','page_link_value','color_code','page_url');

}

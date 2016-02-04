<?php
use Venturecraft\Revisionable\Revisionable;

class POCPages extends Revisionable 
{ 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_content_poc_pages';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->page_name;
    }
 

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('page_description','page_name','page_shortname', 'type_of_page','page_title','page_subtitle','page_section','page_link_value','color_code','page_url');

}

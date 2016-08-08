<?php
use Venturecraft\Revisionable\Revisionable;


class POCSections extends Revisionable { 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_content_poc_sections';


    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->name_of_section;
    }


	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('name_of_section','sub_section', 'shortname','section_url','section_desc');

}

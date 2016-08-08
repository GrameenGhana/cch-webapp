<?php
use Venturecraft\Revisionable\Revisionable;

class References extends Revisionable 
{ 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cch_content_references';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->reference_desc;
    }
 

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('reference_desc','shortname','reference_url','size');

}

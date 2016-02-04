<?php

use Venturecraft\Revisionable\Revisionable;

class UserStatusChange extends Revisionable { 

      protected $table = 'cch_user_status_change';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->id;
    }

}

?>

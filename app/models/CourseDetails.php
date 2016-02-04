<?php

class CourseDetails extends Eloquent {

    protected $table = 'oppia_course';

    public static function details() 
    {
		$courses = DB::select(DB::raw("select oppia_course.title,oppia_course.version,oppia_course.shortname,
                                              oppia_coursetag.tag_id,oppia_tag.name 
									     from oppia_course,oppia_coursetag,oppia_tag 
									    where oppia_course.id=oppia_coursetag.course_id 
									      and oppia_coursetag.tag_id=oppia_tag.id"));
        return $courses;
	}  
}

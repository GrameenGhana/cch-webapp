<?php
/*
 * @author dhutchful
 */
class LogController  extends BaseController 
{
    public function index() 
    {
        $logs = array();
        $resources = array(
                    'Device','District','DistrictPopulation','Facility','FacilityTarget','FacilityType',
                    'Indicator','IndicatorTracker','FacilityUser','POCPages','POCSections','POCUploads',
                    'SubDistrict','SubDistrictPopulation','User','Zone','ZonePopulation');
    
        foreach($resources as $r)
        {
            $obj = new $r; 
            $x = $obj::all();
            $logs[$r] = $this->getHistory($x, $r);
        }

        return View::make('logs.index', array('logs'=> $logs));
    }

    private function getHistory($models, $r)
    {
        $arr = array();

        foreach($models as $m) 
        {
            if (sizeof($m->revisionHistory)) {
                array_push($arr, $this->getLogText($m, $r));
            }
        }

        return $arr;
    }

    private function getLogText($model, $resource)
    {
        $text = '';

        foreach($model->revisionHistory as $history)
        {
            $obj = new $history->revisionable_type;
            $x = $obj::find($history->revisionable_id);

            $name = $history->userResponsible()->first_name .' '.$history->userResponsible()->last_name;
            $on = date('M d, Y h:i:s', strtotime($history->created_at));
            $nv = ($history->newValue()==null) ? 'nothing' : "<b style='color:green'>".$history->newValue().'</b>';
            $ov = ($history->oldValue()==null) ? 'nothing' : "<b style='color:red'>".$history->oldValue().'</b>';

            $text .= '<tr>';
            $text .= '<td>'.ucfirst($resource).'</td>';
            $text .= "<td>$name</td>";

            $text .= '<td>';
            if($history->key == 'created_at' && !$history->old_value)
            {
               $text .= "$name created this $resource at $nv";
            } 
            else
            {
                $text .= $name.' changed <b>'.$history->fieldName()."</b> from \"$ov\" to \"$nv\" for ".$x->identifiableName();
            }
            $text .= '</td>';

            $text .= '<td>'.$on.'</td>';

            $text .= '</tr>';
        }

        return $text;
     }
}


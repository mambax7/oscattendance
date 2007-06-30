<?php
// $Id: attendanceevent.php, 2007/4/16
// *  http://osc.sourceforge.net
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

class  Attendanceevent extends XoopsObject {
    var $db;
    var $table;

    function Attendanceevent()
    {
        $this->db = &Database::getInstance();
//        $this->table = $this->db->prefix("oscatt_attendance");
	$this->initVar('att_id',XOBJ_DTYPE_INT);
	$this->initVar('event_id',XOBJ_DTYPE_INT);
	$this->initVar('att_Date', XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateentered', XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited', XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby', XOBJ_DTYPE_INT);
	$this->initVar('editedby', XOBJ_DTYPE_INT);
	$this->initVar('event_Name', XOBJ_DTYPE_TXTBOX);
	$this->initVar('attendancecount',XOBJ_DTYPE_INT);
	$this->initVar('attmonth',XOBJ_DTYPE_TXTBOX);
	$this->initVar('attyear',XOBJ_DTYPE_TXTBOX);

	$this->initVar('oddrow', XOBJ_DTYPE_INT);
	$this->initVar('loopcount',XOBJ_DTYPE_INT);
    	$this->initVar('totalloopcount', XOBJ_DTYPE_INT);
	
     }

}    
    

class oscAttendanceAttendanceeventHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $attendanceevent = new Attendanceevent ();
        if ($isNew) {
            $attendanceevent->setNew();
        }
        return $attendanceevent;
    }

    function &get($id)
    {
        $attendanceevent =&$this->create(false);
        if ($id > 0) 
	{
		$sql = "SELECT * FROM " . $attendance->table . " WHERE att_id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$attendance->assignVars($row);
		}

		
        }
        return $attendance;
    }
        
    function &search($searcharray, $sort)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
        if (isset($searcharray)) 
	{
	        $attendanceevent= &$this->create(false);
		
/*		$sql="
create temporary table `att_count` (att_id mediumint(8), att_count mediumint(8)); 
insert into att_count
select att_id, count(1) from xoops_oscatt_attendance_person group by att_id;
select a.*,e.event_Name,c.att_count `count`  from xoops_oscatt_attendance a join xoops_oscatt_events e on a.event_id = e.event_id
join att_count c on a.att_id = c.att_id";
*/
		$sql="select a.*,e.event_Name,0 `count`  from xoops_oscatt_attendance a join xoops_oscatt_events e on a.event_id = e.event_id";


		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= " where e.event_Name LIKE '%$searcharray[0]%'";
		
		}
		
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= " order by e.event_Name ";
			break;

			case "date":
			$sql .= " order by a.att_Date ";
			break;
						
			default:
			$sql .= " order by e.event_Name ";
			break;
			}
		}
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
		$oddrow=false;

		$i=0;
		$attendanceevent=new Attendanceevent();
		$returnevents[0]=$attendanceevent;
		while($row = $this->db->fetchArray($result)) 
		{
			$attendanceevent=&$this->create(false);
			$attendanceevent->assignVars($row);
			$attendanceevent->assignVar('oddrow',$oddrow);
			$attendanceevent->assignVar('loopcount',$i);
			
			$returnevents[$i]=$attendanceevent;
			
			if($oddrow){$oddrow=false;}
			else {$oddrow=true;}

			$i++;
		}
		$returnevents[0]->assignVar('totalloopcount',$i);
		
	}
	return $returnevents;
    }

    function &getAttcount($eventid, $lastxdays)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
	$attendanceevent= &$this->create(false);

	$sql="select a.event_id, e.event_Name, a.att_Date, count(1) attendancecount from " . $this->db->prefix("oscatt_attendance") . " a join " . $this->db->prefix("oscatt_events") . " e on a.event_id = e.event_id join " . $this->db->prefix("oscatt_attendance_person") . " ap on a.att_id=ap.att_id where a.att_Date >=(curdate()- " . $lastxdays . ") and a.event_id= " . $eventid . " group by a.event_id, e.event_name, a.att_Date order by a.att_Date desc";

	
	if (!$result = $this->db->query($sql)) 
	{
		return false;
	}
	$oddrow=false;

	$i=0;
	$attendanceevent=new Attendanceevent();
	$returnevents[0]=$attendanceevent;
	while($row = $this->db->fetchArray($result)) 
	{
		$attendanceevent=&$this->create(false);
		$attendanceevent->assignVars($row);
		$attendanceevent->assignVar('oddrow',$oddrow);
		$attendanceevent->assignVar('loopcount',$i);
		
		$returnevents[$i]=$attendanceevent;
		
		if($oddrow){$oddrow=false;}
		else {$oddrow=true;}

		$i++;
	}
	$returnevents[0]->assignVar('totalloopcount',$i);
	
	return $returnevents;
    }


    function &getAttcountYTD($eventid)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
	$attendanceevent= &$this->create(false);

	$sql="select a.event_id, e.event_Name, month(a.att_Date) attmonth, year(a.att_Date) attyear, count(1) attendancecount from  " . $this->db->prefix("oscatt_attendance") . " a join " . $this->db->prefix("oscatt_events") . " e on a.event_id = e.event_id join " . $this->db->prefix("oscatt_attendance_person") . " ap on a.att_id=ap.att_id where a.event_id= " . $eventid . " group by a.event_id, e.event_name, month(a.att_Date), year(a.att_Date)";
	
	
	if (!$result = $this->db->query($sql)) 
	{
		return false;
	}
	$oddrow=false;

	$i=0;
	$attendanceevent=new Attendanceevent();
	$returnevents[0]=$attendanceevent;
	while($row = $this->db->fetchArray($result)) 
	{
		$attendanceevent=&$this->create(false);
		$attendanceevent->assignVars($row);
		$attendanceevent->assignVar('oddrow',$oddrow);
		$attendanceevent->assignVar('loopcount',$i);
		
		$returnevents[$i]=$attendanceevent;
		
		if($oddrow){$oddrow=false;}
		else {$oddrow=true;}

		$i++;
	}
	$returnevents[0]->assignVar('totalloopcount',$i);
	
	return $returnevents;
    }

    function &getAttNonAttenders($datefrom)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
	$attendanceevent= &$this->create(false);

	$sql="create temporary table tmp_oscatt_attendance select a.att_id, ap.att_personid  from " . $this->db->prefix("oscatt_attendance") . "  a join  " . $this->db->prefix("oscatt_attendance_person") . " ap on a.att_id = ap.att_id where a.att_Date >=" . $this->quote($datefrom) . ";";
	$sql.="select p.* from " . $this->db->prefix("oscmembership_person") . " p left join tmp_oscatt_attendance a on p.id = a.att_personid where a.att_id is null;";

	
	if (!$result = $this->db->query($sql)) 
	{
		return false;
	}
	$oddrow=false;

	$i=0;
	$attendanceevent=new Attendanceevent();
	$returnevents[0]=$attendanceevent;
	while($row = $this->db->fetchArray($result)) 
	{
		$attendanceevent=&$this->create(false);
		$attendanceevent->assignVars($row);
		$attendanceevent->assignVar('oddrow',$oddrow);
		$attendanceevent->assignVar('loopcount',$i);
		
		$returnevents[$i]=$attendanceevent;
		
		if($oddrow){$oddrow=false;}
		else {$oddrow=true;}

		$i++;
	}
	$returnevents[0]->assignVar('totalloopcount',$i);
	
	return $returnevents;
    }
                
	function &update(&$event)
    	{
		$sql = "UPDATE " . $event->table
		. " SET "		
		. "event_Name=" . $this->db->quoteString($event->getVar('event_Name'));
		$sql .= ",datelastedited=" .  			
		$this->db->quoteString($event->getVar('datelastedited'))
		. ",editedby=" . $this->db->quoteString($event->getVar('editedby')) . 
		 
		" where event_id=" . $event->getVar('event_id');
			
		if (!$result = $this->db->query($sql)) {
			return false;
			}
	
	}
	
	function &insert(&$attendance, &$uid)
	{
		$sql = "INSERT into " . $attendance->table
		. "(event_id, att_Date, dateentered, enteredby) ";
	
		$sql = $sql . "values(" . $this->db->quoteString($attendance->getVar('event_id'))
		. "," . 
		$this->db->quoteString($attendance->getVar('att_Date'))
		. "," .
		$this->db->quoteString($attendance->getVar('dateentered'))
		. "," .
		$this->db->quoteString($attendance->getVar('enteredby')) . ")";
		
		$returnid=0;
		if (!$result = $this->db->query($sql)) {
			return false;
			}
			else
			{
			$returnid = $this->db->getInsertId();
			
			//Insert cart contents for this attendance
			$sql="INSERT INTO " . $this->db->prefix("oscatt_attendance_person") . " select " . $returnid . ",person_id from " .  $this->db->prefix("oscmembership_cart") . " where xoops_uid=" . $uid;
			
			$result=$this->db->query($sql);

			}
	
	}

}

?>
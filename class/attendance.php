<?php
// $Id: attendance.php, 2007/4/16
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

class  Attendance extends XoopsObject {
    var $db;
    var $table;

    function Attendance()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscatt_attendance");
	$this->initVar('att_id',XOBJ_DTYPE_INT);
	$this->initVar('att_personid',XOBJ_DTYPE_INT);
	$this->initVar('event_id',XOBJ_DTYPE_INT);
	$this->initVar('att_Date', XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateentered', XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited', XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby', XOBJ_DTYPE_INT);
	$this->initVar('editedby', XOBJ_DTYPE_INT);
	$this->initVar('attendancecount',XOBJ_DTYPE_INT);

	$this->initVar('oddrow', XOBJ_DTYPE_INT);
    	$this->initVar('totalloopcount', XOBJ_DTYPE_INT);
	
     }

}    
    

class oscAttendanceAttendanceHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $attendance = new Attendance ();
        if ($isNew) {
            $attendance->setNew();
        }
        return $attendance;
    }

    function &get($id)
    {
        if ($id > 0) 
	{
		$attendance =&$this->create(false);

		$sql = "SELECT * FROM " . $attendance->table . " WHERE att_id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			unset($attendance);
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{

			$attendance->assignVars($row);
			
			return $attendance;

		}
        }
    }

    function &match($date, $event_id)
    {
        if (isset($date) && isset($event_id)) 
	{
		$attendance =&$this->create(false);
		$sql = "SELECT * FROM " . $attendance->table . " WHERE att_Date=" . $this->db->quoteString($date) . " and event_id=" . $event_id;

		if (!$result = $this->db->query($sql)) 
		{
			unset($attendance);
		} 
		if($row = $this->db->fetchArray($result)) 
		{

			$attendance->assignVars($row);
			if(!$attendance->getVar('att_id')>0)
				unset($attendance);
			else
				return $attendance;
		}
        }
    }

    
            
    function &search($searcharray, $sort)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
        if (isset($searcharray)) 
	{
	        $event= &$this->create(false);
		$sql = "SELECT * FROM " . $attendance->table . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "event_Name LIKE '%$searcharray[0]%')";
		
		}
		
//		$sql .= " ) ";
/*		
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= ") order by familyname";
			break;
			case "citystate":
			$sql .= ") order by city,state ";
			break;

			case "email":
			$sql .= ") order by email ";
			break;
						
			default:
			$sql .= ") order by familyname ";
			break;
			}
		}
	}
*/		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		$oddrow=false;

		$i=0;
		$event=new Event();
		$returnevents[0]=$event;
		while($row = $this->db->fetchArray($result)) 
		{
			$event =&$this->create(false);
			$event->assignVars($row);
			$event->assignVar('oddrow',$oddrow);
			$event->assignVar('loopcount',$i);
			$returnevents[$i]=$event;
			
			if($oddrow){$oddrow=false;}
			else {$oddrow=true;}

			$i++;
		}
		$returnevents[0]->assignVar('totalloopcount',$i);
		
	}
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

	function &insertbarcodes(&$attendance, &$uid, &$barcodes)
	{
	
		$returnbarcodes="";
	
		$badids = array();
		
		$retatt=$this->match($attendance->getVar('att_Date'), $attendance->getVar('event_id'));
		
		if(isset($retatt))
		{
			$returnid = $retatt->getVar('att_id');
		}
		else
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

			$result=$this->db->query($sql);
			
			$returnid = $this->db->getInsertId();
		}
	
		$barcodearr=split("\r\n|\r",$barcodes);

		$person_handler= &xoops_getmodulehandler('person', 'oscmembership');

		foreach($barcodearr as $id)
		{
			//verify member id
			$person=$person_handler->get($id);
			if(isset($person))
			{
				//good id
				$sql="INSERT INTO " . $this->db->prefix("oscatt_attendance_person") . " values(" . $returnid . "," . $id . " ) ";

				$result=$this->db->query($sql);
			}
			else
			{
				//bad id
				if(strlen($id)>0)
				array_push($badids,$id);
			}

		}
	
		if(count($badids)>0)
		{
			$returnbarcodes=implode("\n",$badids);
			return $returnbarcodes;
		}
		else
			unset($returnbarcodes);

			
	}
	
	function &addtoCart(&$attendance, &$uid)
	{
		//Insert cart contents for this attendance
		$sql="INSERT INTO " .
		$this->db->prefix("oscmembership_cart") . "(xoops_uid, person_id) select " . $uid . ", att_personid from " . $this->db->prefix("oscatt_attendance_person")  . " p left join  " . $this->db->prefix("oscmembership_cart") . " c on p.att_personid=c.person_id where c.person_id is null and   p.att_id=" . $attendance->getVar("att_id");
		
		$result=$this->db->query($sql);
	
	}

	function &delete(&$attendance)
	{
		$sql="delete from " . 
		$this->db->prefix("oscatt_attendance_person") . " where att_id=" . $attendance->getVar("att_id");
		
		$result=$this->db->query($sql);
		
		$sql="delete from " . 
		$this->db->prefix("oscatt_attendance") . " where att_id=" . $attendance->getVar("att_id");
		
		$result=$this->db->query($sql);
	}


}

?>
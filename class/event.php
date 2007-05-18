<?php
// $Id: event.php, 2007/4/16
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

class  Event extends XoopsObject {
    var $db;
    var $table;

    function Event()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscatt_events");
	$this->initVar('event_id',XOBJ_DTYPE_INT);
	$this->initVar('event_Name',XOBJ_DTYPE_TXTBOX);
	$this->initVar('event_Place',XOBJ_DTYPE_TXTBOX);

	$this->initVar('dateentered', XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited', XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby', XOBJ_DTYPE_INT);
	$this->initVar('editedby', XOBJ_DTYPE_INT);

	$this->initVar('oddrow', XOBJ_DTYPE_INT);
    	$this->initVar('totalloopcount', XOBJ_DTYPE_INT);
	
     }

}    
    

class oscAttendanceEventHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $event = new Event();
        if ($isNew) {
            $event->setNew();
        }
        return $event;
    }

    function &get($id)
    {
        $event =&$this->create(false);
        if ($id > 0) 
	{
		$sql = "SELECT * FROM " . $event->table . " WHERE event_id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$event->assignVars($row);
		}

		
        }
        return $event;
    }
        
    function &search($searcharray, $sort)
    //Search on criteria and return result
    {
	$result='';
	$returnevents[]=array();
	
        if (isset($searcharray)) 
	{
	        $event= &$this->create(false);
		$sql = "SELECT * FROM " . $event->table . " WHERE (";

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
    
	function &insert(&$event)
    	
	{
		$sql = "INSERT into " . $event->table
		. "(event_Name,"
		. "dateentered, datelastedited, editedby , enteredby) ";
	
		$sql = $sql . "values(" . $this->db->quoteString($event->getVar('event_Name'))
		. "," . 
		$this->db->quoteString($event->getVar('dateentered'))
		. "," .
		$this->db->quoteString($event->getVar('datelastedited'))
		. "," .
		$this->db->quoteString($event->getVar('editedby'))
		. "," .
		$this->db->quoteString($event->getVar('enteredby')) . ")";
		
		if (!$result = $this->db->query($sql)) {
			return false;
			}
			else
			{
			return  $this->db->getInsertId();
			}
	
	}

}

?>
<?php
//  ------------------------------------------------------------------------ ////  ------------------------------------------------------------------------ //
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
// 
//@ ChurchLedger.com, Steve McAtee 7/10/07 ------------------------------------------------------------------------ //

include XOOPS_ROOT_PATH . "/modules/oscmembership/class/person.php";

class  attPerson extends Person {
    var $db;
    var $table;

    function attPerson()
    {
	$this->Person();  //initiate person values
	$this->initVar('lastattid',XOBJ_DTYPE_INT);
	$this->initVar('att_Date',XOBJ_DTYPE_TXTBOX);
	$this->initVar('event_Name',XOBJ_DTYPE_TXTBOX);

    }

}    
    
//oscMembershipPersonHandler
//class oscAttendanceattPersonHandler extends XoopsObjectHandler
class oscAttendanceattPersonHandler extends oscMembershipPersonHandler
{
  
    function &create($isNew = true)
    {
        $person = new attPerson();
        if ($isNew) {
            $person->setNew();
        }
        return $person;
    }

	function &getNoAttendees($days, $searcharray, $sort)
	{
	    	$persons[]=array();

		$sql="select p.*, events.att_id lastattid, a.att_Date, e.event_Name from " . $this->db->prefix("oscmembership_person") . " p left join (select ap.att_personid, DATEDIFF(curdate(),a.att_Date) daydiff from " . $this->db->prefix("oscatt_attendance_person") . " ap join  " . $this->db->prefix("oscatt_attendance") . " a on ap.att_id = a.att_id where DATEDIFF(curdate(),a.att_Date)>" . $days . " ) list on p.id=list.att_personid left join (select distinct max(att_Date) att_Date, max(ap.att_id) att_id,ap.att_personid from " . $this->db->prefix("oscatt_attendance_person") . " ap join  " . $this->db->prefix("oscatt_attendance") . " a on ap.att_id = a.att_id group by ap.att_personid ) events on p.id = events.att_personid left join  " . $this->db->prefix("oscatt_attendance") . " a on events.att_id = a.att_id left join " . $this->db->prefix("oscatt_events") . " e on a.event_id = e.event_id where list.daydiff is null and p.clsid=1 ";


		if(isset($searcharray))
		{
			$count = count($searcharray);
			if ( $count > 0 && is_array($searcharray) ) 
			{
				if($searcharray[0]!="")
				{
					$sql .= " and (lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%' or address1 like   '%$searcharray[0]%' or city like '%$searcharray[0]%' or state like '%$searcharray[0]%' )";
				}
			}
		}

//echo $sql;
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		} 
		else
		{
			$i=0;
			while($row = $this->db->fetchArray($result)) 
			{
				$person =&$this->create(false);
				$person->assignVars($row);
				$person->assignVar('loopcount',$i);
				$persons[$i]=$person;
				$i++;
			}		

			if($i>0)
			{
				$person=$persons[0];
				$person->assignVar('totalloopcount',$i-1);
				$persons[0]=$person;
				
			}
			else
			{
				$person = new Person();
				$person->assignVar('totalloopcount',0);
				$persons[0]=$person;
			}

			return $persons;
		}

		
	}
    
    
		
}


?>
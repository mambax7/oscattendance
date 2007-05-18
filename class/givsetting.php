<?php
// $Id: contribution.php,v 1.0 2007/2/5 root Exp $
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

class  Givsetting extends XoopsObject {
    var $db;
    var $table;

    function Givsetting()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscgiving_settings");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('letterendofyear',XOBJ_DTYPE_TXTBOX);
	
     }

}    
    

class oscGivingGivsettingHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $setting = new Givsetting();
        if ($isNew) {
            $setting->setNew();
        }
        return $setting;
    }
    
    
    function &getSetting()
    {
    	
	$setting =&$this->create(false);

	$sSQL = "SELECT DISTINCT * FROM " . $setting->table;
	$result = $this->db->query($sSQL);

	$i=0;	
	$row = $this->db->fetchArray($result);
	$setting->assignVars($row);

	return $setting;

    }
    
    function &saveSetting(&$setting)
    {
    	$sql="Update " . $setting->table . " set letterendofyear=" . $this->db->quoteString($setting->getVar('letterendofyear'));
	
	$this->db->query($sql);
    
    }


}

?>
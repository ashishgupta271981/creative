<?php
if ( ! function_exists('tagsall'))
{
	/**
	 * Tags List
	 *
	 * Returns the "tagsall" from your config file
	 *
	 * @return	string
	 */
	function tagsall()
	{
		$ci = get_instance();
		$parents = array();
		$ci->db->distinct();
        $ci->db->select('tt.name');
        $query = $ci->db->get('tag_translations as tt');
        if($query->num_rows()>0)
        {
            foreach($query->result_array() as $row)
            {
				
               $parents[] = $row['name'];
            }
        }
        //echo $this->db->last_query();
        return json_encode($parents);
	}
}

// ------------------------------------------------------------------------

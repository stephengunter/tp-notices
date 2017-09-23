<?php
	    
	  function save($user_id , $user_unit){
		    $id=0;
			if (isset($_POST['Id'])) {
				$id=(int)$_POST['Id'];
			}
			
			if($id){
				return update();
				
			}else{
				
				return insert($user_id , $user_unit);
			}
		  
	  }
	  
	  
		
		
       
        
        
      
            
       

    
   

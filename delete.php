<?php

    function notFound()
	{
		  http_response_code(404);
		  print json_encode(['error' => '找不到網頁']);
		  exit;
	}
	
	function jsonError($msg)
	{
		  http_response_code(500);
		  print json_encode(['error' => $msg]);
		  exit;
		
	}
   
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	   $id=0;
	   if (isset($_POST['Id'])) {
			$id=(int)$_POST['Id'];
	   }
	   
	   if(!$id) return notFound();
	   
	   require_once("service.php");
       $service=new NoticeService();
	   
	   $notice=$service->getById($id);
	   if(!$notice) return notFound();
	   
	   if($notice['Reviewed']) return jsonError('資料無法刪除');  //審閱過資料無法刪除
	   
	   $can_delete=false;
	    // 使用者權限驗證
	   $user_id = '51';  //當前使用者Id
	   $user_unit = '102000';  //當前使用者部門
	   $can_delete=true;
	  
	   if(!$can_delete) return jsonError('權限不足');  
	   
	   $service->delete($id);
	   
	   print json_encode(['success' => true ]);
	   exit;
		
	    
   }else{
	    return notFound();
	   
   }
	  
	   
	   
	   
	   
	   
	  
  


    
  
?>
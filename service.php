<?php
   class NoticeService
   {
	   public function __construct()
	   {
		   $serverName = "127.0.0.1\SQLEXPRESS2014"; 
		   $connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
		   $this->conn = sqlsrv_connect( $serverName, $connectionInfo);
		   
		   //驗證,取得當前使用者資料
		   $this->current_user= [
		       'id' => 51,   //當前使用者id
			   'unit' => '105010',
			   'role' => ''    //身分,例如主管
		   ];
		   
		   
	   }
	   public function getCurrentUserId()
	   {
			return $this->current_user['id'];
	   }
	   
	   public function getCurrentUserUnit()
	   {
		   return $this->current_user['unit'];
	   }
	   
	   public function canEdit($notice)
	   {
		   $user_id = $this>getCurrentUserId();
		   if(!$notice['Id']) return true;
		   
		   //審核過資料無法修改
		   if($notice['Reviewed']) return false;
		   //建檔者本人
		   if($notice['UpdatedBy'] == $user_id ) return true;
		   
		   return false;
		   
	   }
	   
	   public function canReview($notice)
	   {
		   $user_id = $this>getCurrentUserId();
		   if(!$notice['Id']) return false;
		   
		   /// 如果是發送部門主管, 可以
		   $createdBy = $notice['CreatedBy'];
		   if($user_id=='501') return true;
		   return false;
	   }
	   
	   public function canDelete($notice)
	   {
		   $user_id = $this>getCurrentUserId();
		   $canEdit=$this->canEdit($notice, $user_id);
		   if(!$canEdit) return false;
		   
		   
		   
		   return true;
		   
	   }
	   
	   public function getById($id)
	   {
		    $conn = $this->conn;
		   
			
			$tsql = "SELECT * FROM Notices WHERE id=?" ;
			
			$params = array($id);
			$stmt = sqlsrv_query( $conn, $tsql , $params);
			
			$notice = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
			
			
			
		    return $notice;
		  
	  }
	   
	   public function edit($id)
	   {
		   $conn = $this->conn;
		   
		   $notice = [
				'Id' => 0 , 
				'Content' => '',
				'Staff' => 0,
				'Teacher' => 0,
				'Student' => 0,
				'Reviewed' => 0,
				'Units' => '',
				'Classes' => '',
				'Levels' => '',
				
				'PS' => '',

	        ];
			
		   	
			if($id)  {
				
				
				$tsql = "SELECT * FROM Notices WHERE id=?" ;
				
				$params = array($id);
				$stmt = sqlsrv_query( $conn, $tsql , $params);
				
				$record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
				
				if($record) $notice=$record;
				
				
	
			}
			
			$attachment=$this->findAttachment($id);
			
			
			
		   
			
			
			return array($notice, $attachment);
			
	
		   
	   }
	   
	   private function findAttachment($notice_Id)
	   {
		   $attachment=[
				'Id' => 0 , 		
				'Notice_Id' => 0,
				'Title' => '',
				'Name' => '',
				'Type' => '',
				'FileData' => '',

	        ];
			
			if($notice_Id)  {
				
				$conn = $this->conn;
				$tsql = "SELECT TOP 1 * FROM NoticeAttachment WHERE Notice_Id=?" ;
				
				$params = array($notice_Id);
				$stmt = sqlsrv_query( $conn, $tsql , $params);
				
				$record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
				
				if($record) $attachment=$record;
	
		       
			}
			
			return $attachment;
			
			
	   }
	   public function insert() 
	   {
	       $user_id = $this>getCurrentUserId();
		   $user_unit = $this>getCurrentUserUnit();
		   
		   $conn = $this->conn;
		   
		   $createdBy=$user_unit;  //使用者部門
		   $updatedBy=$user_id;  //使用者id
		   
		   $now=date('Y-m-d H:i:s');
		   
		   
		   $values=$this->getPostedValues();
		   
		   $query = "INSERT INTO Notices (Content, Staff, Teacher , Student , Units , Classes , Levels , PS , Reviewed , CreatedBy , CreatedAt , UpdatedBy , UpdatedAt) "; 
		   $query .= "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?); SELECT SCOPE_IDENTITY()"; 
		   
		   $arrParams[]=$values['Content'];  
		   $arrParams[]=$values['Staff'];  
		   $arrParams[]=$values['Teacher']; 
		   $arrParams[]=$values['Student']; 
		   $arrParams[]=$values['Units']; 
		   $arrParams[]=$values['Classes']; 
		   $arrParams[]=$values['Levels']; 
		   $arrParams[]=$values['PS']; 
		   $arrParams[]=$values['Reviewed']; 
		   
		   $arrParams[]=$createdBy; 
		   $arrParams[]=$now; 
		   $arrParams[]=$updatedBy; 
		   $arrParams[]=$now; 
		  
		   
		   $resource=sqlsrv_query($conn, $query, $arrParams); 
		   sqlsrv_next_result($resource); 
		   sqlsrv_fetch($resource); 
			
		   $notice_id= sqlsrv_get_field($resource, 0); 
		   
		   $has_file= false;
		   if(isset($_FILES['Attachment'])){
			   if (is_uploaded_file($_FILES['Attachment']['tmp_name'])) $has_file=true;
		   }
		 
		   if($has_file){
			      $this->saveAttachment($notice_id,$user_id , $user_unit);
		   }
		  
		
		  
	  }
	  
	  public function update($id) 
	  {
		   $user_id = $this>getCurrentUserId();
		   $user_unit = $this>getCurrentUserUnit();
		   
		   $conn = $this->conn;
		   
		   $createdBy=$user_unit;  //使用者部門
		   $updatedBy=$user_id;  //使用者id
		   
		   $now=date('Y-m-d H:i:s');
		   
		   
		   $values=$this->getPostedValues();
		   
		   $query = "UPDATE Notices SET Content=(?), Staff=(?), Teacher=(?) , Student=(?) , Units=(?) , Classes=(?) , Levels=(?) , ";
		   $query .= "PS=(?) , Reviewed=(?) , CreatedBy=(?) , CreatedAt=(?) , UpdatedBy=(?) , UpdatedAt=(?) "; 
		   $query .= "WHERE Id=(?)";
		 
		   
		   
		   $arrParams[]=$values['Content'];  
		   $arrParams[]=$values['Staff'];  
		   $arrParams[]=$values['Teacher']; 
		   $arrParams[]=$values['Student']; 
		   $arrParams[]=$values['Units']; 
		   $arrParams[]=$values['Classes']; 
		   $arrParams[]=$values['Levels']; 
		   $arrParams[]=$values['PS']; 
		   $arrParams[]=$values['Reviewed']; 
		   
		   $arrParams[]=$createdBy; 
		   $arrParams[]=$now; 
		   $arrParams[]=$updatedBy; 
		   $arrParams[]=$now; 
		   
		   $arrParams[]=$id; 
		  
		   
		   sqlsrv_query($conn, $query, $arrParams); 
		  
		   
		   $has_file= false;
		   if(isset($_FILES['Attachment'])){
			   if (is_uploaded_file($_FILES['Attachment']['tmp_name'])) $has_file=true;
		   }
		 
		   if($has_file){
			     $this->saveAttachment($id,$user_id , $user_unit);
		   }else{
			   $file_title ='';
			   if (isset($_POST['Attachment_Title'])){
				 $file_title =$_POST['Attachment_Title'];
			   } 
			   
			   
			   
			   if($file_title) $this->updateAttachmentTitle($file_title,$id,$user_id );
		   }
		  
	  }
	  
	  private function saveAttachment($notice_id)
	  {
			$user_id = $this>getCurrentUserId();
		    $user_unit = $this>getCurrentUserUnit();
		   
			$file_name = $_FILES['Attachment']['name'];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$file_title = $file_name;
			if (isset($_POST['Attachment_Title'])){
				$file_title =$_POST['Attachment_Title'];
			} 

			$file_data=base64_encode(file_get_contents($_FILES['Attachment']['tmp_name']));
			
			$now=date('Y-m-d H:i:s');
			$createdBy=$user_unit;  //使用者部門
		    $updatedBy=$user_id;  //使用者id
			
			$conn = $this->conn;
			$query = "INSERT INTO NoticeAttachment (Notice_Id, Title, Name , Type , FileData, CreatedBy , CreatedAt , UpdatedBy , UpdatedAt) "; 
		    $query .= "VALUES (?,?,?,?,?,?,?,?,?)"; 
			
			$arrParams[]=$notice_id;  
		    $arrParams[]=$file_title;  
		    $arrParams[]=$file_name; 
		    $arrParams[]=$file_ext; 
		    $arrParams[]=$file_data; 
			
			
			$arrParams[]=$createdBy; 
		    $arrParams[]=$now; 
		    $arrParams[]=$updatedBy; 
		    $arrParams[]=$now; 

			
			
			sqlsrv_query( $conn, $query, $arrParams);
			
			

		  
	  }
	  
	  private function updateAttachmentTitle($file_title,$notice_id,$user_id )
	  {
		    
		    $conn = $this->conn;
			$tsql = "SELECT TOP 1 * FROM NoticeAttachment WHERE Notice_Id=?" ;			
			$params = array($notice_id);
			$stmt = sqlsrv_query( $conn, $tsql , $params);
			
			$record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		
		    $attachment_id=0;
			if($record) $attachment_id=$record['Id'];
			
			if(!$attachment_id) return;
			
				
				
		    $now=date('Y-m-d H:i:s');
			
			$query = "UPDATE NoticeAttachment SET Title=(?), UpdatedBy=(?) , UpdatedAt=(?) "; 
			$query .= "WHERE Id=(?)";
		  
			$arrParams[]=$file_title;  
			$arrParams[]=$user_id; 
			$arrParams[]=$now;  
			$arrParams[]=$attachment_id;  
			
			sqlsrv_query( $conn, $query, $arrParams);
			
	  }
	  
	  private function getPostedValues()
	  {
		    

			$content = $_POST['Content'];

			$staff=false;
			if (isset($_POST['Staff'])) $staff=true;

			$teacher=false;
			if (isset($_POST['Teacher'])) $teacher=true;

			$student=false;
			if (isset($_POST['Student'])) $student=true;

			$levels=false;
			if (isset($_POST['Student'])) $student=true;

			
			$units=$_POST['Units'];
			$classes=$_POST['Classes'];
			$levels=$_POST['Levels'];
			$ps=$_POST['PS'];
			$reviewed=$_POST['Reviewed'];

			$values=[
				'Content' => $content,
				'Staff' => $staff,
				'Teacher' => $teacher,
				'Student' => $student,
				'Units' => $units,
				'Classes' => $classes,
				'Levels' => $levels,
				'PS' => $ps,
				'Reviewed' => false,			
			];
			
			return $values;
		  
	  }
	  
	  public function delete($id)
	  {
		   $conn = $this->conn;
		   $query = "DELETE FROM Notices WHERE Id=?";
		   
		   $params[]=$id;
		  
		   
		   sqlsrv_query($conn, $query, $params); 
		  
	  }
	   
	   
   }
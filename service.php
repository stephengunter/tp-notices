<?php
   class NoticeService
   {
	   public function __construct()
	   {
		   $serverName = "127.0.0.1\SQLEXPRESS2014"; 
		   $connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
		   $this->conn = sqlsrv_connect( $serverName, $connectionInfo);
	   }
	   
	   public function store($user_id , $user_unit)
	   {
		    $id=0;
			if (isset($_POST['Id'])) {
				$id=(int)$_POST['Id'];
			}
			
			if($id){
				 $this->update($user_id , $user_unit);
				
			}else{
				
				 $this->insert($user_id , $user_unit);
				
				
			}
		   
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
				
				sqlsrv_free_stmt( $stmt);
	
			}
			
			$attachment=$this->findAttachment($id);
			
			
			
		    sqlsrv_close($conn);
			
			
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
	
		        sqlsrv_free_stmt( $stmt);
			}
			
			return $attachment;
			
			
	   }
	   private function insert($user_id , $user_unit) 
	   {
	     
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
		   
		   $has_file=isset($_FILES['Attachment']);
		 
		   if($has_file){
			      $this->saveAttachment($notice_id,$user_id , $user_unit);
		   }
		  
		
		  
	  }
	  
	  private function update()
	  {
		  
		  
	  }
	  
	  private function saveAttachment($notice_id,$user_id , $user_unit)
	  {
			
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

			
			
			$stmt = sqlsrv_query( $conn, $query, $arrParams);
			
			

		  
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
	   
	   
   }
    <?php     
        $submit = htmlspecialchars(filter_input(INPUT_POST,'submit'));
         $id = htmlspecialchars(filter_input(INPUT_POST,'clientId'));
         $idEdit = htmlspecialchars(filter_input(INPUT_POST,'id'));
         $idBook = htmlspecialchars(filter_input(INPUT_POST,'IdBook'));
         $fName = htmlspecialchars( filter_input(INPUT_POST,'fname'));
         $lName = htmlspecialchars( filter_input(INPUT_POST,'lname'));
         $appDateEscaped = htmlspecialchars( filter_input(INPUT_POST,'appDate'));
         $clientIDEscaped = htmlspecialchars(filter_input(INPUT_POST,'clientID'));
         $startTimeEscaped = htmlspecialchars(filter_input(INPUT_POST,'stTime'));
         $endTimeEscaped = htmlspecialchars(filter_input(INPUT_POST,'endTime'));
         
		     // db connection
         require_once('CONNECT_DB.php');  
         //  display appointment booking to display on the html table
         function upcomingAppointment(){
             global $db, $detail;
         $query = "SELECT clients.client_fname, clients.client_lname, booking.client_id_num, 
                           booking.apmnt_date, booking.start_time, booking.end_time
		             FROM clients
                     INNER JOIN booking
                     WHERE clients.client_id_num = booking.client_id_num
                     AND booking.apmnt_date >= CURRENT_DATE
                     ORDER BY apmnt_date";
		     $statement = $db->prepare($query);
		     $statement->execute();
		     $detail = $statement->fetchAll();
		     $statement->closeCursor();
         }
          upcomingAppointment();
           function viewAppointment(){
               global $db, $detail, $idBook;
              $query = "SELECT DISTINCT clients.client_fname, clients.client_lname, booking.client_id_num, 
                     booking.apmnt_date, booking.start_time, booking.end_time
		             FROM clients
                     INNER JOIN booking
                     WHERE clients.client_id_num = booking.client_id_num
                     AND booking.client_id_num = :IdBook
                     ORDER BY apmnt_date";
		     $statement = $db->prepare($query);
             $statement->bindValue('IdBook', $idBook);
		     $statement->execute();
		     $detail = $statement->fetchAll();
		     $statement->closeCursor();
             return $detail;
           }
           
		     
             $query = "SELECT client_id_num
		            FROM clients
                    ORDER By client_id_num";
		     $statement = $db->prepare($query);
                     $statement->bindValue(':clientID', $id);
		     $statement->execute();
		     $idNumber = $statement->fetchAll();
		     $statement->closeCursor();
             
             // querry for booking, database table
              $query = "SELECT DISTINCT client_id_num
		             FROM booking
                     ORDER By client_id_num";
		     $statement = $db->prepare($query);
             $statement->bindValue(':clientID', $idBook);
		     $statement->execute();
		     $idBooking = $statement->fetchAll();
		     $statement->closeCursor();
         // retrieves the latest date of appointment
         function maxDate(){
                global $db;
              $query = "SELECT MAX(apmnt_date) 
              FROM booking";
		     $statement = $db->prepare($query);
		     $statement->execute();
		     $maxAppDate = $statement->fetchColumn();
		     $statement->closeCursor();
             return $maxAppDate;
            }
             $appointmentDate = maxDate();
            
            // retrive the latest end time on the given date 
            function getEndTime(){
              global $db, $appointmentDate;
              $query = "SELECT MAX(end_time) 
              FROM booking
              WHERE apmnt_date = :apmnt_date";
		     $statement = $db->prepare($query);
             $statement->bindValue(':apmnt_date', $appointmentDate);
		     $statement->execute();
		     $maxEndTime = $statement->fetchColumn();
		     $statement->closeCursor();
             return $maxEndTime;
            }
   
           $maxEndTime = getEndTime();
          // move to next day if end time equal to 16:00
          if ( $maxEndTime === Date("H:i:s",strtotime('16:00:00'))){
              $appointmentDate = Date('Y-m-d', strtotime($appointmentDate. '+ 1 day'));
              $maxEndTime =  Date("H:i:s",strtotime('7:00:00'));
          }
           
         If ($submit == "Save") {   
             // checks if the row has same booking details to be submitted
               $selectQuery = "SELECT* FROM booking
                WHERE 
                apmnt_date = '$appDateEscaped'
                AND start_time = '$startTimeEscaped'" ;
                $select = $db->query($selectQuery);
                if ($select->rowCount() > 0){
                    $check = "Not submitted: The slot is already taken: Please check your start time."; 
                     include('booking_home.php');
                     exit();   
             }else{
              if ($appDateEscaped <  Date('Y-m-d'))  {
                  $check = "Not submitted: The date you entered is is before a current date: Please enter a valid date."; 
                     include('booking_home.php');
                     exit();
              } if ( $endTimeEscaped == " " || $startTimeEscaped == ""){
                  $check = "Not submitted: Please enter all fields";
                   include('booking_view.php');
                 exit(); 
              }  if ($startTimeEscaped >= $endTimeEscaped){
                  $check = "Not submitted: End time must be after start time";
                   include('booking_view.php');
                  exit();
              }
		     
		     $query = "INSERT INTO booking
		     (apmnt_date, client_id_num, start_time, end_time)
		     VALUES 
		     (:apmnt_date, :client_id_num, :start_time, :end_time)";
		     $statement = $db->prepare($query);
		     $statement->bindValue(':apmnt_date', $appDateEscaped);
		     $statement->bindValue(':client_id_num', $clientIDEscaped);
		     $statement->bindValue(':start_time', $startTimeEscaped);
		     $statement->bindValue(':end_time', $endTimeEscaped);
		     $statement->execute();
		     $statement->closeCursor();	
             
              header("Location:booking_home.php");  
             }    
         }
         else if($submit == "Edit"){
               viewAppointment();
          }
           else if($submit == "Search"){
              viewAppointment();
          }
          else{
              upcomingAppointment();
          }
 ?>
<?php 
    /*
        Author: Belal Khan
        Post: PHP Rest API Example using SLIM
    */

        class DbOperations{
        //the database connection variable
            private $con; 

        //inside constructor
        //we are getting the connection link
            function __construct(){
                require_once dirname(__FILE__) . '/DbConnect.php';
                $db = new DbConnect; 
                $this->con = $db->connect(); 
            }
            public function createSharedUser($name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent){
               if(!($this->isEmailExist($email) && isUsernameExist($username))){

                $stmt = $this->con->prepare("INSERT INTO profit_shared_user (name, username, email, mobile, district, upazilla, up, shared_percent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssd", $name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent);
                if($stmt->execute()){
                    return DATA_INSERTED; 
                }else{
                    return DATA_INSERTED_FAILED;
                }
            }
            return DATA_EXISTS; 
            }
            public function getAllSharedUsers(){
                $stmt = $this->con->prepare("SELECT user_id, name, username, email, mobile, district, upazilla, up, shared_percent FROM profit_shared_user;");
                $stmt->execute(); 
                $stmt->bind_result($user_id, $name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent);
                $users = array(); 
                while($stmt->fetch()){ 
                    $user = array(); 
                    $user['user_id'] = $user_id; 
                    $user['name']=$name; 
                    $user['username'] = $username; 
                    $user['email'] = $email;
                    $user['mobile'] = $mobile;
                    $user['district'] = $district;
                    $user['upazilla'] = $upazilla;
                    $user['up'] = $up;
                    $user['shared_percent'] = $shared_percent; 
                    array_push($users, $user);
                }             
                return $users; 
            }
            public function getSharedUserByEmail($email){
                $stmt = $this->con->prepare("SELECT user_id, name, username, email, mobile, district,upazilla, up, shared_percent FROM profit_shared_user WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->bind_result($user_id, $name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent);
                $stmt->fetch(); 
                $user = array(); 
                $user['user_id'] = $user_id; 
                $user['name']=$name; 
                $user['username'] = $username; 
                $user['email'] = $email; 
                $user['mobile'] = $mobile;
                $user['district'] = $district;
                $user['upazilla'] = $upazilla;
                $user['up'] = $up;
                $user['shared_percent'] = $shared_percent;
                return $user; 
            }
            public function updateSharedUser($name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent, $id){
                $stmt = $this->con->prepare("UPDATE profit_shared_user SET name = ?, username = ?, email = ?, mobile = ?, district = ?, upazilla = ?, up = ?, shared_percent = ? WHERE user_id = ?");
                $stmt->bind_param("sssssssdi", $name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent, $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function deleteSharedUser($id){
                $stmt = $this->con->prepare("DELETE FROM profit_shared_user WHERE user_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0; 
            }
            public function addTransaction($from_username, $to_username, $amount, $trans_type, $trans_charge){
                $stmt = $this->con->prepare("INSERT INTO transaction(from_username, to_username, amount, trans_type, trans_charge) VALUES (?, ?, ?, ?, ?) ");
                $stmt->bind_param("ssdss",$from_username, $to_username, $amount, $trans_type, $trans_charge);
                if ($stmt->execute()) {
                    return DATA_INSERTED;   
                } else{
                    return DATA_INSERTED_FAILED;
                }
            }
            public function getAllTransactions(){
                $stmt = $this->con->prepare("SELECT id, from_username, to_username, trans_date, amount, trans_type, trans_charge, status FROM transaction");
                $stmt->execute();
                $stmt->bind_result($id, $from_username, $to_username, $trans_date, $amount, $trans_type, $trans_charge, $status);
                $transactions = array(); 
                while($stmt->fetch()){ 
                    $transaction = array(); 
                    $transaction['id'] = $id; 
                    $transaction['from_username']=$from_username; 
                    $transaction['to_username'] = $to_username; 
                    $transaction['trans_date'] = $trans_date;
                    $transaction['amount'] = $amount;
                    $transaction['trans_type'] = $trans_type;
                    $transaction['trans_charge'] = $trans_charge;
                    $transaction['status'] = $status;    
                    array_push($transactions, $transaction);
                }             
                return $transactions; 
            }
            public function getTransactionByFromUser($username){
                $stmt = $this->con->prepare("SELECT id, from_username, to_username, trans_date, amount, trans_type, trans_charge, status FROM transaction WHERE from_username = ?");
                $stmt->bind_param("s",$username);
                $stmt->execute();
                $stmt->bind_result($id, $from_username, $to_username, $trans_date, $amount, $trans_type, $trans_charge, $status);
                $stmt->fetch();
                $transaction = array(); 
                $transaction['id'] = $id; 
                $transaction['from_username']=$from_username; 
                $transaction['to_username'] = $to_username; 
                $transaction['trans_date'] = $trans_date;
                $transaction['amount'] = $amount;
                $transaction['trans_type'] = $trans_type;
                $transaction['trans_charge'] = $trans_charge;
                $transaction['status'] = $status;    
                return $transaction;
            }
            public function getTransactionById($id){
                $stmt = $this->con->prepare("SELECT id, from_username, to_username, trans_date, amount, trans_type, trans_charge, status FROM transaction WHERE id = ?");
                $stmt->bind_param("s",$id);
                $stmt->execute();
                $stmt->bind_result($id, $from_username, $to_username, $trans_date, $amount, $trans_type, $trans_charge, $status);
                $stmt->fetch();
                $transaction = array(); 
                $transaction['id'] = $id; 
                $transaction['from_username']=$from_username; 
                $transaction['to_username'] = $to_username; 
                $transaction['trans_date'] = $trans_date;
                $transaction['amount'] = $amount;
                $transaction['trans_type'] = $trans_type;
                $transaction['trans_charge'] = $trans_charge;   
                $transaction['status'] = $status;        
                return $transaction;
            }
        
            public function updateTransaction($from_username, $to_username, $amount, $trans_type, $trans_charge, $id){
                    $stmt = $this->con->prepare("UPDATE transaction SET from_username =? , to_username = ?, amount = ?, trans_type = ?, trans_charge = ? WHERE id = ?");
                    $stmt->bind_param("ssdssi",$from_username, $to_username, $amount, $trans_type, $trans_charge, $id);
                    $stmt->execute();
                    $rowCount = $stmt->affected_rows;
                    return $rowCount>0;
                } 
            public function updateTransactionStatus($status,$id){
                    $stmt = $this->con->prepare("UPDATE transaction SET status = ? WHERE id = ?");
                    $stmt->bind_param("si",$status, $id);
                    $stmt->execute();
                    $rowCount = $stmt->affected_rows;
                    return $rowCount>0;
            }
            public function deleteTransaction($id){
                $stmt = $this->con->prepare("DELETE FROM transaction WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function addMatch($team1, $team2, $date_time,$tournament, $match_type, $match_format){
                $stmt = $this->con->prepare("SELECT id FROM matches WHERE team1=? AND team2 = ? AND date_time = ?");
                $stmt->bind_param("sss",$team1,$team2,$date_time);
                $stmt->execute();
                $stmt->store_result();
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("INSERT INTO `matches`(`team1`, `team2`, `date_time`,`tournament`, `match_type`, `match_format`) VALUES (?,?,?,?,?,?)");
                    $stmt->bind_param("ssssss",$team1, $team2,$date_time,$tournament, $match_type, $match_format);
                    if ($stmt->execute()) {
                        return DATA_INSERTED;
                    }else{
                        return DATA_INSERTED_FAILED;
                    }
                }
                return DATA_EXISTS;
                
            }

            public function getInsertedMatch($team1, $team2, $date_time){
                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time, match_type, match_format, status FROM matches WHERE status = 1 AND team1 = ? AND team2 = ? AND date_time= ?");
                $stmt->bind_param("sss",$team1,$team2,$date_time);
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time, $match_type, $match_format, $status);
                $stmt->fetch();
                $match = array(); 
                $match['id'] = $id; 
                $match['team1']=$team1; 
                $match['team2'] = $team2; 
                $match['date_time'] = $date_time;
                $match['match_type'] = $match_type;
                $match['match_format'] = $match_format;
                $match['status'] = $status;
                return $match;    
            }
            public function getRunningMatch(){

                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time,tournament, match_type, match_format, status FROM matches WHERE DATE_FORMAT(date_time, '%Y-%m-%d') = CURDATE()");
                //$stmt->bind_param("i",1);
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time,$tournament, $match_type, $match_format, $status);
                $matches = array(); 
                while($stmt->fetch()){ 
                    $match = array(); 
                    $match['id'] = $id; 
                    $match['team1']=$team1; 
                    $match['team2'] = $team2; 
                    $match['date_time'] = $date_time;
                    $match['tournament'] = $tournament;
                    $match['match_type'] = $match_type;
                    $match['match_format'] = $match_format;
                    $match['status'] = $status;    
                    array_push($matches, $match);
                }             
                return $matches; 
            }
            public function getUpcomingMatch(){

                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time,tournament, match_type, match_format, status FROM matches WHERE DATE_FORMAT(date_time, '%Y-%m-%d') > CURDATE()");
                //$stmt->bind_param("i",1);
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time, $tournament, $match_type, $match_format, $status);
                $matches = array(); 
                while($stmt->fetch()){ 
                    $match = array(); 
                    $match['id'] = $id; 
                    $match['team1']=$team1; 
                    $match['team2'] = $team2; 
                    $match['date_time'] = $date_time;
                    $match['tournament'] = $tournament;
                    $match['match_type'] = $match_type;
                    $match['match_format'] = $match_format;
                    $match['status'] = $status;    
                    array_push($matches, $match);
                }             
                return $matches; 
            }
            public function getFinishMatch(){

                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time,tournament, match_type, match_format, status FROM matches WHERE DATE_FORMAT(date_time, '%Y-%m-%d') < CURDATE()");
                //$stmt->bind_param("i",1);
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time, $tournament, $match_type, $match_format, $status);
                $matches = array(); 
                while($stmt->fetch()){ 
                    $match = array(); 
                    $match['id'] = $id; 
                    $match['team1']=$team1; 
                    $match['team2'] = $team2; 
                    $match['date_time'] = $date_time;
                    $match['tournament'] = $tournament;
                    $match['match_type'] = $match_type;
                    $match['match_format'] = $match_format;
                    $match['status'] = $status;    
                    array_push($matches, $match);
                }             
                return $matches; 
            }
            
            public function getAllMatches(){
                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time,tournament, match_type, match_format, status FROM matches");
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time,$tournament,$match_type, $match_format, $status);
                $matches = array(); 
                while($stmt->fetch()){ 
                    $match = array(); 
                    $match['id'] = $id; 
                    $match['team1']=$team1; 
                    $match['team2'] = $team2; 
                    $match['date_time'] = $date_time;
                    $match['tournament'] = $tournament;
                    $match['match_type'] = $match_type;
                    $match['match_format'] = $match_format;
                    $match['status'] = $status;    
                    array_push($matches, $match);
                }             
                return $matches; 
            }
            public function getMatchById($id){
                $stmt = $this->con->prepare("SELECT id, team1,team2, date_time,tournament, match_type, match_format, status FROM matches WHERE id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($id, $team1, $team2, $date_time, $tournament, $match_type, $match_format, $status);
                $stmt->fetch();
                $match = array(); 
                $match['id'] = $id; 
                $match['team1']=$team1; 
                $match['team2'] = $team2; 
                $match['date_time'] = $date_time;
                $match['tournament'] = $tournament;
                $match['match_type'] = $match_type;
                $match['match_format'] = $match_format;
                $match['status'] = $status;    
                return $match; 
            }
            public function updateMatch($team1, $team2, $date_time, $match_type, $match_format, $id){
                $stmt = $this->con->prepare("UPDATE matches SET team1 = ?, team2 = ?, date_time = ?, match_type = ?, match_format = ? WHERE id = ?");
                $stmt->bind_param("sssssi",$team1, $team2,$date_time,  $match_type, $match_format,$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function updateMatchStatus($status,$id){
                $stmt = $this->con->prepare("UPDATE matches SET status = ? WHERE id = ?");
                $stmt->bind_param("ii",$status, $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function deleteMatch($id){
                $stmt = $this->con->prepare("DELETE FROM matches WHERE id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function createBet($question, $started_date, $match_id, $bet_mode){
                $stmt = $this->con->prepare("SELECT bet_id from bet WHERE LOWER(question) = LOWER(?) AND match_id = ? AND bet_mode = ?");
                $stmt->bind_param("sii",$question,$match_id,$bet_mode);
                $stmt->execute();
                $stmt->store_result();
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("INSERT INTO bet (question, started_date, match_id, bet_mode) VALUES (?, ?, ?, ?) ");
                    $stmt->bind_param("ssii",$question, $started_date, $match_id, $bet_mode);
                    if ($stmt->execute()) {
                        return DATA_INSERTED;   
                    } else{
                        return DATA_INSERTED_FAILED;
                    }
                }
                return DATA_EXISTS;
                
            }
            public function getAllBets(){
                $stmt = $this->con->prepare("SELECT bet_id, question,started_date, match_id, bet_mode, status, result FROM bet");
                $stmt->execute();
                $stmt->bind_result($bet_id, $question, $started_date, $match_id, $bet_mode, $status, $result);
                $bets = array();
                while($stmt->fetch()){ 
                    $bet = array(); 
                    $bet['bet_id'] = $bet_id; 
                    $bet['question']=$question; 
                    $bet['started_date'] = $started_date; 
                    $bet['match_id'] = $match_id;
                    $bet['bet_mode'] = $bet_mode;
                    $bet['status'] = $status;
                    $bet['result'] = $result; 
                    array_push($bets, $bet);
                }  
                return $bets; 
            }
            public function getInsertedBet($question, $match_id){
                $stmt = $this->con->prepare("SELECT bet_id, question,started_date, match_id, bet_mode, status, result FROM bet WHERE question = ? AND  match_id = ?");
                $stmt->bind_param("si",$question,$match_id);
                $stmt->execute();
                $stmt->bind_result($bet_id, $question, $started_date, $match_id, $bet_mode, $status, $result);
                $stmt->fetch();
                $bet = array(); 
                $bet['bet_id'] = $bet_id; 
                $bet['question']=$question; 
                $bet['started_date'] = $started_date; 
                $bet['match_id'] = $match_id;
                $bet['bet_mode'] = $bet_mode;
                $bet['status'] = $status;
                $bet['result'] = $result;    
                return $bet; 

            }
            public function getAllBetsByMatch($match_id){
                $stmt = $this->con->prepare("SELECT bet_id, question,started_date, match_id, bet_mode, status, result FROM bet WHERE match_id = ?");
                $stmt->bind_param("i",$match_id);
                $stmt->execute();
                $stmt->bind_result($bet_id, $question, $started_date, $match_id, $bet_mode, $status, $result);
                $bets = array();
                while($stmt->fetch()){ 
                    $bet = array(); 
                    $bet['bet_id'] = $bet_id; 
                    $bet['question']=$question; 
                    $bet['started_date'] = $started_date; 
                    $bet['match_id'] = $match_id;
                    $bet['bet_mode'] = $bet_mode;
                    $bet['status'] = $status;
                    $bet['result'] = $result; 
                    array_push($bets, $bet);
                }  
                return $bets; 
            }
            public function getAllBetsByMatchAndBetMode($match_id, $bet_mode_id){
                $stmt = $this->con->prepare("SELECT bet_id, question,started_date, match_id, bet_mode, status, result FROM bet WHERE match_id = ? AND bet_mode = ?");
                $stmt->bind_param("ii",$match_id,$bet_mode_id);
                $stmt->execute();
                $stmt->bind_result($bet_id, $question, $started_date, $match_id, $bet_mode, $status, $result);
                $bets = array();
                while($stmt->fetch()){ 
                    $bet = array(); 
                    $bet['bet_id'] = $bet_id; 
                    $bet['question']=$question; 
                    $bet['started_date'] = $started_date; 
                    $bet['match_id'] = $match_id;
                    $bet['bet_mode'] = $bet_mode;
                    $bet['status'] = $status;
                    $bet['result'] = $result; 
                    array_push($bets, $bet);
                }  
                return $bets; 
            }
            public function getBetById($id){
                $stmt = $this->con->prepare("SELECT bet_id, question,started_date, match_id, bet_mode, status, result,right_answer FROM bet WHERE bet_id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($bet_id, $question, $started_date, $match_id, $bet_mode, $status, $result,$right_answer);
                $stmt->fetch();
                $bet = array(); 
                $bet['bet_id'] = $bet_id; 
                $bet['question']=$question; 
                $bet['started_date'] = $started_date; 
                $bet['match_id'] = $match_id;
                $bet['bet_mode'] = $bet_mode;
                $bet['status'] = $status;
                $bet['result'] = $result;    
                $bet['right_ans'] = $right_answer;
                return $bet; 
            }
            public function updateBet($question,  $match_id, $bet_mode, $id){
                $stmt = $this->con->prepare("UPDATE bet SET question = ?,  match_id = ?, bet_mode = ? WHERE bet_id = ? ");
                $stmt->bind_param("siii",$question, $match_id, $bet_mode,$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function updateBetResult($result, $right_ans, $id){
                $stmt = $this->con->prepare("UPDATE bet SET result = ?, right_answer = ? WHERE bet_id = ?");
                $stmt->bind_param("sii",$result,$right_ans, $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function cancelBet($id){
                $stmt = $this->con->prepare("UPDATE bet SET result = 'Cancel' WHERE bet_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function deleteBet($id){
                $stmt = $this->con->prepare("DELETE FROM bet WHERE bet_id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function setBetRate($bet_id,$options,$rate,$user_type_id,$bet_mode_id){
                $stmt = $this->con->prepare("INSERT INTO bet_rate(bet_id,options,rate,user_type_id,bet_mode_id) VALUES (?,?,?,?,?)");
                $stmt->bind_param("isdii",$bet_id,$options,$rate,$user_type_id,$bet_mode_id);
                if ($stmt->execute()) {
                    return DATA_INSERTED;
                }else{
                    return DATA_INSERTED_FAILED;
                }
            }
            public function getAllBetRate(){
                $stmt = $this->con->prepare("SELECT id, bet_id, options, rate, user_type_id, bet_mode_id FROM bet_rate");
                $stmt->execute();
                $stmt->bind_result($id, $bet_id, $options, $rate, $user_type_id, $bet_mode_id);
                $bet_rates = array();
                while($stmt->fetch()){ 
                    $bet_rate = array(); 
                    $bet_rate['id'] = $id; 
                    $bet_rate['bet_id']=$bet_id; 
                    $bet_rate['options'] = $options; 
                    $bet_rate['rate'] = $rate;
                    $bet_rate['user_type_id'] = $user_type_id;
                    $bet_rate['bet_mode_id'] = $bet_mode_id;
                    array_push($bet_rates, $bet_rate);
                }  
                return $bet_rates; 
            }
            public function getBetRateByBetId($bet_id){
                $stmt = $this->con->prepare("SELECT id, bet_id, options, rate, user_type_id, bet_mode_id FROM bet_rate WHERE bet_id = ?");
                $stmt->bind_param("i",$bet_id);
                $stmt->execute();
                $stmt->bind_result($id, $bet_id, $options, $rate, $user_type_id, $bet_mode_id);
                $bet_rates = array();
                while($stmt->fetch()){ 
                    $bet_rate = array(); 
                    $bet_rate['id'] = $id; 
                    $bet_rate['bet_id']=$bet_id; 
                    $bet_rate['options'] = $options; 
                    $bet_rate['rate'] = $rate;
                    $bet_rate['user_type_id'] = $user_type_id;
                    $bet_rate['bet_mode_id'] = $bet_mode_id;
                    array_push($bet_rates, $bet_rate);
                }  
                return $bet_rates; 
            }
            public function getBetRateByBetIdAndUserType($bet_id,$user_type_id){
                $stmt = $this->con->prepare("SELECT id, bet_id, options, rate, user_type_id, bet_mode_id FROM bet_rate WHERE bet_id = ? AND user_type_id = ?");
                $stmt->bind_param("ii",$bet_id,$user_type_id);
                $stmt->execute();
                $stmt->bind_result($id, $bet_id, $options, $rate, $user_type_id, $bet_mode_id);
                $bet_rates = array();
                while($stmt->fetch()){ 
                    $bet_rate = array(); 
                    $bet_rate['id'] = $id; 
                    $bet_rate['bet_id']=$bet_id; 
                    $bet_rate['options'] = $options; 
                    $bet_rate['rate'] = $rate;
                    $bet_rate['user_type_id'] = $user_type_id;
                    $bet_rate['bet_mode_id'] = $bet_mode_id;
                    array_push($bet_rates, $bet_rate);
                }  
                return $bet_rates; 
            }
            public function getBetRateById($bet_id){
                $stmt = $this->con->prepare("SELECT id, bet_id, options, rate, user_type_id, bet_mode_id FROM bet_rate WHERE id = ?");
                $stmt->bind_param("i",$bet_id);
                $stmt->execute();
                $stmt->bind_result($id, $bet_id, $options, $rate, $user_type_id, $bet_mode_id);
                $bet_rates = array();
                while($stmt->fetch()){ 
                    $bet_rate = array(); 
                    $bet_rate['id'] = $id; 
                    $bet_rate['bet_id']=$bet_id; 
                    $bet_rate['options'] = $options; 
                    $bet_rate['rate'] = $rate;
                    $bet_rate['user_type_id'] = $user_type_id;
                    $bet_rate['bet_mode_id'] = $bet_mode_id;
                    array_push($bet_rates, $bet_rate);
                }  
                return $bet_rates; 
            }
            public function updateBetRate($options,$rate,$id){
                $stmt = $this->con->prepare("UPDATE bet_rate SET options = ?, rate = ? WHERE id = ?");
                $stmt->bind_param("sdi", $options, $rate,$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function updateOnlyBetRate($rate, $id){
                $stmt = $this->con->prepare("UPDATE bet_rate SET rate = ? WHERE id = ?");
                $stmt->bind_param("di",$rate, $id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function deleteBetRate($id){
                $stmt = $this->con->prepare("DELETE FROM bet_rate WHERE id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function getAllBetRateWithDetails(){
                $stmt->con->prepare("SELECT question, bet_date, mode, user_type, options,rate,status,result FROM bet_rate_view");
                $stmt->execute();
                $stmt->bind_result($question, $bet_date, $mode, $user_type, $options, $rate, $status, $result);
                $bet_rates = array();
                while($stmt->fetch()){ 
                    $bet_rate = array(); 
                    $bet_rate['question'] = $question; 
                    $bet_rate['bet_date'] = $bet_date; 
                    $bet_rate['mode'] = $mode; 
                    $bet_rate['user_type'] = $user_type;
                    $bet_rate['options'] = $options;
                    $bet_rate['rate'] = $rate;
                    $bet_rate['status'] = $status;
                    $bet_rate['result'] = $result;
                    array_push($bet_rates, $bet_rate);
                }  
                return $bet_rates; 
            }
            public function addCommission($comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose){
                $stmt = $this->con->prepare("INSERT INTO commission(comm_rate,amount, username, from_user_id,bet_id,purpose) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("ddsiis",$comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose);
                if ($stmt->execute()) {
                    return DATA_INSERTED;
                }else{
                    return DATA_INSERTED_FAILED;
                }
            }
            public function getAllCommission(){
                $stmt = $this->con->prepare("SELECT comm_id,comm_rate, amount, username, from_user_id, bet_id, comm_date,purpose FROM commission");
                $stmt->execute();
                $stmt->bind_result($comm_id, $comm_rate, $amount, $username, $from_user_id, $bet_id,$comm_date, $purpose);
                $commissions = array();
                while($stmt->fetch()){ 
                    $commission = array(); 
                    $commission['comm_id'] = $comm_id; 
                    $commission['comm_rate']=$comm_rate; 
                    $commission['amount'] = $amount; 
                    $commission['username'] = $username;
                    $commission['from_user_id'] = $from_user_id;
                    $commission['bet_id'] = $bet_id;
                    $commission['comm_date'] = $comm_date;
                    $commission['purpose'] = $purpose;
                    array_push($commissions, $commission);
                }  
                return $commissions; 
            }
            public function getCommissionById($id){
                $stmt = $this->con->prepare("SELECT comm_id,comm_rate, amount, username, from_user_id, bet_id, comm_date,purpose FROM commission WHERE comm_id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($comm_id, $comm_rate, $amount, $username, $from_user_id, $bet_id,$comm_date, $purpose);
                $commissions = array();
                while($stmt->fetch()){ 
                    $commission = array(); 
                    $commission['comm_id'] = $comm_id; 
                    $commission['comm_rate']=$comm_rate; 
                    $commission['amount'] = $amount; 
                    $commission['username'] = $username;
                    $commission['from_user_id'] = $from_user_id;
                    $commission['bet_id'] = $bet_id;
                    $commission['comm_date'] = $comm_date;
                    $commission['purpose'] = $purpose;
                    array_push($commissions, $commission);
                }  
                return $commissions; 
            }
            public function updateCommission($comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose,$id){
                $stmt = $this->con->prepare("UPDATE commission SET comm_rate = ?, amount = ?, username = ?, from_user_id = ?,bet_id = ? ,purpose = ? WHERE comm_id = ?");
                $stmt->bind_param("ddsiisi",$comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose,$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            public function deleteCommission($id){
                $stmt = $this->con->prepare("DELETE FROM commission WHERE comm_id = ?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            
            public function addUserBet($user_id, $bet_id,$bet_option_id,$bet_rate,$bet_amount,$bet_return_amount,$bet_mode_id){
                $stmt = $this->con->prepare("SELECT user_id FROM user_bet WHERE user_id = ? AND bet_id = ?");
                $stmt->bind_param("ii",$user_id, $bet_id);
                $stmt->execute();
                $stmt->store_result();
                $rowCount = $stmt->num_rows;

                if ($rowCount>0) {
                    return DATA_EXISTS;
                }else{
                    $stmt = $this->con->prepare("INSERT INTO user_bet (user_id, bet_id, bet_option_id, bet_rate, bet_amount, bet_return_amount, bet_mode_id) VALUES (?,?,?,?,?,?,?)");
                    $stmt->bind_param("iiidddi",$user_id, $bet_id, $bet_option_id, $bet_rate, $bet_amount, $bet_return_amount, $bet_mode_id);
                    if ($stmt->execute()) {
                        return DATA_INSERTED;
                    }else{
                        return DATA_INSERTED_FAILED;
                    }
                }
            }
            public function getAllUserBets(){
                $stmt = $this->con->prepare("SELECT user_id,bet_id,bet_option_id,bet_rate,bet_amount, bet_return_amount,bet_mode_id,bet_date,result FROM user_bet");
                $stmt->execute();
                $stmt->bind_result($user_id, $bet_id, $bet_option_id, $bet_rate, $bet_amount, $bet_return_amount,$bet_mode_id, $bet_date,$result);
                $user_bets = array();
                while($stmt->fetch()){ 
                    $user_bet = array(); 
                    $user_bet['user_id'] = $user_id; 
                    $user_bet['bet_id']=$bet_id; 
                    $user_bet['bet_option_id'] = $bet_option_id; 
                    $user_bet['bet_rate'] = $bet_rate;
                    $user_bet['bet_amount'] = $bet_amount;
                    $user_bet['bet_return_amount'] = $bet_return_amount;
                    $user_bet['bet_mode_id'] = $bet_mode_id;
                    $user_bet['bet_date'] = $bet_date;
                    $user_bet['result'] = $result;
                    array_push($user_bets, $user_bet);
                }  
                return $user_bets; 
            }
            public function getUserBetByUserId($user_id){
                $stmt = $this->con->prepare("SELECT user_id,bet_id,bet_option_id,bet_rate,bet_amount, bet_return_amount,bet_mode_id,bet_date,result FROM user_bet WHERE user_id = ?");
                $stmt->bind_param("i",$user_id);
                $stmt->execute();
                $stmt->bind_result($user_id, $bet_id, $bet_option_id, $bet_rate, $bet_amount, $bet_return_amount,$bet_mode_id, $bet_date,$result);
                $user_bets = array();
                while($stmt->fetch()){ 
                    $user_bet = array(); 
                    $user_bet['user_id'] = $user_id; 
                    $user_bet['bet_id']=$bet_id; 
                    $user_bet['bet_option_id'] = $bet_option_id; 
                    $user_bet['bet_rate'] = $bet_rate;
                    $user_bet['bet_amount'] = $bet_amount;
                    $user_bet['bet_return_amount'] = $bet_return_amount;
                    $user_bet['bet_mode_id'] = $bet_mode_id;
                    $user_bet['bet_date'] = $bet_date;
                    $user_bet['result'] = $result;
                    array_push($user_bets, $user_bet);
                }  
                return $user_bets;
            }
            public function getUserBetByAgentId($agent_id){
                $stmt = $this->con->prepare("SELECT user_id,bet_id,bet_option_id,bet_rate,bet_amount, bet_return_amount,bet_mode_id,bet_date,result FROM user_bet WHERE user_id = (SELECT user_id FROM user WHERE agent_id = ?)");
                $stmt->bind_param("i",$agent_id);
                $stmt->execute();
                $stmt->bind_result($user_id, $bet_id, $bet_option_id, $bet_rate, $bet_amount, $bet_return_amount,$bet_mode_id, $bet_date,$result);
                $user_bets = array();
                while($stmt->fetch()){ 
                    $user_bet = array(); 
                    $user_bet['user_id'] = $user_id; 
                    $user_bet['bet_id']=$bet_id; 
                    $user_bet['bet_option_id'] = $bet_option_id; 
                    $user_bet['bet_rate'] = $bet_rate;
                    $user_bet['bet_amount'] = $bet_amount;
                    $user_bet['bet_return_amount'] = $bet_return_amount;
                    $user_bet['bet_mode_id'] = $bet_mode_id;
                    $user_bet['bet_date'] = $bet_date;
                    $user_bet['result'] = $result;
                    array_push($user_bets, $user_bet);
                }  
                return $user_bets;
            }

            public function updateUser($name, $mobile,$district, $upazilla, $up, $user_id){
                $stmt = $this->con->prepare("UPDATE user SET name = ?, mobile = ?, district = ?, upazilla = ?, up = ? WHERE user_id = ?");
                $stmt->bind_param("sssssi",$name, $mobile, $district, $upazilla , $up, $user_id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }
            private function getPasswordById($user_id){
                $stmt = $this->con->prepare("SELECT password FROM user WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute(); 
                $stmt->bind_result($password);
                $stmt->fetch(); 
                return $password;
            }
            public function updatePassword($currentpassword, $newpassword, $id){
            $hashed_password = '$2y$10$od9aAP/zUKN0EzdegzjMae.ygTC2JjfcZcEOkHFbAUu';
            
            if(password_verify($currentpassword, $hashed_password)){
                
                $hash_password = password_hash($newpassword, PASSWORD_DEFAULT);
                $stmt = $this->con->prepare("UPDATE user SET password = ? WHERE user_id = ?");
                $stmt->bind_param("si",$hash_password, $id);
                if($stmt->execute())
                    return PASSWORD_CHANGED;
                return PASSWORD_NOT_CHANGED;
            }else{
                return PASSWORD_DO_NOT_MATCH; 
            }
            }
            public function getUserById($user_id){
                $stmt = $this->con->prepare("SELECT user_id, name, username, email, mobile, reference, agent_id, district, upazilla, up, type_id, advanced_balance, trade_balance,rank_id FROM user WHERE user_id = ?");
                $stmt->bind_param("i",$user_id);
                $stmt->execute();
                $stmt->bind_result($user_id, $name, $username, $email, $mobile, $reference, $agent_id, $district, $upazilla, $up, $type_id, $advanced_balance, $trade_balance, $rank_id);
                $users =  array();
                while ($stmt->fetch()) {
                    $user = array();
                    $user['user_id'] = $user_id;
                    $user['name'] = $name;
                    $user['username'] = $username;
                    $user['email'] = $email;
                    $user['mobile'] = $mobile;
                    $user['reference'] = $reference;
                    $user['agent_id'] = $agent_id;
                    $user['district'] = $district;
                    $user['upazilla'] = $upazilla;
                    $user['up'] = $up;
                    $user['type_id'] = $type_id;
                    $user['advanced_balance'] = $advanced_balance;
                    $user['trade_balance'] = $trade_balance;
                    $user['rank_id'] = $rank_id;
                    array_push($users, $user);
                }
                return $users;
            }
            public function getUserByEmail($email){
                $stmt = $this->con->prepare("SELECT user_id, name, username, email, mobile, reference, agent_id, district, upazilla, up, type_id, advanced_balance, trade_balance,rank_id FROM user WHERE email = ?");
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $stmt->bind_result($user_id, $name, $username, $email, $mobile, $reference, $agent_id, $district, $upazilla, $up, $type_id, $advanced_balance, $trade_balance, $rank_id);
            
                $user = array();
                $stmt->fetch();
                $user['user_id'] = $user_id;
                $user['name'] = $name;
                $user['username'] = $username;
                $user['email'] = $email;
                $user['mobile'] = $mobile;
                $user['reference'] = $reference;
                $user['agent_id'] = $agent_id;
                $user['district'] = $district;
                $user['upazilla'] = $upazilla;
                $user['up'] = $up;
                $user['type_id'] = $type_id;
                $user['advanced_balance'] = $advanced_balance;
                $user['trade_balance'] = $trade_balance;
                $user['rank_id'] = $rank_id;
                return $user;
               
            }
            public function createPremiumUser($name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up){

                $stmt = $this->con->prepare("SELECT user_id FROM user WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("SELECT user_id FROM user WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute(); 
                    $stmt->store_result(); 
                    if (!$stmt->num_rows>0) {
                        $stmt = $this->con->prepare("SELECT club_id FROM club WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->store_result();
                        if (!$stmt->num_rows>0) {
                            $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                            $stmt->bind_param("s",$username);
                            $stmt->execute();
                            $stmt->store_result();
                            if (!$stmt->num_rows>0) {
                                $stmt = $this->con->prepare("SELECT user_id FROM user WHERE mobile = ?");
                                $stmt->bind_param("s", $mobile);
                                $stmt->execute(); 
                                $stmt->store_result(); 
                                if (!$stmt->num_rows>0) {
                                    $stmt = $this->con->prepare("INSERT INTO user(name, username, email, mobile, password, reference, agent_id, district, upazilla, up, type_id ) VALUES (?,?,?,?,?,?,?,?,?,?,3)");
                                    $stmt->bind_param("ssssssisss", $name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up);
                                    if ($stmt->execute()){
                                        return DATA_INSERTED;
                                    }
                                    else{
                                        return DATA_INSERTED_FAILED;
                                    }
                                }
                                return MOBILE_DUPLICATE;
                            }
                            return USERNAME_DUPLICATE;
                        }
                        return USERNAME_DUPLICATE;
                    }
                    return USERNAME_DUPLICATE;
                }
                return EMAIL_DUPLICATE;
            }
            public function createUser($name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up, $type_id, $pin_id, $trade_balance){

                $stmt = $this->con->prepare("SELECT user_id FROM user WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("SELECT user_id FROM user WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute(); 
                    $stmt->store_result(); 
                    if (!$stmt->num_rows>0) {
                        $stmt = $this->con->prepare("SELECT club_id FROM club WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->store_result();
                        if (!$stmt->num_rows>0) {
                            $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                            $stmt->bind_param("s",$username);
                            $stmt->execute();
                            $stmt->store_result();
                            if (!$stmt->num_rows>0) {
                                $stmt = $this->con->prepare("SELECT user_id FROM user WHERE mobile = ?");
                                $stmt->bind_param("s", $mobile);
                                $stmt->execute(); 
                                $stmt->store_result(); 
                                if (!$stmt->num_rows>0) {
                                    $stmt = $this->con->prepare("INSERT INTO user(name, username, email, mobile, password, reference, agent_id, district, upazilla, up, type_id, pin_id, trade_balance ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                    $stmt->bind_param("ssssssisssiid", $name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up, $type_id, $pin_id, $trade_balance);
                                    if ($stmt->execute()){
                                        return DATA_INSERTED;
                                    }
                                    else{
                                        return DATA_INSERTED_FAILED;
                                    }
                                }
                                return MOBILE_DUPLICATE;
                            }
                            return USERNAME_DUPLICATE;
                        }
                        return USERNAME_DUPLICATE;
                    }
                    return USERNAME_DUPLICATE;
                }
                return EMAIL_DUPLICATE;
            }
            public function userLogin($email, $password){
                $stmt = $this->con->prepare("SELECT email FROM user WHERE email = ? AND password = ?");
                $stmt->bind_param("ss",$email,$password);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows>0) {
                    return USER_AUTHENTICATED;
                }else{
                    return USER_UNAUTHENTICATED;
                }
/*
                $stmt = $this->con->prepare("SELECT email FROM user WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 
                if ($stmt->num_rows > 0) {
                     $hashed_password = $this->getUsersPasswordByEmail($email); 
                    if(password_verify($password, $hashed_password)){
                        return USER_AUTHENTICATED;
                    }else{
                        return USER_PASSWORD_DO_NOT_MATCH; 
                    }
                 } else{
                    return USER_NOT_FOUND; 
                }
        */
            }

            public function adminLogin($email, $password){
                $stmt = $this->con->prepare("SELECT email FROM admin WHERE email = ? AND password = ?");
                $stmt->bind_param("ss",$email,$password);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows>0) {
                    return USER_AUTHENTICATED;
                }else{
                    return USER_UNAUTHENTICATED;
                }
            }

            public function getAdminByEmail($email){
                $stmt = $this->con->prepare("SELECT admin_id, name, username, email,mobile, district, upazilla,up, total_balance FROM admin WHERE email = ?");
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $stmt->bind_result($admin_id, $name, $username, $email, $mobile, $district,$upazilla,$up,$total_balance);
                $admin = array();
                $stmt->fetch();
                $admin['admin_id'] = $admin_id;
                $admin['name']=$name;
                $admin['username']=$username;
                $admin['email']=$email;
                $admin['mobile']=$mobile;
                $admin['district'] = $district;
                $admin['upazilla'] = $upazilla;
                $admin['up'] = $up;
                $admin['total_balance'] = $total_balance;
                return $admin;
            }
            private function getUsersPasswordByEmail($email){
                $stmt = $this->con->prepare("SELECT password FROM user WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->bind_result($password);
                $stmt->fetch(); 
                return $password; 
            }
            public function getAgentIdByUsername($username){
                $stmt = $this->con->prepare("SELECT agent_id FROM user WHERE username = ?");
                $stmt->bind_param("s",$username);
                $stmt->execute();
                //$stmt->bind_result($agent_id);
                //$stmt->fetch();
                $stmt->store_result();
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                    $stmt->bind_param("s",$username);
                    $stmt->execute();
                    //$stmt->bind_result($agent_id);
                    //$stmt->fetch();
                    $stmt->store_result();
                    if (!$stmt->num_rows>0) {
                        return null;
                    }else{
                        $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->bind_result($agent_id);
                        $stmt->fetch();
                        return $agent_id;
                    }
                }else{
                    $stmt = $this->con->prepare("SELECT agent_id FROM user WHERE username = ?");
                    $stmt->bind_param("s",$username);
                    $stmt->execute();
                    $stmt->bind_result($agent_id);
                    $stmt->fetch();
                    return $agent_id;
                }
                
            }
            public function deleteUser($id){
                $stmt = $this->con->prepare("DELETE FROM user WHERE user_id = ?" );
                $stmt->bind_param("i", $id);
                if($stmt->execute())
                    return true; 
                return false; 
            }

            public function getAllUser(){

            $stmt = $this->con->prepare("SELECT user_id, name, username, email, mobile, password, reference, agent_id, district, upazilla, up, type_id, pin_id, create_date, validity_date, trade_balance, advanced_balance, status, rank_id  FROM user ;");

            $stmt->execute(); 
            $stmt->bind_result( $user_id, $name, $username, $email, $mobile, $password, $reference, $agent_id, $district,   $upazilla, $up, $type_id, $pin_id, $create_date, $validity_date, $trade_balance, $advanced_balance, $status, $rank_id );

            $users = array(); 
            while($stmt->fetch()){ 

                $user = array(); 

                $user['user_id'] = $user_id; 
                $user['name']= $name;
                $user['username'] = $username; 
                $user['email'] = $email; 
                $user['mobile'] = $mobile;
                $user['password'] = $password;
                $user['reference'] = $reference; 
                $user['agent_id'] = $agent_id;
                $user['district'] = $district;
                $user['upazilla'] = $upazilla;
                $user['up'] = $up;
                $user['type_id'] = $type_id;
                $user['pin_id'] = $pin_id;
                $user['create_date'] = $create_date;
                $user['validity_date'] = $validity_date;
                $user['trade_balance'] = $trade_balance;
                $user['advanced_balance'] = $advanced_balance;
                $user['status'] = $status;
                $user['rank_id'] = $rank_id;

                array_push($users, $user);
            }    

            return $users; 
        }
        
//Create Club

           public function createClub($name, $username, $email, $mobile,  $password,$district, $upazilla, $up){
                
                $stmt = $this->con->prepare("SELECT club_id FROM club WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 
                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("SELECT club_id FROM club WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute(); 
                    $stmt->store_result(); 
                    if (!$stmt->num_rows>0) {
                        $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->store_result();
                        if (!$stmt->num_rows>0) {
                            $stmt = $this->con->prepare("SELECT user_id FROM user WHERE username = ?");
                            $stmt->bind_param("s",$username);
                            $stmt->execute();
                            $stmt->store_result();
                            if (!$stmt->num_rows>0) {
                                $stmt = $this->con->prepare("SELECT club_id FROM club WHERE mobile = ?");
                                $stmt->bind_param("s", $mobile);
                                $stmt->execute(); 
                                $stmt->store_result(); 
                                if (!$stmt->num_rows>0) {
                                    $stmt = $this->con->prepare("INSERT INTO club(name, username, email,mobile, password, district, upazilla, up) VALUES (?,?,?,?,?,?,?,?)");
                                    $stmt->bind_param("ssssssss",$name,$username, $email, $mobile, $password, $district, $upazilla, $up);
                                    if ($stmt->execute()) {
                                        return DATA_INSERTED;
                                    }else{
                                        return DATA_INSERTED_FAILED;
                                    }
                                }
                                return MOBILE_DUPLICATE;
                            }
                            return USERNAME_DUPLICATE;
                        }
                        return USERNAME_DUPLICATE;
                    }
                    return USERNAME_DUPLICATE;
                }
                return EMAIL_DUPLICATE;
                
           }


           public function clubLogin($email, $password){
                $stmt = $this->con->prepare("SELECT email FROM club WHERE email = ? AND password = ?");
                $stmt->bind_param("ss",$email,$password);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows>0) {
                    return USER_AUTHENTICATED;
                }else{
                    return USER_UNAUTHENTICATED;
                }
            }


//Update Club


               public function getClubById($id){

                    $stmt = $this->con->prepare("SELECT name, mobile, district, upazilla, up FROM club WHERE club_id = ?");

                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->bind_result($name, $mobile, $district, $upazilla, $up);
                    $clubs = array();
                    while($stmt->fetch()){ 
                        $club = array(); 
                        $club['name'] = $name; 
                        $club['mobile']=$mobile; 
                        $club['district'] = $district; 
                        $club['upazilla'] = $upazilla;
                        $club['up'] = $up;
                        array_push($clubs, $club);
                    }  
                    return $clubs; 
                }

                public function getClubByEmail($email){

                    $stmt = $this->con->prepare("SELECT name,username, email, mobile, district, upazilla, up, total_balance FROM club WHERE email = ?");

                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->bind_result($name, $username, $email, $mobile, $district, $upazilla, $up, $total_balance);
                    $stmt->fetch();
                    $club = array(); 
                    $club['name'] = $name; 
                    $club['username'] = $username;
                    $club['email'] = $email;
                    $club['mobile']=$mobile; 
                    $club['district'] = $district; 
                    $club['upazilla'] = $upazilla;
                    $club['up'] = $up;
                    $club['total_balance'] = $total_balance;
                    return $club;
                }


            public function updateClub($name,$mobile,$district,$upazilla,$up,$id){


                $stmt = $this->con->prepare("UPDATE club SET name = ?, mobile = ?, district = ?, upazilla = ?, up = ? WHERE club_id = ?" );

                $stmt->bind_param("sssssi", $name, $mobile, $district, $upazilla, $up ,$id);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }



















//Delete Club

        /*
            The Delete Operation
            This function will delete the user from database
        */

            public function deleteClub($id){
                $stmt = $this->con->prepare("DELETE FROM agent WHERE club_id = ?" );
                $stmt->bind_param("i", $id);
                if($stmt->execute())
                    return true; 
                return false; 
            }


//Select Club


                 public function getAllClub(){

            $stmt = $this->con->prepare("SELECT club_id, name, username, email, mobile, password, district, upazilla, up,  create_date, total_balance, status  FROM club ;");

            $stmt->execute(); 
            $stmt->bind_result( $club_id, $name, $username, $email, $mobile, $password, $district, $upazilla, $up, 
                $create_date, $total_balance, $status);

            $users = array(); 
            while($stmt->fetch()){ 

                $user = array(); 

                $user['club_id'] = $club_id; 
                $user['name']= $name;
                $user['username'] = $username; 
                $user['email'] = $email; 
                $user['mobile'] = $mobile;
                $user['password'] = $password;
                $user['district'] = $district;
                $user['upazilla'] = $upazilla;
                $user['up'] = $up;
                $user['create_date'] = $create_date;
                $user['total_balance'] = $total_balance;
                $user['status'] = $status;


                array_push($users, $user);
            }    

            return $users; 
        }


                public function getClubIdByUsername( $username){
                    $stmt = $this->con->prepare("SELECT club_id FROM club WHERE username = ?");
                    $stmt->bind_param("s",$username);
                    $stmt->execute();
                    $stmt->bind_result($club_id);
                    $stmt->fetch();
                    return $club_id;
                }
//Create Agent
               public function createAgent($name, $username, $email, $mobile,  $password, $club_id,$district, $upazilla, $up){

                $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 

                if (!$stmt->num_rows>0) {
                    $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute(); 
                    $stmt->store_result(); 
                    if (!$stmt->num_rows>0) {
                        $stmt = $this->con->prepare("SELECT club_id FROM club WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->store_result();
                        if (!$stmt->num_rows>0) {
                            $stmt = $this->con->prepare("SELECT user_id FROM user WHERE username = ?");
                            $stmt->bind_param("s",$username);
                            $stmt->execute();
                            $stmt->store_result();
                            if (!$stmt->num_rows>0) {
                                $stmt = $this->con->prepare("SELECT agent_id FROM agent WHERE mobile = ?");
                                $stmt->bind_param("s", $mobile);
                                $stmt->execute(); 
                                $stmt->store_result(); 
                                if (!$stmt->num_rows>0) {
                                    $stmt = $this->con->prepare("INSERT INTO agent (name, username, email, mobile, password, club_id, district, upazilla, up) VALUES (?,?,?,?,?,?,?,?,?)");
                            
                                    $stmt->bind_param("sssssisss",$name, $username, $email, $mobile, $password, $club_id, $district, $upazilla, $up );

                                    if ($stmt->execute()) {
                                        return DATA_INSERTED;
                                    }else{
                                        return DATA_INSERTED_FAILED;
                                    }
                                }
                                return MOBILE_DUPLICATE;
                            }
                            return USERNAME_DUPLICATE;
                        }
                        return USERNAME_DUPLICATE;
                    }
                    return USERNAME_DUPLICATE;
                }
                return EMAIL_DUPLICATE;
                
           }

                

            public function agentLogin($email, $password){
                $stmt = $this->con->prepare("SELECT email FROM agent WHERE email = ? AND password = ?");
                $stmt->bind_param("ss",$email,$password);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows>0) {
                    return USER_AUTHENTICATED;
                }else{
                    return USER_UNAUTHENTICATED;
                }
            }
           





//Delete Agent

        /*
            The Delete Operation
            This function will delete the user from database
        */

            public function deleteAgent($id){
                $stmt = $this->con->prepare("DELETE FROM agent WHERE agent_id = ?" );
                $stmt->bind_param("i", $id);
                if($stmt->execute())
                    return true; 
                return false; 
            }




//Select Agent

        /*
            The Read Operation
            Function is returning all the users from database
        */

        public function getAllAgent(){

            $stmt = $this->con->prepare("SELECT agent_id, name, username, email, mobile, password, club_id, district, upazilla, up, create_date, total_balance, status FROM agent ;");

            $stmt->execute(); 
            $stmt->bind_result( $agent_id, $name, $username, $email, $mobile, $password, $club_id, $district, $upazilla, $up, $create_date, $total_balance, $status );

            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['agent_id'] = $agent_id; 
                $user['name']= $name;
                $user['username'] = $username; 
                $user['email'] = $email; 
                $user['mobile'] = $mobile;
                $user['password'] = $password; 
                $user['club_id'] = $club_id;
                $user['district'] = $district;
                $user['upazilla'] = $upazilla;
                $user['up'] = $up;
                $user['create_date'] = $create_date;
                $user['total_balance'] = $total_balance;
                $user['status'] = $status;
                array_push($users, $user);
            }
            return $users; 
        }
        public function getAgentByEmail($email){
            $stmt = $this->con->prepare("SELECT agent_id, name, username, email, mobile, club_id, district, upazilla, up, total_balance FROM agent WHERE email = ?");

            $stmt->bind_param("s",$email);
            $stmt->execute(); 
            $stmt->bind_result( $agent_id, $name, $username, $email, $mobile,  $club_id, $district, $upazilla, $up, $total_balance );

            $agent = array(); 
            $stmt->fetch();
            $agent['agent_id'] = $agent_id; 
            $agent['name']= $name;
            $agent['username'] = $username; 
            $agent['email'] = $email; 
            $agent['mobile'] = $mobile;
            $agent['club_id'] = $club_id;
            $agent['district'] = $district;
            $agent['upazilla'] = $upazilla;
            $agent['up'] = $up;
            $agent['total_balance'] = $total_balance;
            return $agent; 
        }

        public function isPinValid($pin, $user_type_id){
            $stmt = $this->con->prepare("SELECT id FROM security_pin WHERE pin = ? AND user_type_id = ? AND used = 0;");
            $stmt->bind_param("si",$pin,$user_type_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows>0) {
                return PIN_VALID;
            }else{
                return PIN_INVALID;
            }
        }
        public function getPinId($pin){
            $stmt = $this->con->prepare("SELECT id FROM security_pin WHERE pin = ? AND used = 0;");
            $stmt->bind_param("s",$pin);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();
            return $id;
        }

 public function createSecuritypin ($pin, $user_type_id){

                $stmt = $this->con->prepare("INSERT INTO security_pin (pin, user_type_id) VALUES (?,?)");
                
                $stmt->bind_param("si",$pin, $user_type_id);

                if ($stmt->execute()) {
                    return DATA_INSERTED;
                }else{
                    return DATA_INSERTED_FAILED;
                }
           }

            public function setPinUsed($pin){
                $stmt = $this->con->prepare("UPDATE security_pin SET used = 1 WHERE pin = ?");
                $stmt->bind_param("s", $pin);
                $stmt->execute();
                $rowCount = $stmt->affected_rows;
                return $rowCount>0;
            }

        //Delete Security-pin

        /*
            The Delete Operation
            This function will delete the user from database
        */

            public function deleteSecuritypin($id){
                $stmt = $this->con->prepare("DELETE FROM security_pin WHERE id = ?" );
                $stmt->bind_param("i", $id);
                if($stmt->execute())
                    return true; 
                return false; 
            }




//Select security-pin


        /*
            The Read Operation
            Function is returning all the users from database
        */

        public function getAllSecuritypin(){

            $stmt = $this->con->prepare("SELECT id, pin, user_type_id, used, validity FROM security_pin;");
            $stmt->execute(); 
            $stmt->bind_result($id, $pin, $user_type_id, $used, $validity );
            $users = array(); 
            while($stmt->fetch()){ 

                $user = array(); 
                $user['id'] = $id; 
                $user['pin']=$pin;
                $user['user_type_id']=$user_type_id; 
                $user['used'] = $used; 
                $user['validity'] = $validity; 

                array_push($users, $user);
            }             
            return $users; 
        }


 
    


            private function isPlaceBet($user_id, $bet_id){
                $stmt = $this->con->prepare("SELECT user_id FROM user_bet WHERE user_id = ? AND bet_id = ?");
                $stmt->bind_param("ii",$user_id, $bet_id);
                $stmt->execute();
                $stmt->store_result();
                return $stmt->num_rows>0;
            }
            private function isEmailExist($email, $table_name){
                $stmt = $this->con->prepare("SELECT email FROM club WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute(); 
                $stmt->store_result(); 
                return $stmt->num_rows > 0;  
            }
            /*private function isUsernameExist($username, $table_name){
                $stmt = $this->con->prepare("SELECT username FROM ? WHERE username = ?");
                $stmt->bind_param("ss",$table_name, $username);
                $stmt->execute();
                $stmt->store_result();
                return $stmt->num_rows>0;
            }*/
            private function isMobileExits($mobile, $table_name){
                $stmt = $this->con->prepare("SELECT mobile FROM ? WHERE mobile = ?");
                $stmt->bind_param("ss",$table_name , $mobile);
                $stmt->execute();
                $stmt->store_result();
                return $stmt->num_rows>0;
            }


        }
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/DbOperations.php';
$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

$app->post('/createshareduser', function(Request $request, Response $response){
    $request_data = $request->getParsedBody(); 
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email'];
    $mobile = $request_data['mobile'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];
    $shared_percent = $request_data['shared_percent'];

    $db = new DbOperations; 
    $result = $db->createSharedUser($name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent);

    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'User created successfully';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Some error occurred';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);    
    }else if($result == DATA_EXISTS){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'User Already Exists';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);    
    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(422);    
});

$app->get('/allsharedusers', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllSharedUsers();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updateshareduser/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody(); 
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email']; 
    $mobile = $request_data['mobile'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];
    $shared_percent = $request_data['shared_percent'];

    $db = new DbOperations; 
    if($db->updateSharedUser($name, $username, $email, $mobile, $district, $upazilla, $up, $shared_percent, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'User Updated Successfully';
        $user = $db->getSharedUserByEmail($email);
        $response_data['user'] = $user; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->delete('/deleteshareduser/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteSharedUser($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'User has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});

$app->post('/addtransaction', function(Request $request, Response $response){
    $request_data = $request->getParsedBody(); 
    $from_username = $request_data['from_username'];
    $to_username = $request_data['to_username'];
    $amount = $request_data['amount'];
    $trans_type = $request_data['trans_type'];
    $trans_charge = $request_data['trans_charge'];
    

    $db = new DbOperations; 
    $result = $db->addTransaction($from_username, $to_username, $amount, $trans_type, $trans_charge);

    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Transaction Request Send';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Transaction Request not Send';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);    
    }   
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(422); 
});

$app->put('/setfromuserseen/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    if ($db->setFromUserSeen($id)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'From user set seen';
        $response->write(json_encode($response_data));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200); 
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'From user already seen';
        $response->write(json_encode($response_data));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200); 
    }
});

$app->put('/settouserseen/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    if ($db->setToUserSeen($id)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'To user set seen';
        $response->write(json_encode($response_data));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200); 
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'To user already seen';
        $response->write(json_encode($response_data));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200); 
    }
});

$app->get('/alltransactions', function(Request $request, Response $response){
    $db = new DbOperations; 
    $transactions = $db->getAllTransactions();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['transactions'] = $transactions; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

//Get User notifications
$app->get('/usernotification/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];

    $db = new DbOperations;
    $notifications = $db->getUserNotification($username);
    $response->write(json_encode($notifications));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});
//Approved  Transaction
$app->put('/appreovedtransaction', function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $amount = $request_data['amount'];
    $to_username = $request_data['to_username'];
    $from_username = $request_data['from_username'];

    $db = new DbOperations;
    $to_username_existing_balance = $db->getUserBalanceByUsername($to_username);
    $to_username_update_balance = $to_username_existing_balance - $amount;
    $updateToUserBalance = $db->updateUserBalance($to_username_update_balance, $to_username);
    if($updateToUserBalance){
        $from_username_existing_balance = $db->getUserBalanceByUsername($from_username);
        $from_username_update_balance = $from_username_existing_balance + $amount;
        $updateFromUserBalance = $db->updateUserBalance($from_username_update_balance, $from_username);
        if ($updateFromUserBalance) {
            $message = array();
           $message['error'] = false;
           $message['message'] = "Transaction successful";
           $response->write(json_encode($message));
           return $response
                        ->withHeader('Content-type','application/json')
                        ->withStatus(200);
        }else{
           $message = array();
           $message['error'] = true;
           $message['message'] = "Transaction failed";
           $response->write(json_encode($message));
           return $response
                        ->withHeader('Content-type','application/json')
                        ->withStatus(200);
       }
    } else{
        $message = array();
           $message['error'] = true;
           $message['message'] = "Transaction failed";
           $response->write(json_encode($message));
           return $response
                        ->withHeader('Content-type','application/json')
                        ->withStatus(200);
    }
    
    $response->write(json_encode($withdraw_result));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});

$app->get('/alldeposittransactions/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations; 
    $transactions = $db->getAllUserDepositTransactions($username);
    $response_data = array();
    //$response_data['error'] = false; 
    //$response_data['transactions'] = $transactions; 
    $response->write(json_encode($transactions));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/allwithdrawtransactions/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations; 
    $transactions = $db->getAllUserWithdrawTransactions($username);
    $response_data = array();
    //$response_data['error'] = false; 
    //$response_data['transactions'] = $transactions; 
    $response->write(json_encode($transactions));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/allbalancetransfertransactions/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations; 
    $transactions = $db->getAllUserBalanceTransferTransactions($username);
    $response_data = array();
    //$response_data['error'] = false; 
    //$response_data['transactions'] = $transactions; 
    $response->write(json_encode($transactions));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/allrequestedtransactions/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations;
    $transactions = $db->getRequestedTransactions($username);
    $response->write(json_encode($transactions));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});


$app->get('/transactions/{username}', function(Request $request, Response $response,array $args){
    $username = $args['username'];
    $db = new DbOperations; 
    $transactions = $db->getTransactionByFromUser($username);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['transactions'] = $transactions; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updatetransacation/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody(); 
    $from_username = $request_data['from_username'];
    $to_username = $request_data['to_username'];
    $amount = $request_data['amount'];
    $trans_type = $request_data['trans_type'];
    $trans_charge = $request_data['trans_charge'];

    $db = new DbOperations; 
    if($db->updateTransaction($from_username, $to_username, $amount, $trans_type, $trans_charge, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Transaction Updated Successfully';
        $transaction = $db->getTransactionById($id);
        $response_data['transaction'] = $transaction; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updatetransacationstatus/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody(); 
    $status = $request_data['status'];
    $db = new DbOperations; 
    if($db->updateTransactionStatus($status, $id)){
        $transaction = $db->getTransactionById($id);
        $response->write(json_encode($transaction));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->delete('/deletetransaction/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteTransaction($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Transaction has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});

$app->post('/addmatch',function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $team1 = $request_data['team1'];
    $team2 = $request_data['team2'];
    $date_time = $request_data['date_time'];
    $tournament = $request_data['tournament'];
    $match_type = $request_data['match_type'];
    $match_format = $request_data['match_format'];

    $db = new DbOperations;
    $result = $db->addMatch($team1, $team2, $date_time,$tournament, $match_type, $match_format);
    if ($result == DATA_INSERTED) {
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Match Added Successfully';
        $match = $db->getInsertedMatch($team1,$team2, $date_time);
        $message['match'] = $match;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Match not added, Please try again.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);
    }elseif ($result == DATA_EXISTS) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Match already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);
    }
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422); 
});
$app->get('/allmatches', function(Request $request, Response $response){
    $db = new DbOperations; 
    $matches = $db->getAllMatches();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['matches'] = $matches; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/allrunningmatch', function(Request $request, Response $response){
    $db = new DbOperations;
    $matches = $db->getRunningMatch();
    $response_data['error'] = false; 
    $response_data['matches'] = $matches; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});
$app->get('/allupcomingmatch', function(Request $request, Response $response){
    $db = new DbOperations;
    $matches = $db->getUpcomingMatch();
    $response_data['error'] = false; 
    $response_data['matches'] = $matches; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});
$app->get('/allfinishmatch', function(Request $request, Response $response){
    $db = new DbOperations;
    $matches = $db->getFinishMatch();
    $response_data['error'] = false; 
    $response_data['matches'] = $matches; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});


$app->put('/updatematch/{id}', function(Request $request, Response $response,array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();
    $team1 = $request_data['team1'];
    $team2 = $request_data['team2'];
    $date_time = $request_data['date_time'];
    $match_type = $request_data['match_type'];
    $match_format = $request_data['match_format'];

    $db = new DbOperations; 
    if($db->updateMatch($team1, $team2, $date_time, $match_type, $match_format, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Match Updated Successfully';
        $match = $db->getMatchById($id);
        $response_data['match'] = $match; 
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  

    }
    
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});

$app->put('/updatematchstatus/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody(); 
    $status = $request_data['status'];
    $db = new DbOperations; 
    if($db->updateMatchStatus($status, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Match Updated Successfully';
        $match = $db->getMatchById($id);
        $response_data['match'] = $match; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->delete('/deletematch/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteMatch($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Match has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});

$app->post('/createbet', function(Request $request, Response $response){
    $request_data = $request->getParsedBody(); 
    $question = $request_data['question'];
    $started_date = $request_data['started_date'];
    $match_id = $request_data['match_id'];
    $bet_mode = $request_data['bet_mode'];

    $db = new DbOperations; 
    $result = $db->createBet($question, $started_date, $match_id, $bet_mode);

    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Bet create successfully';
        $bet = $db->getInsertedBet($question,$match_id);
        $message['bet'] = $bet;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Bet does not created';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);    
    }elseif ($result == DATA_EXISTS) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Bet already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);   
    }   
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(201); 
});

$app->put('/updatebet/{id}', function(Request $request , Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody(); 
    $question = $request_data['question'];
    $match_id = $request_data['match_id'];
    $bet_mode = $request_data['bet_mode'];

    $db = new DbOperations;
    if($db->updateBet($question,$match_id, $bet_mode,$id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Bet Updated Successfully';
        $bet = $db->getBetById($id);
        $response_data['bet'] = $bet; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updatebetresult/{id}',function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $request_data = $request->getParsedBody();
    $result = $request_data['result'];
    $right_answer = $request_data['right_ans'];
    $db = new DbOperations;
    if ($db->updateBetResult($result,$right_answer,$id)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Bet Updated Successfully';
        $bet = $db->getBetById($id);
        $response_data['bet'] = $bet; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'No row affected';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/cancelbet/{id}',function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $request_data = $request->getParsedBody();
    $db = new DbOperations;
    if ($db->cancelBet($id)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Bet Updated Successfully';
        $bet = $db->getBetById($id);
        $response_data['bet'] = $bet; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'No row affected';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});


$app->get('/allbets', function(Request $request, Response $response){
    $db = new DbOperations; 
    $bets = $db->getAllBets();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bets'] = $bets; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/matchbets/{match_id}', function(Request $request, Response $response, array $args){
    $match_id = $args['match_id'];
    $db = new DbOperations; 
    $bets = $db->getAllBetsByMatch($match_id);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bets'] = $bets; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->delete('/deletebet/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteBet($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Bet has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});

$app->post('/setbetrate',function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $bet_id = $request_data['bet_id'];
    $options = $request_data['options'];;
    $rate = $request_data['rate'];
    $user_type_id = $request_data['user_type_id'];
    $bet_mode_id = $request_data['bet_mode_id'];

    $db = new DbOperations;
    $result = $db->setBetRate($bet_id,$options,$rate,$user_type_id,$bet_mode_id);

    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Betrate set successfully';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Betrate does not set';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);    
    }   
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(201); 
});

$app->get('/allbetratesbygroupmatchandbet/{bet_mode}/{user_type_id}', function(Request $request, Response $response, array $args){
    $bet_mode_id = $args['bet_mode'];
    $user_type_id = $args['user_type_id'];
    $db = new DbOperations;
    $array = array();
    $match_array = array();
    $matches = $db->getAllMatches();
    foreach ($matches as $match) {
        $bet_match_array = array();
        $id = $match['id'];
        $bets = $db->getAllBetsByMatchAndBetMode($id,$bet_mode_id);
        foreach ($bets as $bet) {
            $bet_array = array();
            $bet_rate_array = array();
            
            $bet_id = $bet['bet_id'];
            $bet_rates = $db->getBetRateByBetIdAndUserType($bet_id,$user_type_id);
            foreach ($bet_rates as $bet_rate ) {
                array_push($bet_rate_array, $bet_rate);
            }
            
                $bet_array['bet'] = $bet;
                $bet_array['bet_rates'] = $bet_rate_array;
                array_push($bet_match_array, $bet_array);
            
            
        }
        if (!empty($bets)) {
            if (!empty($bet_match_array)) {
               $match_array['match'] = $match;
                $match_array['bets'] = $bet_match_array;
                array_push($array, $match_array);
            }
            
        }
        
    }
    $message = array();
    $message['error'] = true; 
    $message['matches'] = $array;
    $response->write(json_encode($message));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(201); 

});
$app->get('/allbetrates', function(Request $request, Response $response){
    $db = new DbOperations; 
    $bet_rates = $db->getAllBetRate();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bet_rate'] = $bet_rates; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/betratesbybet/{id}', function(Request $request, Response $response,array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $bet_rates = $db->getBetRateByBetId($id);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bet_rate'] = $bet_rates; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/betratesbyid/{id}', function(Request $request, Response $response,array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $bet_rates = $db->getBetRateById($id);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bet_rate'] = $bet_rates; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updatebetrate/{id}', function(Request $request , Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();
    $options = $request_data['options'];
    $rate = $request_data['rate'];

    $db = new DbOperations;
    if($db->updateBetRate($options, $rate, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Betrate Updated Successfully';
        $bet_rate = $db->getBetRateById($id);
        $response_data['betrate'] = $bet_rate; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->put('/updateonlybetrate/{id}', function(Request $request , Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();
    $rate = $request_data['rate'];

    $db = new DbOperations;
    if($db->updateOnlyBetRate($rate, $id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Betrate Updated Successfully';
        $bet_rate = $db->getBetRateById($id);
        $response_data['betrate'] = $bet_rate; 
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  

    }
    
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->delete('/deletebetrate/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $db = new DbOperations;
    if ($db->deleteBetRate($id)) {
        $response_data['error'] = false; 
        $response_data['message'] = 'Betrate has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
});

$app->get('/allbetrateswithdetails', function(Request $request, Response $response){
    $db = new DbOperations; 
    $bet_rates = $db->getAllBetRateWithDetails();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['bet_rate'] = $bet_rates; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->post('/addcommission', function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $comm_rate = $request_data['comm_rate'];
    $amount = $request_data['amount'];
    $username = $request_data['username'];
    $from_user_id = $request_data['from_user_id'];
    $bet_id = $request_data['bet_id'];
    $purpose = $request_data['purpose'];

    $db = new DbOperations;
    $result = $db->addCommission($comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose);
    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Commission added successfully';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Commission does not added';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);    
    }   
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422); 
});

$app->get('/allcommission',function(Request $request, Response $response){
    $db = new DbOperations; 
    $commissions = $db->getAllCommission();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['commissions'] = $commissions; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200); 
});

$app->get('/commissionbyid/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $commission = $db->getCommissionById($id);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['commissions'] = $commission; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200); 
});

$app->put('/updatecommission/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();
    $comm_rate = $request_data['comm_rate'];
    $amount = $request_data['amount'];
    $username = $request_data['username'];
    $from_user_id = $request_data['from_user_id'];
    $bet_id = $request_data['bet_id'];
    $purpose = $request_data['purpose'];

    $db = new DbOperations;

    if ($db->updateCommission($comm_rate,$amount,$username,$from_user_id,$bet_id,$purpose,$id)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Commission Updated Successfully';
        $commission = $db->getCommissionById($id);
        $response_data['commission'] = $commission; 
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  

    }
    
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});

$app->delete('/deletecommission/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $db = new DbOperations;
    if ($db->deleteCommission($id)) {
        $response_data['error'] = false; 
        $response_data['message'] = 'Commission has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
});



$app->post('/userbet', function(Request $request , Response $response){
    $request_data = $request->getParsedBody();
    $user_id = $request_data['user_id'];
    $bet_id = $request_data['bet_id'];
    $bet_option_id = $request_data['bet_option_id'];
    $bet_rate = $request_data['bet_rate'];
    $bet_amount = $request_data['bet_amount'];
    $bet_return_amount = $request_data['bet_return_amount'];
    $bet_mode_id = $request_data['bet_mode_id'];

    $db = new DbOperations;
    $result = $db->addUserBet($user_id, $bet_id,$bet_option_id,$bet_rate,$bet_amount,$bet_return_amount,$bet_mode_id);
    if($result == DATA_INSERTED){
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Bet place successfully';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }else if($result == DATA_INSERTED_FAILED){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Bet does not place';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);    
    }elseif ($result == DATA_EXISTS) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'User already place this bet';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422); 
    }   
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422); 
});

$app->get('/alluserbets',function(Request $request, Response $response){
    $db = new DbOperations;
    $userbets = $db->getAllUserBets();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['userbets'] = $userbets; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200); 
});

$app->get('/alluserbetsbyuserid/{id}',function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $userbets = $db->getUserBetByUserId($id);
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['userbets'] = $userbets; 
    $response->write(json_encode($response_data));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200); 
});



$app->get('/alluserbetsbyagentid/{id}',function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $userbets = $db->getUserBetByAgentId($id);
    $response->write(json_encode($userbets));
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200); 
});

$app->get('/allusersbetsbyclub/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $userbets = $db->getUsersBetByClubId($id);
    $response->write(json_encode($userbets));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});
$app->put('/updateuser/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $mobile = $request_data['mobile'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];

    $db = new DbOperations;
    if($db->updateUser($name, $mobile,$district, $upazilla, $up, $id)){
        $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'User Updated Successfully';
            $user = $db->getUserById($id);
            $response_data['user'] = $user; 
            $response->write(json_encode($response_data));
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
        
        }else{
            $response_data = array(); 
            $response_data['error'] = true; 
            $response_data['message'] = 'Please try again later';
            $response->write(json_encode($response_data));
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
              
        }
});


$app->delete('/deleteuser/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteUser($id)){

        $response_data['error'] = false; 
        $response_data['message'] = 'User has been deleted';    
    }else{

        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});


//Select User


$app->get('/alluser', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllUser();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});
$app->get('/allusers', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllUsers();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

$app->get('/userbyusername/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations;
    $user = $db->getUserByUsername($username);
    $response->write(json_encode($user));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});

$app->put('/updatepassword/{id}', function(Request $request, Response $response,array $args){
        $id = $args['id'];
        $request_data = $request->getParsedBody(); 
        $currentpassword = $request_data['currentpassword'];
        $newpassword = $request_data['newpassword'];
        //$id = $request_data['id']; 

        $db = new DbOperations; 
        $result = $db->updatePassword($currentpassword, $newpassword, $id);
        if($result == PASSWORD_CHANGED){
            $response_data = array(); 
            $response_data['error'] = false;
            $response_data['message'] = 'Password Changed';
            $response->write(json_encode($response_data));
            return $response->withHeader('Content-type', 'application/json')
                            ->withStatus(200);
        }else if($result == PASSWORD_DO_NOT_MATCH){
            $response_data = array(); 
            $response_data['error'] = true;
            $response_data['message'] = 'You have given wrong password';
            $response->write(json_encode($response_data));
            return $response->withHeader('Content-type', 'application/json')
                            ->withStatus(200);
        }else if($result == PASSWORD_NOT_CHANGED){
            $response_data = array(); 
            $response_data['error'] = true;
            $response_data['message'] = 'Some error occurred';
            $response->write(json_encode($response_data));
            return $response->withHeader('Content-type', 'application/json')
                            ->withStatus(200);
        }
    
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(422);  
});
$app->post('/createuser', function(Request $request, Response $response){


    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email'];
    $mobile = $request_data['mobile'];
    $password = $request_data['password'];
    $reference = $request_data['reference'];
    $agent_id = $request_data['agent_id'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];
    $type_id = $request_data['type_id'];
    $pin_id = $request_data['pin_id'];

    //$hash_password = password_hash($password, PASSWORD_DEFAULT);

    $db = new DbOperations;
    $result = $db->createUser($name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up, $type_id, $pin_id);

    if ($result == DATA_INSERTED) {

       $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Create User Successful';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);

    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Please try again';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == MOBILE_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Mobile already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == USERNAME_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Username already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == EMAIL_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'User already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);
});

$app->get('/getuserbalance/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $total_balance = $db->getUserBalanceById($id);
    $response->write(json_encode($total_balance));
    
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});

$app->get('/isuserexists/{username}', function(Request $request, Response $response, array $args){
    $username = $args['username'];
    $db = new DbOperations;
    $result = $db->isUserExist($username);
    if ($result) {
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'User exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }else{
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'User does not exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }
});

$app->post('/userlogin', function(Request $request, Response $response){
        $request_data = $request->getParsedBody(); 
        $email = $request_data['email'];
        $password = $request_data['password'];
        
        $db = new DbOperations; 
        $result = $db->userLogin($email, $password);
        if($result == USER_AUTHENTICATED){
            $user = $db->getUserByEmail($email);
            $response_data = array();
            $response_data['error']=false; 
            $response_data['message'] = 'Login Successful';
            $response_data['user']=$user; 
            $response->write(json_encode($response_data));
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
        }else if($result == USER_UNAUTHENTICATED){
            $response_data = array();
            $response_data['error']=true; 
            $response_data['message'] = 'Email or password does not match';
            $response->write(json_encode($response_data));
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
        }
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(422);    
});
$app->get('/getuserbyemail/{email}',function(Request $request, Response $response, array $args ){
        $db = new DbOperations;
        $email = $args['email'];
        $message = array();
        $user = $db->getUsersByEmail($email);
        $message['user'] = $user;
        $response->write(json_encode($message));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
});
$app->post('/login',function (Request $request, Response $response){
        $request_data = $request->getParsedBody();
        $email = $request_data['email'];
        $password = $request_data['password'];

        $db = new DbOperations;
        $result = $db->login($email, $password);

        if ($result == USER_AUTHENTICATED) {
            $user = $db->getUsersByEmail($email);
            $response_data = array();
            $response_data['error'] = false;
            $response_data['message'] = 'Login Successful';
            $response_data['user'] = $user;
            $response->write(json_encode($response_data));
            return $response
                        ->withHeader('Content-type','application/json')
                        ->withStatus(200);
        }elseif ($result == USER_UNAUTHENTICATED) {
           $response_data = array();
            $response_data['error'] = true;
            $response_data['message'] = 'Email or password is invalid';
            $response->write(json_encode($response_data));
            return $response
                        ->withHeader('Content-type','application/json')
                        ->withStatus(200);
        }
        return $response
                    ->withHeader('Content-type','application/json')
                    ->withStatus(200);
});


$app->post('/adminlogin', function(Request $request, Response $response){
        $request_data = $request->getParsedBody(); 
        $email = $request_data['email'];
        $password = $request_data['password'];
        
        $db = new DbOperations; 
        $result = $db->adminLogin($email, $password);
        if($result == USER_AUTHENTICATED){
            $user = $db->getAdminByEmail($email);
            $response_data = array();
            $response_data['error']=false; 
            $response_data['message'] = 'Login Successful';
            $response_data['admin']=$user; 
            $response->write(json_encode($response_data));
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
        }else if($result == USER_UNAUTHENTICATED){
            $response_data = array();
            $response_data['error']=true; 
            $response_data['message'] = 'Email or password does not match';
            $response->write(json_encode($response_data));
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
        }
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(422);    
});

$app->post('/createpremiumuser', function(Request $request, Response $response){


    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email'];
    $mobile = $request_data['mobile'];
    $password = $request_data['password'];
    $reference = $request_data['reference'];
    $agent_id = $request_data['agent_id'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];

    //$hash_password = password_hash($password, PASSWORD_DEFAULT);

    $db = new DbOperations;
    $result = $db->createPremiumUser($name, $username, $email, $mobile, $password, $reference, $agent_id, $district, $upazilla, $up);

    if ($result == DATA_INSERTED) {

       $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Create User Successful';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);

    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Please try again';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == MOBILE_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Mobile already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == USERNAME_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Username already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == EMAIL_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'User already exists';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(201);

});

$app->get('/agentbyid/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $user = $db->getAgentById($id);
    if (!empty($user)) {
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Agent has exists';
        $message['user'] = $user;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }else{
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Agent does not exists.';
        $message['user'] = $user;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }
});

$app->get('/clubbyid/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations;
    $user = $db->getClubById($id);
    if (!empty($user)) {
        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Club has exists';
        $message['user'] = $user;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }else{
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Club does not exists.';
        $message['user'] = $user;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }
});

//Get Admin
$app->get('/getadmin', function(Request $request, Response $response){
    $db = new DbOperations;
    $admin = $db->getAdmin();
    $response->write(json_encode($admin));
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});



$app->get('/getagentidbyreference/{reference}', function(Request $request, Response $response, array $args){
    $reference = $args['reference'];
    $db = new DbOperations;
    $agent_id = $db->getAgentIdByUsername($reference);
    if ($agent_id!=null) {
        $message = array();
        $message['error'] = false;
        $message['message'] = 'Reference username is valid';
        $message['agent_id'] = $agent_id;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type','application/json')
                    ->withStatus(200);
    }else{
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Reference username is invalid';
        $message['agent_id'] = $agent_id;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type','application/json')
                    ->withStatus(200);
    }
});


//Create Agent 

$app->post('/createagent', function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email'];
    $mobile = $request_data['mobile'];
    $password = $request_data['password'];
    $club_id = $request_data['club_id'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];

    $db = new DbOperations;
    $result = $db->createAgent($name, $username, $email, $mobile,  $password, $club_id, $district, $upazilla, $up);


    if ($result == DATA_INSERTED) {
       $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Create Agent Successful';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Agent can\'t created, Please try again';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == MOBILE_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Mobile already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == USERNAME_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Username already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == EMAIL_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Agent already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(201);  

});


$app->post('/agentlogin',function(Request $request,Response $response){
    $request_data = $request->getParsedBody();
    $email = $request_data['email'];
    $password = $request_data['password'];

    $db = new DbOperations;

    $result = $db->agentLogin($email, $password);

    if ($result == USER_AUTHENTICATED) {
        $response_data = array();
        $agent = $db->getAgentByEmail($email);
        $response_data['error']=false; 
        $response_data['message'] = 'Login Successful';
        $response_data['agent'] = $agent;
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }elseif ($result == USER_UNAUTHENTICATED) {
        $response_data = array();
        $response_data['error']=true; 
        $response_data['message'] = 'Email or Password does not match';
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }else{
        $response_data = array();
        $response_data['error']=true; 
        $response_data['message'] = 'Email or Password does not match';
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);   
});






//Delete Agent


$app->delete('/deleteagent/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteAgent($id)){

        $response_data['error'] = false; 
        $response_data['message'] = 'Agent has been deleted';    
    }else{
        
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});




//Select Agent


$app->get('/allagent', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllAgent();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});

//Create Club

$app->post('/createclub', function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $username = $request_data['username'];
    $email = $request_data['email'];
    $mobile = $request_data['mobile'];
    $password = $request_data['password'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];

    $db = new DbOperations;
    $result = $db->createClub($name, $username, $email, $mobile, $password, $district, $upazilla, $up);

    if ($result == DATA_INSERTED) {
       $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Create Club Successful';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Please try again';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == MOBILE_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Mobile already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == USERNAME_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Username already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }elseif ($result == EMAIL_DUPLICATE) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Club already exists.';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);
    }
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(201);    

});

$app->post('/clublogin',function(Request $request,Response $response){
    $request_data = $request->getParsedBody();
    $email = $request_data['email'];
    $password = $request_data['password'];

    $db = new DbOperations;

    $result = $db->clubLogin($email, $password);

    if ($result == USER_AUTHENTICATED) {
        $club = $db->getClubByEmail($email);
        $response_data = array();
        $response_data['error']=false; 
        $response_data['message'] = 'Login Successful';
        $response_data['club'] = $club;
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }elseif ($result == USER_UNAUTHENTICATED) {
        $response_data = array();
        $response_data['error']=true; 
        $response_data['message'] = 'Email or Password does not match';
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }else{
        $response_data = array();
        $response_data['error']=true; 
        $response_data['message'] = 'Email or Password does not match';
        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    
    }
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);   
});


$app->get("/clubidbyusername/{username}",function(Request $request , Response $response,array $args){
    $username = $args['username'];
    $db = new DbOperations;
    $club_id = $db->getClubIdByUsername($username);
    if ($club_id!=null) {
        $message = array();
        $message['error'] = false;
        $message['message'] = "Club id found";
        $message['club_id'] = $club_id;
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type','application/json')
                    ->withStatus(200);
    }else{
        $message = array();
        $message['error'] = true;
        $message['message'] = "Club id not found";
        $message['club_id'] = '';
        $response->write(json_encode($message));
        return $response
                    ->withHeader('Content-type','application/json')
                    ->withStatus(200);
    }
});


//Update-club


$app->put('/updateclub/{id}',  function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $request_data = $request->getParsedBody();

    $name = $request_data['name'];
    $mobile = $request_data['mobile'];
    $district = $request_data['district'];
    $upazilla = $request_data['upazilla'];
    $up = $request_data['up'];

    $db = new DbOperations;

    if ($db->updateClub($name, $mobile, $district, $upazilla, $up,$id)) {

        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Club Updated Successfully';
        $club = $db->getClubById($id);
        $response_data['club'] = $club; 
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  
        
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);  

    }
    
    return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
});



//Delete club


$app->delete('/deleteclub/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteClub($id)){

        $response_data['error'] = false; 
        $response_data['message'] = 'Agent has been deleted';    
    }else{
        
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});




//Select club




$app->get('/allclub', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllClub();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});


$app->post('/ispinvalid',function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $pin = $request_data['pin'];
    $user_type_id = $request_data['user_type_id'];

    $db = new DbOperations;
    $result = $db->isPinValid($pin, $user_type_id);
    if ($result == PIN_VALID) {
        $message = array();
        $message['error'] = false;
        $message['message'] = 'Pin is valid';
        $pin = $db->getPinId($pin);
        $message['pin'] = $pin;
        $response->write(json_encode($message));
        return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
    } elseif ($result == PIN_INVALID) {
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Pin is invalid';
        $response->write(json_encode($message));
        return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
    }
    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});


//Create Security-Pin 

$app->post('/createsecuritypin', function(Request $request, Response $response){

    $request_data = $request->getParsedBody();
    $pin = $request_data['pin'];
    $user_type_id = $request_data['user_type_id'];
   

    $db = new DbOperations;
    $result = $db->createSecuritypin($pin, $user_type_id);

    if ($result == DATA_INSERTED) {
       $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Create Security-Pin Successful';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);
    }elseif ($result == DATA_INSERTED_FAILED) {
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Please try again';
        $response->write(json_encode($message));
        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);

});


$app->put('/setpinused', function(Request $request, Response $response){
    $request_data = $request->getParsedBody();
    $pin = $request_data['pin'];
    $db = new DbOperations;
    if ($db->setPinUsed($pin)) {
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Pin set used successfully';
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'No row affected';
        $response->write(json_encode($response_data));
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
    }
    return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
});

//Delete Security-Pin


$app->delete('/deletesecuritypin/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];
    $db = new DbOperations; 
    $response_data = array();
    if($db->deleteSecuritypin($id)){

        $response_data['error'] = false; 
        $response_data['message'] = 'Security-Pin has been deleted';    
    }else{
        
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});



//Select Security-pin


$app->get('/allsecuritypin', function(Request $request, Response $response){
    $db = new DbOperations; 
    $users = $db->getAllSecuritypin();
    $response_data = array();
    $response_data['error'] = false; 
    $response_data['users'] = $users; 
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
});


function haveEmptyParameters($required_params, $request, $response){
    $error = false; 
    $error_params = '';
    $request_params = $request->getParsedBody(); 
    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true; 
            $error_params .= $param . ', ';
        }
    }
    if($error){
        $error_detail = array();
        $error_detail['error'] = true; 
        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error; 
}

$app->run();
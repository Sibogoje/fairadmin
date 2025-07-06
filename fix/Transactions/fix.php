<?php

// Database connection details
$db_config = [
    'host' => 'srv1212.hstgr.io',
    'user' => 'u747325399_fairfix',
    'password' => 'Fairfix2',
    'database' => 'u747325399_fairfix'
];

try {
    // Connect to the database
    $connection = mysqli_connect($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']);
    if (!$connection) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    // Function to execute SQL queries
    function execute_query($connection, $query, $params = []) {
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . mysqli_error($connection));
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing query: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
    }

    // Extract transactions from tblmemberaccounts between 2017-08-01 and 2017-08-31
    $query = "SELECT * FROM tblmemberaccountsMONDAY2 WHERE TransactionDate BETWEEN '2025-03-01' AND '2025-03-31' ORDER BY TransactionDate";
    $transactions = execute_query($connection, $query);
    if (empty($transactions)) {
        throw new Exception("No transactions returned from the query.");
    }

    // Process transactions based on TransactionTypeID
    foreach ($transactions as $i => $transaction) {
        try {
            $transactionTypeID = $transaction['TransactionTypeID'];
            $memberID = $transaction['memberID'];
            $transactionDate = $transaction['TransactionDate'];
            $amount = $transaction['Amount'];
            $credit = $transaction['Credit'];
            $details = $transaction['Details'];
            $comments = $transaction['Comments'];

            echo "member  for ".$memberID."  ". ($i + 1) . "/" . count($transactions) . ": Type $transactionTypeID\n";

            switch ($transactionTypeID) {
                case 1:
                case 2:
                case 9:
                    handleType129($connection, $transaction);
                    break;
                case 4:
                    handleType4($connection, $transaction);
                    break;
                case 3:
                    handleType3($connection, $transaction);
                    break;
                case 10:
                    handleType10($connection, $transaction);
                    break;
                case 8:
                    handleType8($connection, $transaction);
                    break;
                case 7:
                    handleType7($connection, $transaction);
                    break;
                case 6:
                    handleType6($connection, $transaction);
                    break;
            }

            if (($i + 1) % 100 == 0) {
                echo "Committed " . ($i + 1) . " transactions so far.\n";
                mysqli_commit($connection);
            }
        } catch (Exception $e) {
            echo "Error processing transaction " . ($i + 1) . ": " . $e->getMessage() . "\n";
        }
    }

    mysqli_commit($connection);
    echo "All transactions processed successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($connection)) {
        mysqli_close($connection);
        echo "Database connection closed.\n";
    }
}

function handleType129($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    $transactionTypeID = $transaction['TransactionTypeID'];
    $memberID = $transaction['memberID'];
    $details = $transaction['Details'];
    $credit = $transaction['Credit'];
    $amount = $transaction['Amount'];
    $comments = $transaction['Comments'];

    if ($transactionTypeID == 9) {
        $result = execute_query($connection, "SELECT NewBalance FROM balances WHERE memberID = ?", [$memberID]);
        $startingBalance = $result[0]['NewBalance'];
        $newBalance = $startingBalance + $amount;
    } else {
        $startingBalance = $transaction['StartingBalance'];
        $newBalance = $transaction['NewBalance'];
    }

    $query = "INSERT INTO tblmemberaccounts 
              (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    execute_query($connection, $query, [$transactionDate, $transactionTypeID, $memberID, $details, $credit, $startingBalance, $amount, $newBalance, $comments]);
}

function handleType4($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    $memberID = $transaction['memberID'];
    $details = $transaction['Details'];
    $credit = $transaction['Credit'];
    $amount = $transaction['Amount'];
    $comments = $transaction['Comments'];

    $result = execute_query($connection, "SELECT NewBalance FROM balances WHERE memberID = ?", [$memberID]);
    $startingBalance = $result[0]['NewBalance'];
    $newBalance = $startingBalance - $amount;

    if ($newBalance < 100) {
        $terminationAmount = $amount * 0.01;
        execute_query($connection, 
            "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, 11, ?, ?, 0, ?, ?, 0, ?)",
            [$transactionDate, $memberID, "Termination Fee", $startingBalance, $terminationAmount, "Termination due to insufficient balance"]
        );
        execute_query($connection, "UPDATE tblmembers SET `Terminated` = 1, TerminationDate = '$transactionDate' WHERE MemberID = ?", [$memberID]);
    } else {
        execute_query($connection, 
            "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$transactionDate, 4, $memberID, $details, $credit, $startingBalance, $amount, $newBalance, $comments]
        );

        $transactionFee = $amount * 0.02;
        $newBalance -= $transactionFee;
        execute_query($connection, 
            "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, 5, ?, 'Transaction Fee', 0, ?, ?, ?, ?)",
            [$transactionDate, $memberID, $newBalance + $transactionFee, $transactionFee, $newBalance, "Fee for Adhoc payment"]
        );
    }
}

function handleType3($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    $memberID = $transaction['memberID'];
    $details = $transaction['Details'];
    $credit = $transaction['Credit'];
    $comments = $transaction['Comments'];

    $result = execute_query($connection, "SELECT FixedPaymentAmount FROM member_fees WHERE memberID = ?", [$memberID]);
    $fixedPaymentAmount = $result[0]['FixedPaymentAmount'];

    if ($fixedPaymentAmount > 0) {
        $result = execute_query($connection, "SELECT NewBalance FROM balances WHERE memberID = ?", [$memberID]);
        $startingBalance = $result[0]['NewBalance'];

        if ($startingBalance >= $fixedPaymentAmount) {
            $newBalance = $startingBalance - $fixedPaymentAmount;
            execute_query($connection, 
                "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$transactionDate, 3, $memberID, $details, $credit, $startingBalance, $fixedPaymentAmount, $newBalance, $comments]
            );

            $transactionFee = $fixedPaymentAmount * 0.02;
            $newBalance -= $transactionFee;
             if ($startingBalance > 100){
            execute_query($connection, 
                "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, 5, ?, 'Transaction Fee', 0, ?, ?, ?, ?)",
                [$transactionDate, $memberID, $newBalance + $transactionFee, $transactionFee, $newBalance, "Fee for scheduled payment"]
            );
             }
        }
    }
}

function handleType10($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    $memberID = $transaction['memberID'];
    $details = $transaction['Details'];
    $credit = $transaction['Credit'];
    $amount = $transaction['Amount'];
    $comments = $transaction['Comments'];

    $result = execute_query($connection, "SELECT NewBalance FROM balances WHERE memberID = ?", [$memberID]);
    $startingBalance = $result[0]['NewBalance'];
    $newBalance = ($credit == '1') ? $startingBalance + $amount : $startingBalance - $amount;
    
     if ($startingBalance > 100){

    execute_query($connection, 
        "INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [$transactionDate, 10, $memberID, $details, $credit, $startingBalance, $amount, $newBalance, $comments]
    );
     }
}

function handleType8($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];

    if (date('d', strtotime($transactionDate)) == date('t', strtotime($transactionDate))) {
        $result = execute_query($connection, "SELECT amount FROM interests WHERE date_received = ?", [$transactionDate]);
        $interestAmount = $result[0]['amount'];
        
        //echo "Interest is   ".$interestAmount;

        $result = execute_query($connection, "SELECT SUM(NewBalance) as TotalBalance FROM balances WHERE NewBalance > 0");
        $totalBalance = $result[0]['TotalBalance'];

        if ($totalBalance > 0) {
            $eligibleMembers = execute_query($connection, 
                "SELECT memberID, NewBalance FROM balances 
                WHERE NewBalance > 0 
                AND memberID NOT IN (
                    SELECT memberID 
                    FROM tblmemberaccounts 
                    WHERE TransactionTypeID = 8 
                    AND TransactionDate = ?
                )",
                [$transactionDate]
            );

            $insertQuery = "INSERT INTO tblmemberaccounts 
                           (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments)
                           VALUES (?, 8, ?, 'Interest Allocated', '1', ?, ?, ?, 'Interest Allocation')";

            foreach ($eligibleMembers as $member) {
                $amount = ($member['NewBalance'] / $totalBalance) * $interestAmount;
                $newBalance = $member['NewBalance'] + $amount;
                 if ($member['NewBalance'] > 100){
                execute_query($connection, $insertQuery, 
                    [$transactionDate, $member['memberID'], $member['NewBalance'], round($amount, 2), round($newBalance, 2)]
                );
                 }
            }
        }
    }
}

function handleType7($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    $percent = 1.5 / 100;
    $daysInMonth = date('t', strtotime($transactionDate));

    $eligibleMembers = execute_query($connection, 
        "SELECT memberID, NewBalance FROM balances
        WHERE NewBalance > 10
        AND memberID NOT IN (
            SELECT memberID 
            FROM tblmemberaccounts 
            WHERE TransactionTypeID = 7 
            AND TransactionDate = ?
        )",
        [$transactionDate]
    );

    $insertQuery = "INSERT INTO tblmemberaccounts
                   (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments)
                   VALUES (?, 7, ?, 'Admin Fee', '0', ?, ?, ?, 'Monthly Admin Fee')";

    foreach ($eligibleMembers as $member) {
        $amount = round($member['NewBalance'] * $percent / 365 * $daysInMonth, 2);
        $newBalance = round($member['NewBalance'] - $amount, 2);
        
         if ($member['NewBalance'] > 100){
        execute_query($connection, $insertQuery, 
            [$transactionDate, $member['memberID'], $member['NewBalance'], $amount, $newBalance]
        );
         }
    }
}

function handleType6($connection, $transaction) {
    $transactionDate = $transaction['TransactionDate'];
    
    // Check if this transaction type has already been processed for this month
    $monthStart = date('Y-m-01', strtotime($transactionDate));
    $monthEnd = date('Y-m-t', strtotime($transactionDate));
    
    $alreadyProcessed = execute_query($connection, 
        "SELECT COUNT(*) as count FROM tblmemberaccounts 
         WHERE TransactionTypeID = 6 
         AND TransactionDate BETWEEN ? AND ?", 
        [$monthStart, $monthEnd]
    );

    if ($alreadyProcessed[0]['count'] > 0) {
        echo "Fixed Monthly Fee (Type 6) already processed for " . date('F Y', strtotime($transactionDate)) . ". Skipping.\n";
        return;
    }

    $eligibleMembers = execute_query($connection, "SELECT memberID, NewBalance FROM balances WHERE NewBalance > 10");

    $insertQuery = "INSERT INTO tblmemberaccounts 
                   (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments)
                   VALUES (?, 6, ?, 'Fixed Monthly Fee', '0', ?, 10, ?, 'Monthly Fixed Fee')";

    foreach ($eligibleMembers as $member) {
        $newBalance = $member['NewBalance'] - 10;
        
        if ($member['NewBalance'] > 100){
        execute_query($connection, $insertQuery, 
            [$transactionDate, $member['memberID'], $member['NewBalance'], $newBalance]
        );
        }
        
        
    }

   // echo "Processed Fixed Monthly Fee (Type 6) for " . count($eligibleMembers) . " members.\n";
}

?>
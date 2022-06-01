<?php

declare(strict_types = 1);

// Your Code

function getTransacionFiles(string $dirPath): array
{
    $files = [];
    foreach(scandir($dirPath) as $file){
        if (is_dir($file)){
            continue;
        }
        $files[] = $dirPath . $file;
        
    }
    return $files;
}

function getTransactions(string $fileName, ?callable $transationHandler = null): array {
    if (! file_exists($fileName)){
        trigger_error('File"'. $fileName . '" doesnot exist' , E_USER_ERROR);
    }
    $file = fopen($fileName, 'r');
    fgetcsv($file);
    $transations = [];
    while(($transation = fgetcsv($file)) !== false){
        if($transationHandler !== null){
            $transation = $transationHandler($transation);
        }
        // $transations[] = extractTransaction($transation);
        $transations[] = $transation;

    }
    return $transations;
}

function extractTransaction(array $transationRow): array {
    [$date, $checkNumber, $description, $amount] = $transationRow;
    $amount = (float) str_replace(['$', ','], '', $amount);
    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount
    ];
}

function calculateTotals(array $transactions): array {
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];
    foreach($transactions as $transaction){
        $totals['netTotal'] += $transaction['amount'];
        if($transaction['amount'] >= 0){
            $totals['totalIncome'] += $transaction['amount'];
        }else{
            $totals['totalExpense'] += $transaction['amount'];
        }
    }
    return $totals;
}
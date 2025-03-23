<?php

$companies_filename = 'Companies_Export_08-12-2024.csv';
$companies = csv_to_array($companies_filename);

$companies_list = [];
foreach($companies as $company){
    if(!isset($companies_list[$company['Name']])) {
        $companies_list[$company['Name']] = $company['Company ID (REQUIRED)'];
    }
}
<?php

function putRequestToSQL8($requestSQLOriginal){
    $array = [];
    $requestSQL = $requestSQLOriginal;

    // All selectors are initially set to false.
    $select = false;
    $update = false;
    $insertinto = false;
    $delete = false;

    //Determine the selector used in the query and remove it
    if (str_contains($requestSQL, "SELECT ")) 
    {
        $select = true; 
        $requestSQL = str_replace("SELECT ", "",$requestSQL);
    }
    if (str_contains($requestSQL, "UPDATE ")) 
    {
        $update = true;
        $requestSQL = str_replace("UPDATE ", "",$requestSQL);
    }    
    if (str_contains($requestSQL, "INSERT INTO ")) 
    {
        $insertinto = true;
        $requestSQL = str_replace("INSERT INTO ", "",$requestSQL);
    }
    if (str_contains($requestSQL, "DELETE ")) 
    {
        $delete = true;
        $requestSQL = str_replace("DELETE ", "",$requestSQL);
    }

    //Retrieve the position of 'FROM' = length
    $pos = strpos($requestSQL, " FROM", 0);

    //Retrieve everything from 'FROM' to the end of the query
    $requestSQLEnd = substr($requestSQL, $pos);

    //Truncate starting from the position of 'FROM' and retrieve everything from the beginning up to 'FROM'
    $requestSQL = substr($requestSQL, 0, $pos);

    //replace " " with ""
    $requestSQL = str_replace(" ", "",$requestSQL);

    // If it already contains back quotes or '*', return the original query.
    if (str_contains($requestSQL, "*")){
        return $requestSQLOriginal;

    }else{
        if (str_contains($requestSQL, "`")){
            return $requestSQLOriginal;
            
        }else{ // Otherwise, add backquotes for each value
            $array = explode(",", $requestSQL);
    
            $column = "";
            
            foreach ($array as &$word){
                $column .= "`" .  $word . "`,";
            }
            
            //Remove the last character, which is ','
            $column = rtrim($column, ",");
            
            // Reconstruct the initial query
            if ($select == true) $column = "SELECT " . $column . $requestSQLEnd;
            if ($update == true) $column = "UPDATE " . $column . $requestSQLEnd;
            if ($insertinto == true) $column = "INSERT INTO " . $column . $requestSQLEnd;
            if ($delete == true) $column = "DELETE " . $column . $requestSQLEnd;

            return $column; 
    
        }
    }
        
}

//exemple :
// $result = putRequestToSQL8("SELECT views, name, age FROM t_users WHERE id=?");
// echo $result;


?>
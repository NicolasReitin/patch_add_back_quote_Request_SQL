<?php

function putRequestToSQL8($requestSQLOriginal){
    $array = [];
    $requestSQL = $requestSQLOriginal;

    // tous les selector sont false au départ
    $select = false;
    $update = false;
    $insertinto = false;
    $delete = false;

    //détermine le selector utilisé dans la requête et l'efface
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

    //récupère la place de FROM = lenght
    $pos = strpos($requestSQL, " FROM", 0);

    //rècupère tous à partir de FROM jusqu'à la fin de la requete
    $requestSQLEnd = substr($requestSQL, $pos);

    //Tronque à partir de la valeur de la position de" FROM" et récupère le début jusqu'à "FROM"
    $requestSQL = substr($requestSQL, 0, $pos);

    //remplace " " par ""
    $requestSQL = str_replace(" ", "",$requestSQL);

    // Si contient déjà des back quotes ou "*", renvoie la requête original
    if (str_contains($requestSQL, "*")){
        return $requestSQLOriginal;

    }else{
        if (str_contains($requestSQL, "`")){
            return $requestSQLOriginal;
            
        }else{ // sinon ajoute des back quote pour chaque valeur
            $array = explode(",", $requestSQL);
    
            $column = "";
            
            foreach ($array as &$word){
                if ($word === "") break;
                if ($word != 'null'){
                    $column .= "`" .  $word . "`,";
                }
                else{
                    $column .= $word . ",";
                }
            }
            
            //enlève le dernier caractère à savoir ","
            $column = rtrim($column, ",");
            
            // reconstruit la requete initiale
            if ($select == true) $column = "SELECT " . $column . $requestSQLEnd;
            if ($update == true) $column = "UPDATE " . $column . $requestSQLEnd;
            if ($insertinto == true) $column = "INSERT INTO " . $column . $requestSQLEnd;
            if ($delete == true) $column = "DELETE " . $column . $requestSQLEnd;
            
            // echo $column;
            return $column; 
    
        }
    }
        
}

// $result = putRequestToSQL8("DELETE views, ar, vr FROM t_users WHERE id=? LIMIT 1");
// echo $result;


?>
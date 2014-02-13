<?php

    require 'chainquerybuilder.class.php';

    $a = new ChainQueryBuilder;
    $q = $a
            ->update('table')
            ->set(array('name' => 'Arthur', 'sirname' => 'Borisow'))
            ->where('userId', '=', 1)
            ->orWhere('name', '=', 'Arthur')
            ->build();

    $b = new ChainQueryBuilder;
    $q2 = $b
            ->select(array('name', 'sirname'))
            ->addField('username')
            ->from('users')
            ->where('userId', '=', 1)
            ->build();
            
    $c = new ChainQueryBuilder;
    $q3 = $c
            ->select('name', 'sirname', 'username')
            ->from('users')
            ->where('userId', 'in', array(1, 3, 4, 5))
            ->andWhere('username', 'in', array('Arthur', 'Vova'))
            ->groupBy('name')
            ->orderBy('userId', 'sirName')
            ->order('ASC')
            ->offset(10)
            ->limit(5)
            ->build();
            
    $d = new ChainQueryBuilder;
    $q4 = $d
            ->select('t1.name', 't2.sirname')
            ->from('names as t1')
            ->leftJoin('sirnames as t2')
            ->using('userId')
            ->build();
    
    $e = new ChainQueryBuilder;
    $q5 = $e
            ->select('names.name', 'sirnames.sirname')
            ->from('names')
            ->leftJoin('sirnames')
            ->on('names.userId', '=', 'sirnames.userName')
            ->andOn('names.userName', '<>', 'sirnames.userSirname')
            ->where('names.userName', '=', 'Arthur')
            ->build();
    
    $f = new ChainQueryBuilder;
    $q6 = $f
            ->select('names.name', 'sirnames.sirname')
            ->from('names')
            ->leftJoin('sirnames')
            ->using('userId')
            ->where('names.userName', '=', 'Arthur')
            ->build();
//AND so on with SELECT, DELETE, UPDATE and INSERT (see the class code)
// Don't forget to change the method _sanitizeValue!!!!!'

?>
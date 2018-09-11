<?php

/*Criação de função para calcular o numero de pessoas que cada usuário cadastrou
Função Baseada na listar porem ela ira contar quantos filhos essa pessoa tem

____________________________________________________________________________________________*/
function calcular_cadastros($id,$limite){
    $lista =array(); 
    global $pdo;
    
    $sql = $pdo->prepare("SELECT * FROM tb_usuarios WHERE id_pai = :id");
        
    $sql->bindValue(":id", $id);
    $sql->execute();
    $filhos =0;
    
    
    if($sql->rowCount() > 0){
        $lista = $sql->fetchAll(PDO::FETCH_ASSOC); //armazenando os results em lista
        
        $filhos = $sql->rowCount(); // aqui estamos pegando a quantidade de filhos direto que cada usuario tem
        
        foreach($lista as $chave => $usuario) { 
             //ir de usuário a usuário e pegar os filhos
              if($limite > 0) {
                  
                  $filhos += calcular_cadastros($usuario['id'], $limite-1);                              //Aqui estamos acrescentando
                  
//                 $lista[$chave]['filhos'] = listar($usuario['id'], $limite-1);  //aqui armazenamos os filhos e fazemos um decremento($limite se sencontra na config)      
                                                                                     
                     
                }
             }
        }  
    
    return $filhos;
    
    }



/*Criação de função listar o numero de pessoas que cada usuário cadastrou diretamente


____________________________________________________________________________________________*/

function listar($id,$limite){
    $lista =array(); 
    global $pdo;
    
    $sql = $pdo->prepare("SELECT 
    tb_usuarios.id, tb_usuarios.id_pai, tb_usuarios.patente, tb_usuarios.nome, tb_patente.nome as p_nome
    FROM tb_usuarios 
    LEFT JOIN tb_patente ON tb_patente.id = tb_usuarios.patente
    WHERE tb_usuarios.id_pai = :id");
        
    $sql->bindValue(":id", $id);
    $sql->execute();
    if($sql->rowCount() > 0){
        $lista = $sql->fetchAll(PDO::FETCH_ASSOC); //armazenando os results em lista
        
        foreach($lista as $chave => $usuario) { 
             //ir de usuário a usuário e pegar os filhos
            $lista[$chave]['filhos'] = array();
              if($limite > 0) {
                 $lista[$chave]['filhos'] = listar($usuario['id'], $limite-1);  //aqui armazenamos os filhos e fazemos um decremento($limite se sencontra na config)      
                                                                                     
                     
                }
             }
        }  
    
    return $lista;
    
    }

function exibir($array) {
        echo '<ul>';
        foreach($array as $usuario) {
              echo '<li>';
            echo $usuario['nome'].' ('.$usuario['p_nome'].')';  // estamos apresentando os nomes do usuários e concatenando com select com Inner JOIN usando p_nome para apresentar as patenter
            
            //precisamos fazer uma verificação para exibir os filhos. 
            if(count($usuario['filhos']) > 0){
                exibir($usuario['filhos']);
                
            }
            
            
            echo '</li>';
        }
          echo '</ul>';
    }
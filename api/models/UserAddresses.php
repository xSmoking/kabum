<?php

class UserAddresses
{
    private $table = "user_addresses";

    public $id;
    public $user_id;
    public $zip;
    public $address;
    public $number;
    public $complement;
    public $district;
    public $city;
    public $state;

    /**
     * Retorna um array com todos os endereços
     *
     * @return array()
     */
    public function show()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = Database::connection()->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * Retorna um array com os endereços de um usuário específico
     *
     * @param int $user_id
     * @return array
     */
    public function showFromUser($user_id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id=:user_id";
        $stmt = Database::connection()->prepare($query);
        $user_id = htmlspecialchars($user_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * Busca um endereço pelo ID
     * 
     * @param int $id
     * @return object
     */
    public function find($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id=:id";
        $stmt = Database::connection()->prepare($query);
        $this->id = htmlspecialchars($id);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $data = $stmt->fetchObject();
        $this->setMembers($data);
        return $this;
    }

    /**
     * Insire um novo endereço no banco de dados
     * 
     * @return object | bool
     */
    public function create()
    {
        // Constrói e prepara a query
        $query = "INSERT INTO " . $this->table . " SET user_id=:user_id, zip=:zip, address=:address, number=:number, complement=:complement, district=:district, city=:city, state=:state";
        $conn = Database::connection();
        $stmt = $conn->prepare($query);

        // Filtra input do usuário
        $this->user_id = htmlspecialchars($this->user_id);
        $this->zip = htmlspecialchars($this->zip);
        $this->address = htmlspecialchars($this->address);
        $this->number = htmlspecialchars($this->number);
        $this->complement = htmlspecialchars($this->complement);
        $this->district = htmlspecialchars($this->district);
        $this->city = htmlspecialchars($this->city);
        $this->state = htmlspecialchars($this->state);

        // Binda parâmetros
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":zip", $this->zip);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":number", $this->number);
        $stmt->bindParam(":complement", $this->complement);
        $stmt->bindParam(":district", $this->district);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":state", $this->state);
        
        // Executa query e pega o ID do usuário inserido
        $exec = $stmt->execute();
        $this->id = $conn->lastInsertId();
        
        // Se executado com sucesso, devolve o objeto do usuário
        if($exec)
        {   
            return $this;
        }
        
        // Se não, retorna falso
        return false;
    }

    /**
     * Atualiza um endereço existente no banco de dados
     * 
     * @return object | bool
     */
    public function save()
    {
        $query = "UPDATE " . $this->table . " SET complement=:complement WHERE id=:id";
        $stmt = Database::connection()->prepare($query);

        // Filtra input do usuário
        $this->complement = htmlspecialchars($this->complement);

        // Binda parâmetros
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":complement", $this->complement);

        $exec = $stmt->execute();

        // Se executado com sucesso, devolve o objeto do usuário
        if($exec)
        {   
            return $this;
        }
        
        // Se não, retorna falso
        return false;
    }

    /**
     * Remove um endereço do banco de dados
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = Database::connection()->prepare($query);
        $id = htmlspecialchars($id);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Seta membros da classe
     * 
     * @param object $data
     */
    private function setMembers($data)
    {
        if(isset($data->id)) $this->id = $data->id;
        if(isset($data->user_id)) $this->user_id = $data->user_id;
        if(isset($data->zip)) $this->zip = $data->zip;
        if(isset($data->address)) $this->address = $data->address;
        if(isset($data->number)) $this->number = $data->number;
        if(isset($data->complement)) $this->complement = $data->complement;
        if(isset($data->district)) $this->district = $data->district;
        if(isset($data->city)) $this->city = $data->city;
        if(isset($data->state)) $this->state = $data->state;
    }
}
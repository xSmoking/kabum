<?php

class User
{
    private $table = "users";

    public $id;
    public $name;
    public $birthdate;
    public $password;
    public $cpf;
    public $rg;
    public $phone;
  
    public function __construct($data = null)
    {
        if($data)
        {
            $this->setMembers($data);
        }
    }

    /**
     * Retorna um array com todos os usuários
     *
     * @return array
     */
    public function show()
    {
        $query = "SELECT * FROM " . $this->table . "";
        $stmt = Database::connection()->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * Busca um usuário pelo ID
     * 
     * @param int $id
     * @return object
     */
    public function find($id)
    {
        $query = "SELECT name, birthdate, password, cpf, rg, phone FROM " . $this->table . " WHERE id=:id";
        $stmt = Database::connection()->prepare($query);
        $this->id = htmlspecialchars($id);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $data = $stmt->fetchObject();

        $this->setMembers($data);

        return $this;
    }

    /**
     * Busca um usuário pelo ID
     * 
     * @param int $cpf
     * @return object
     */
    public function getByCPF($cpf)
    {
        $query = "SELECT id, name, birthdate, password, cpf, rg, phone FROM " . $this->table . " WHERE cpf=:cpf";
        $stmt = Database::connection()->prepare($query);
        $this->cpf = htmlspecialchars($cpf);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->execute();
        $data = $stmt->fetchObject();
        
        $this->setMembers($data);

        return $this;
    }

    /**
     * Busca um usuário pelo ID
     * 
     * @param int $rg
     * @return object
     */
    public function getByRG($rg)
    {
        $query = "SELECT id, name, birthdate, password, cpf, rg, phone FROM " . $this->table . " WHERE rg=:rg";
        $stmt = Database::connection()->prepare($query);
        $this->rg = htmlspecialchars($rg);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->execute();
        $data = $stmt->fetchObject();
        
        $this->setMembers($data);

        return $this;
    }

    /**
     * Insire um novo usuário no banco de dados
     * 
     * @return object | bool
     */
    public function create()
    {
        // Constrói e prepara a query
        $query = "INSERT INTO " . $this->table . " SET name=:name, birthdate=:birthdate, password=:password, cpf=:cpf, rg=:rg, phone=:phone";
        $conn = Database::connection();
        $stmt = $conn->prepare($query);

        // Filtra input do usuário
        $this->name = htmlspecialchars($this->name);
        $this->birthdate = substr($this->birthdate, 0, 2)."/".substr($this->birthdate, 2, 2)."/".substr($this->birthdate, 4, 4);
        $this->birthdate = implode('-', array_reverse(explode('/',  $this->birthdate)));
        $this->cpf = preg_replace('/\D/', '', $this->cpf);
        $this->rg = preg_replace('/\D/', '', $this->rg);
        $this->phone = preg_replace('/\D/', '', $this->phone);

        // Binda parâmetros
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthdate", $this->birthdate);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":phone", $this->phone);
        
        // Executa query e pega o ID do usuário inserido
        $exec = $stmt->execute();
        $this->id = $conn->lastInsertId();
        $this->password = null;
        
        // Se executado com sucesso, devolve o objeto do usuário
        if($exec)
        {   
            return $this;
        }
        
        // Se não, retorna falso
        return false;
    }

    /**
     * Atualiza um usuário existente no banco de dados
     * 
     * @return object | bool
     */
    public function save()
    {
        $query = "UPDATE " . $this->table . " SET name=:name, birthdate=:birthdate, cpf=:cpf, rg=:rg, phone=:phone WHERE id=:id";
        $stmt = Database::connection()->prepare($query);

        // Filtra input do usuário
        $this->name = htmlspecialchars($this->name);
        if(!strpos($this->birthdate, '/'))
        {
            $this->birthdate = substr($this->birthdate, 0, 2)."/".substr($this->birthdate, 2, 2)."/".substr($this->birthdate, 4, 4);
        }
        $this->birthdate = implode('-', array_reverse(explode('/',  $this->birthdate)));
        $this->cpf = preg_replace('/\D/', '', $this->cpf);
        $this->rg = preg_replace('/\D/', '', $this->rg);
        $this->phone = preg_replace('/\D/', '', $this->phone);

        // Binda parâmetros
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthdate", $this->birthdate);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":id", $this->id);

        $exec = $stmt->execute();
        $this->password = null;

        // Se executado com sucesso, devolve o objeto do usuário
        if($exec)
        {
            return $this;
        }
        
        // Se não, retorna falso
        return false;
    }

    /**
     * Remove um usuário do banco de dados
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
        if(isset($data->name)) $this->name = $data->name;
        if(isset($data->birthdate)) $this->birthdate = $data->birthdate;
        if(isset($data->password)) $this->password = $data->password;
        if(isset($data->cpf)) $this->cpf = $data->cpf;
        if(isset($data->rg)) $this->rg = $data->rg;
        if(isset($data->phone)) $this->phone = $data->phone;
    }
}
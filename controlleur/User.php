<?php // permet de gerer UN User specifiquement
class User
{

    private int $id;
    private string $first_name;
    private string $last_name;
    private string $username;
    private string $password;
    private string $email;

    public function __construct(array $datas)
    {
        $this->hydrate($datas);
    }

    public function hydrate(array $datas)
    {
        foreach ($datas as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }


    public function setId($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $this->id = $id;
        }
    }

    public function setUsername($username)
    {
        if (is_string($username)) {
            $this->username = $username;
        }
    }

    public function setPassword($password)
    {
        if (is_string($password)) {
            $this->password = $password;
        }
    }

    public function setEmail($email)
    {
        if (is_string($email)) {
            $this->email = $email;
        }
    }

    public function setFirst_name($first_name)
    {
        if (is_string($first_name)) {
            $this->first_name = $first_name;
        }
    }


    public function setLast_name($last_name)
    {
        if (is_string($last_name)) {
            $this->last_name = $last_name;
        }
    }
}

<?php // permet de gerer UN commentaire specifiquement

class Commentaire
{
    private $id;
    private $id_post;
    private $content;
    private $created_at;
    private $user_id;
    private $user_name;


    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $cle => $valeur) {
            $methode = 'set' . ucfirst($cle);
            if (method_exists($this, $methode)) {
                $this->$methode($valeur);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id > 0) {
            $this->id = $id;
            return $this;
        }
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        if (is_string($content)) {
            $this->content = $content;
            return $this;
        }
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getId_commentaire()
    {
        return $this->id_commentaire;
    }

    public function setId_commentaire($id_commentaire)
    {
        $this->id_commentaire = $id_commentaire;

        return $this;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get the value of id_post
     */
    public function getId_post()
    {
        return $this->id_post;
    }

    /**
     * Set the value of id_post
     *
     * @return  self
     */
    public function setId_post($id_post)
    {
        $this->id_post = $id_post;

        return $this;
    }

    /**
     * Get the value of user_name
     */
    public function getUser_name()
    {
        return $this->user_name;
    }

    /**
     * Set the value of user_name
     *
     * @return  self
     */
    public function setUser_name($user_name)
    {
        $this->user_name = $user_name;

        return $this;
    }
}

<?php // permet d'interagir avec un commentaire de la base de données commentaires
class CommentairesManager
{
    private PDO $db;

    public function __construct()
    {
        $dbName = 'projet';
        $port = 3306;
        $username = 'root';
        $password = 'root';
        try {
            $this->setDb(new PDO("mysql:host=localhost;dbname=$dbName;port=$port", $username));
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function getLastCommentId()
    {
        $req = $this->db->query("SELECT id FROM `commentaire` ORDER BY id DESC");
        $req->execute();

        $donnees = $req->fetch();
        $commentaire = new Commentaire($donnees);
        return $commentaire->getId();
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function add(Commentaire $commentaire)
    {
        $req = $this->db->prepare("INSERT INTO `commentaire` (id_post, content, created_at, user_id, user_name) VALUES(:id_post, :content, :created_at, :user_id, :user_name)");
        $req->bindValue(":id_post", $commentaire->getId_post(), PDO::PARAM_INT);
        $req->bindValue(":content", $commentaire->getContent(), PDO::PARAM_STR);
        $req->bindValue(":created_at", $commentaire->getCreated_at(), PDO::PARAM_STR);
        $req->bindValue(":user_id", $commentaire->getUser_id(), PDO::PARAM_STR);
        $req->bindValue(":user_name", $commentaire->getUser_name(), PDO::PARAM_STR);

        $req->execute();
    }

    public function update(Commentaire $commentaire)
    {
        $req = $this->db->prepare("UPDATE `commentaire` SET id_post = :id_post, content = :content, created_at = :created_at, user_id = :user_id, user_name = :user_name WHERE id = :id");

        $req->bindValue(":id", $commentaire->getId(), PDO::PARAM_INT);
        $req->bindValue(":id_post", $commentaire->getId_post(), PDO::PARAM_INT);
        $req->bindValue(":content", $commentaire->getContent(), PDO::PARAM_STR);
        $req->bindValue(":created_at", $commentaire->getCreated_at(), PDO::PARAM_STR);
        $req->bindValue(":user_id", $commentaire->getUser_id(), PDO::PARAM_STR);
        $req->bindValue(":user_name", $commentaire->getUser_name(), PDO::PARAM_STR);

        $req->execute();
    }

    public function get(int $id)
    {
        $req = $this->db->prepare("SELECT * FROM `commentaire` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch();
        if ($donnees) {
            $commentaire = new Commentaire($donnees);
        }
        return $commentaire;
    }

    public function getAll(): array
    {
        $commentaire = [];
        $req = $this->db->query("SELECT * FROM `commentaire`");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $commentaire[] = new Commentaire($donnee);
        }

        return $commentaire;
    }

    public function delete(int $id): void
    {
        $req = $this->db->prepare("DELETE FROM `commentaire` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}

<?php // permet d'interagir avec un dislike de la base de données likes
class DislikesManager
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
        $req = $this->db->query("SELECT id FROM `dislikes` ORDER BY id DESC");
        $req->execute();

        $donnees = $req->fetch();
        $dislike = new Dislike($donnees);
        return $dislike->getId();
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function add(Dislike $dislike)
    {
        $req = $this->db->prepare("INSERT INTO `dislikes` (post_id, disliker_id, user_id) VALUES(:post_id, :disliker_id, :user_id)");
        $req->bindValue(":post_id", $dislike->getPost_id(), PDO::PARAM_INT);
        $req->bindValue(":disliker_id", $dislike->getDisliker_id(), PDO::PARAM_INT);
        $req->bindValue(":user_id", $dislike->getUser_id(), PDO::PARAM_INT);

        $req->execute();
    }

    public function getAll(): array
    {
        $dislike = [];
        $req = $this->db->query("SELECT * FROM `dislikes`");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $dislike[] = new Commentaire($donnee);
        }

        return $dislike;
    }

    public function countRelated($post_id)
    {
        $req = $this->db->query("SELECT COUNT(*) FROM `dislikes` WHERE post_id = $post_id");
        $req->execute();

        $donnees = $req->fetchAll();

        return $donnees;
    }

    public function delete(int $post_id, int $disliker_id): void
    {
        $req = $this->db->prepare("DELETE FROM `dislikes` WHERE post_id = :post_id AND disliker_id = :disliker_id");
        $req->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $req->bindValue(":disliker_id", $disliker_id, PDO::PARAM_INT);
        $req->execute();
    }

    public function getPrecDislike(int $post_id, int $disliker_id): bool
    {
        $req = $this->db->prepare("SELECT * FROM `dislikes` WHERE post_id = :post_id AND disliker_id = :disliker_id");
        $req->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $req->bindValue(":disliker_id", $disliker_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();

        if (empty($donnees)) {
            return False;
        } else {
            return True;
        }
    }

    public function getRelated(int $user_id)
    {
        $req = $this->db->prepare("SELECT * FROM `dislikes` WHERE user_id = :user_id");
        $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $likes[] = new Dislike($donnee);
        }

        return $likes;
    }

    public function countUserRelated(int $user_id)
    {
        $req = $this->db->prepare("SELECT COUNT(*) FROM `dislikes` WHERE user_id = :user_id");
        $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();

        return $donnees;
    }
}

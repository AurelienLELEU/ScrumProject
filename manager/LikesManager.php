<?php // permet d'interagir avec un like de la base de données likes
class LikesManager
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
        $req = $this->db->query("SELECT id FROM `likes` ORDER BY id DESC");
        $req->execute();

        $donnees = $req->fetch();
        $like = new Like($donnees);
        return $like->getId();
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function add(Like $like)
    {
        $req = $this->db->prepare("INSERT INTO `likes` (post_id, liker_id, user_id) VALUES(:post_id, :liker_id, :user_id)");
        $req->bindValue(":post_id", $like->getPost_id(), PDO::PARAM_INT);
        $req->bindValue(":liker_id", $like->getLiker_id(), PDO::PARAM_INT);
        $req->bindValue(":user_id", $like->getUser_id(), PDO::PARAM_INT);

        $req->execute();
    }

    public function getAll(): array
    {
        $like = [];
        $req = $this->db->query("SELECT * FROM `likes`");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $like[] = new Commentaire($donnee);
        }

        return $like;
    }

    public function countRelated($post_id)
    {
        $req = $this->db->query("SELECT COUNT(*) FROM `likes` WHERE post_id = $post_id");
        $req->execute();

        $donnees = $req->fetchAll();

        return $donnees;
    }

    public function delete(int $post_id, int $liker_id): void
    {
        $req = $this->db->prepare("DELETE FROM `likes` WHERE post_id = :post_id AND liker_id = :liker_id");
        $req->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $req->bindValue(":liker_id", $liker_id, PDO::PARAM_INT);
        $req->execute();
    }

    public function getPrecLike(int $post_id, int $liker_id): bool
    {
        $req = $this->db->prepare("SELECT * FROM `likes` WHERE post_id = :post_id AND liker_id = :liker_id");
        $req->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $req->bindValue(":liker_id", $liker_id, PDO::PARAM_INT);
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
        $req = $this->db->prepare("SELECT * FROM `likes` WHERE user_id = :user_id");
        $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $likes[] = new Like($donnee);
        }

        return $likes;
    }

    public function countUserRelated(int $user_id)
    {
        $req = $this->db->prepare("SELECT COUNT(*) FROM `likes` WHERE user_id = :user_id");
        $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();

        return $donnees;
    }
}

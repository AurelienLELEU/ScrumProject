<?php // permet d'interagir avec un post de la base de données posts
class PostsManager
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

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function add(Post $post)
    {
        $req = $this->db->prepare("INSERT INTO `post` (content, created_at, user_id, user_name) VALUES(:content, :created_at, :user_id, :user_name)");
        $req->bindValue(":content", $post->getContent(), PDO::PARAM_STR);
        $req->bindValue(":created_at", $post->getCreated_at(), PDO::PARAM_STR);
        $req->bindValue(":user_id", $post->getUser_id(), PDO::PARAM_INT);
        $req->bindValue(":user_name", $post->getUser_name(), PDO::PARAM_STR);

        $req->execute();
    }

    public function update(Post $post)
    {
        $req = $this->db->prepare("UPDATE `post` SET content = :content, created_at = :created_at, user_id = :user_id, user_name = :user_name WHERE id = :id");

        $req->bindValue(":id", $post->getId(), PDO::PARAM_INT);
        $req->bindValue(":content", $post->getContent(), PDO::PARAM_STR);
        $req->bindValue(":created_at", $post->getCreated_at(), PDO::PARAM_STR);
        $req->bindValue(":user_id", $post->getUser_id(), PDO::PARAM_INT);
        $req->bindValue(":user_name", $post->getUser_name(), PDO::PARAM_STR);

        $req->execute();
    }

    public function get(int $id)
    {
        $req = $this->db->prepare("SELECT * FROM `post` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch();
        if ($donnees) {
            $post = new Post($donnees);
            return $post;
        }
    }

    public function getAll($filter): array
    {
        $posts = [];
        $req = $this->db->query("SELECT * FROM `post` WHERE user_name LIKE '%$filter%' OR content LIKE '%$filter%' OR user_id LIKE '%$filter%' ORDER BY created_at DESC");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $posts[] = new Post($donnee);
        }

        return $posts;
    }

    public function getRelated($filter, int $user_id): array
    {
        $posts = [];
        $req = $this->db->query("SELECT * FROM `post` WHERE user_id = $user_id AND content LIKE '%$filter%' ORDER BY created_at DESC");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $posts[] = new Post($donnee);
        }

        return $posts;
    }

    public function countRelated(int $user_id)
    {
        $req = $this->db->prepare("SELECT COUNT(*) FROM `post` WHERE user_id = :user_id");
        $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetchAll();

        return $donnees;
    }

    public function delete(int $id): void
    {
        $req = $this->db->prepare("DELETE FROM `post` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}

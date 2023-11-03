<?php // permet d'interagir avec une image de la base de données images
class ImagesManager
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

    public function getLastImageId()
    {
        $req = $this->db->query("SELECT id FROM `image` ORDER BY id DESC");
        $req->execute();

        $donnees = $req->fetch();
        $image = new Image($donnees);
        return $image->getId();
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function add(Image $image)
    {
        $req = $this->db->prepare("INSERT INTO `image` (name,image) VALUES(:name,:image)");

        $req->bindValue(":name", $image->getName(), PDO::PARAM_STR);
        $req->bindValue(":image", $image->getImage(), PDO::PARAM_STR);

        $req->execute();
    }

    public function update(Image $image)
    {
        $req = $this->db->prepare("UPDATE `image` SET name = :name, image = :image WHERE id = :id");

        $req->bindValue(":id", $image->getId(), PDO::PARAM_INT);
        $req->bindValue(":name", $image->getName(), PDO::PARAM_STR);
        $req->bindValue(":image", $image->getImage(), PDO::PARAM_STR);

        $req->execute();
    }

    public function get(int $id)
    {
        $req = $this->db->prepare("SELECT * FROM `image` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch();
        if ($donnees) {
            $image = new Image($donnees);
        }
        return $image;
    }

    public function getwname($name)
    {
        $req = $this->db->prepare("SELECT * FROM `image` WHERE name = :name");
        $req->bindValue(":name", $name, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch();
        $image = new Image($donnees);
        return $image;
    }

    public function getAll(): array
    {
        $images = [];
        $req = $this->db->query("SELECT * FROM `image`");
        $req->execute();

        $donnees = $req->fetchAll();
        foreach ($donnees as $donnee) {
            $images[] = new Image($donnee);
        }

        return $images;
    }

    public function delete(int $id): void
    {
        $req = $this->db->prepare("DELETE FROM `image` WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}

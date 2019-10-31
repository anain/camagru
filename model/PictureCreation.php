<?php

require_once __DIR__."/DBConnection.php";

Class PictureCreation
{  
    private $dbh;
    public $user;
    public $basePath;
    public $srcPath;
    public $id;

    public function __construct($user)
    {
        $this->user = $user;
        $this->basePath = './static/pictureBases/'.$this->user.'.png';
    }

    public function merge($data)
    {
        $this->srcPath = './static/components/'.$data['component'];
        $picData = explode(',', $data['picBase']);
        $content = base64_decode($picData[1]);
        $file = fopen($this->basePath, "wb");
        fwrite($file, $content);
        fclose($file);
        $dest = imagecreatefrompng($this->basePath);
        $srcSize = getimagesize($this->srcPath);
        $destSize = getimagesize($this->basePath);
        $src = imagecreatefrompng($this->srcPath);
        imagecopyresized($dest, $src, $data['xStart'], $data['yStart'], 0, 0, $data['renderableWidth'], $data['renderableHeight'], $srcSize[0], $srcSize[1]);
        $this->sendToDb();
        imagepng($dest, 'static/gallery/'.$this->id.'.png');
        imagedestroy($dest);
        imagedestroy($src);
    }

    public function sendToDb()
    {
        $this->dbh = (new DBConnection())->con;
        $stmt = $this->dbh->prepare("INSERT INTO pictures(user_id, date) VALUES(?, NOW())");
        $this->dbh->beginTransaction();
        $stmt->execute(array($this->user));
        $this->id = $this->dbh->lastInsertId();
        $this->dbh->commit();
    }
}
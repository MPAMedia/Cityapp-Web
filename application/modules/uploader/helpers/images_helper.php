<?php


function loadAllImages()
{

    $path = Path::getPath(array("uploads", "images"));

    $data = array();

    if (!is_dir($path))
        return array();

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $data[] = $entry;

            }
        }
        closedir($handle);
    }


    return $data;

}


function _openDir($dir = "")
{
    if(!preg_match("#^[0-9]+$#",$dir)){

        $dir_ = json_decode($dir,JSON_OBJECT_AS_ARRAY);

        if(is_array($dir_))
            foreach ($dir_ as $d){
                $dir = $d;
            }

    }

    $path = Path::getPath(array("uploads", "images", $dir));

    $data = array();


    if (!is_dir($path))
        return array();


    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $path_2 = Path::addPath($path, array($entry));

                if (is_file($path_2)) {

                    $ar = explode(".", $entry);
                    $index = reset($ar);
                    $ext = end($ar);


                    $data[$index] = array(
                        "name" => $entry,
                        "path" => $path_2,
                        "url" => IMAGES_BASE_URL . $dir . "/$entry",
                        "ext" => $ext
                    );


                }

            }
        }
        closedir($handle);
    }

    if (!empty($data)) {
        $data["name"] = $dir;
    }

    return $data;

}


function _getAllSizes($id = "")
{

    if ($id != "") {
        $userDir = _openDir($id);
        return $userDir;

    }

}

function _removeDir($dir = '')
{
    $path = Path::getPath(array("uploads", "images", $dir));

    $data = _openDir($dir);

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $path_2 = Path::addPath($path, array($entry));
                if (is_file($path_2)) {
                    @unlink($path_2);
                }

            }
        }
        closedir($handle);
        rmdir($path);
    }


    return $data;

}


function getAllImgFolder()
{
    $path = Path::getPath(array("uploads", "images"));

    $data = array();

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $data[] = $entry;

            }
        }
        closedir($handle);
    }

    return $data;

}


function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
{
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster`enter code here`
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    die;
}


class UploaderHelper
{

    private $files;
    private $ext;
    private $size;
    private $errors;
    private $namedir;
    private $download;


    public function __construct($data = NULL, $type = '')
    {


        $this->files = $data;
        $this->ext = array("image/jpg", "image/jpeg", "image/png", "image/gif");
        $this->size = (1048576 * MAX_IMAGE_UPLOAD);
        $this->errors = array();
        $this->namedir = time() . rand(000, 99999);
        $this->download = FALSE;


        $this->createDir();
    }


    public function start64()
    {

        $ext = "jpeg";
        $dis = Path::getPath(array("uploads", "images", $this->namedir, "full.jpeg"));


        file_put_contents($dis, base64_decode($this->files));


        if (file_exists($dis)) {

            $image = new SimpleImage();
            $image->load($dis);


            /* $image->resizeToWidth(1000);
             //$image->copyright = getPath(array("template","copyright.png"));
             $newpath  = getPath(array("uploads","images",$this->namedir,"1000_1000",$name));
             $image->save($newpath);*/


            //$newpath  = Path::getPath(array("uploads","images",$this->namedir,"full.".$image->getExt()));
            //$image->save($newpath);

            $image->resizeToWidth(560);

            $newpath = Path::getPath(array("uploads", "images", $this->namedir, "560_560." . $image->getExt()));
            $image->save($newpath);


            $image->resizeToWidth(200);
            $newpath = Path::getPath(array("uploads", "images", $this->namedir, "200_200." . $image->getExt()));
            $image->save($newpath);


            $image->resizeToWidth(100);
            $newpath = Path::getPath(array("uploads", "images", $this->namedir, "100_100." . $image->getExt()));
            $image->save($newpath);
            $image->destroy();


            $imageData = _getAllSizes($this->namedir);


            /*if (!empty($imageData)) {

                $temppath = Path::getPath(array("uploads", "images", $this->namedir, "temp." . strtolower($ext)));
                //unlink($temppath);
                $imageData['html'] = "<img src='" . $imageData['200_200']['url'] . "' alt=''/>";

                $imageData['type'] = "image/" . strtolower($ext);
                $imageData['image_data'] = md5($this->namedir);
                $imageData['image'] = $this->namedir;
            }*/

            if (!empty($imageData)) {
                $temppath = Path::getPath(array("uploads", "images", $this->namedir, "temp." . strtolower($ext)));
                //unlink($temppath);


                $imageData['html'] = ""
                    . "<div data-id=\"".$this->namedir."\" class=\"image-uploaded cursor-draggable item_" . $this->namedir . "\">
                        <i class='index'></i><a  id=\"image-preview\">    
                           <img src='" . $imageData['200_200']['url'] . "' alt=''/>
                        </a>
                        
                        <div class=\"clear\"></div>
                        <a href=\"#\"  data=\"$this->namedir\" id=\"delete\"><i class=\"fa fa-trash\"></i>&nbsp;&nbsp;Delete</a></div>"
                    . "<input id=\"image-data\" type=\"hidden\" value=\"" . md5($this->namedir) . "\">"
                    . "";


                $imageData['type'] = "image/" . strtolower($ext);
                $imageData['image_data'] = md5($this->namedir);
                $imageData['image'] = $this->namedir;
            }

            return $imageData;
        }


    }


    public function start()
    {


        if ($this->files["error"] == UPLOAD_ERR_NO_TMP_DIR) {
            $this->errors['dir'] = "Error (no tmp dir) UPLOAD_ERR_NO_TMP_DIR:6";
        }

        if (isset($this->files['type']) AND !in_array(strtolower($this->files['type']), $this->ext)) {
            $this->errors['type'] = "The type's image isn't valid! " . $this->files['type'];
        }

        if (isset($this->files['size']) AND $this->files['size'] > $this->size) {
            $this->errors['size'] = "Error in size, the max size :" . MAX_IMAGE_UPLOAD." MB";
        }


        $ar = explode(".", $this->files['name']);
        $ext = end($ar);

        $dis = Path::getPath(array("uploads", "images", $this->namedir, "full." . strtolower($ext)));


        if (empty($this->errors)) {


            if (move_uploaded_file($this->files['tmp_name'], $dis)) {

                if (file_exists($dis)) {

                    $image = new SimpleImage();
                    $image->load($dis);


                    /* $image->resizeToWidth(1000);
                     //$image->copyright = getPath(array("template","copyright.png"));
                     $newpath  = getPath(array("uploads","images",$this->namedir,"1000_1000",$name));
                     $image->save($newpath);*/


                    //$newpath  = Path::getPath(array("uploads","images",$this->namedir,"full.".$image->getExt()));
                    //$image->save($newpath);

                    $image->resizeToWidth(560);


                    $newpath = Path::getPath(array("uploads", "images",
                        $this->namedir, "560_560." . $image->getExt()));


                    $image->save($newpath);


                    $image->resizeToWidth(200);
                    $newpath = Path::getPath(array("uploads", "images", $this->namedir, "200_200." . $image->getExt()));
                    $image->save($newpath);


                    $image->resizeToWidth(100);
                    $newpath = Path::getPath(array("uploads", "images", $this->namedir, "100_100." . $image->getExt()));
                    $image->save($newpath);
                    $image->destroy();

                    /*$image->resizeToWidth(70);
                    $newpath  = getPath(array("uploads","images",$this->namedir,"70_70",$name));
                    $image->save($newpath);


                    $image->resizeToWidth(60);
                    $newpath  = getPath(array("uploads","images",$this->namedir,"60_60",$name));
                    $image->save($newpath);


                    $image->resizeToWidth(48);
                    $newpath  = getPath(array("uploads","images",$this->namedir,"48_48",$name));
                    $image->save($newpath);


                     $image->resizeToWidth(35);
                    $newpath  = getPath(array("uploads","images",$this->namedir,"35_35",$name));
                    $image->save($newpath);

                    $image->resizeToWidth(23);
                    $newpath  = getPath(array("uploads","images",$this->namedir,"23_23",$name));
                    $image->save($newpath);
                    */


                    $imageData = _getAllSizes($this->namedir);


                    if (!empty($imageData)) {
                        $temppath = Path::getPath(array("uploads", "images", $this->namedir, "temp." . strtolower($ext)));
                        //unlink($temppath);

                        $imageData['html'] = ""
                            . "<div data-id=\"".$this->namedir."\" class=\"image-uploaded cursor-draggable item_" . $this->namedir . "\">
                        <i class='index'></i><a  id=\"image-preview\">    
                           <img src='" . $imageData['200_200']['url'] . "' alt=''/>
                        </a>
                        
                        <div class=\"clear\"></div>
                        <a href=\"#\"  data=\"$this->namedir\" id=\"delete\"><i class=\"fa fa-trash\"></i>&nbsp;&nbsp;Delete</a></div>"
                            . "<input id=\"image-data\" type=\"hidden\" value=\"" . md5($this->namedir) . "\">"
                            . "";


                        $imageData['type'] = "image/" . strtolower($ext);
                        $imageData['image_data'] = md5($this->namedir);
                        $imageData['image'] = $this->namedir;
                    }


                    return $imageData;
                }
            }


        }

        return array();
    }


    public function getErrors()
    {
        return $this->errors;
    }

    private function createDir()
    {

        /* $name_23_23 = getPath(array("uploads","images",$this->namedir,"23_23"));
         $name_35_35 = getPath(array("uploads","images",$this->namedir,"35_35"));
         $name_48_48 = getPath(array("uploads","images",$this->namedir,"48_48"));
         $name_60_60 = getPath(array("uploads","images",$this->namedir,"60_60"));
         $name_70_70 = getPath(array("uploads","images",$this->namedir,"70_70")); */
        $name_100_100 = Path::getPath(array("uploads", "images", $this->namedir, "100_100"));
        $name_200_200 = Path::getPath(array("uploads", "images", $this->namedir, "200_200"));
        $name_560_560 = Path::getPath(array("uploads", "images", $this->namedir, "560_560"));
        $name_1000_1000 = Path::getPath(array("uploads", "images", $this->namedir, "1000_1000"));
        $temp = Path::getPath(array("uploads", "images", $this->namedir, "temp"));


        @mkdir(Path::getPath(array("uploads", "images", $this->namedir)));
        /*@mkdir( $name_23_23);
        @mkdir( $name_35_35 );
        @mkdir( $name_48_48 );
        @mkdir( $name_60_60 );
        @mkdir( $name_70_70 );
        @mkdir( $name_100_100 );
        @mkdir( $name_200_200 );
        @mkdir( $name_560_560 );
        @mkdir( $name_1000_1000 );*/
        //@mkdir( $temp );

        return;
    }


}
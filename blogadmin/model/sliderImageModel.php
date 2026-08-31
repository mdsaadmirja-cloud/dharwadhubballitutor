<?php
class sliderImage implements JsonSerializable{
    private $imageFileId;
    private $image;
    private $ImageFileCaption;
    private $ImageAlternateText;
    private $createdby;
    private $createon;
    private $modifiedby;
    private $modifedon;
    public function jsonSerialize()
    {
        return   [
            'ImageFileId' => $this->imageFileId,
            'ImageFilePath' => $this->image,
            'ImageFileCaption' => $this->ImageFileCaption,
            'ImageAlternateText' =>$this->ImageAlternateText,
            'modifiedby' => $this->modifiedby,
            'createdby' => $this->createdby,
            'createon' => $this->createon,
            'modifedon' => $this->modifedon
        ];
    }
    
   

    /**
     * Get the value of imageFileId
     */
    public function getImageFileId()
    {
        return $this->imageFileId;
    }

    /**
     * Set the value of imageFileId
     */
    public function setImageFileId($imageFileId)
    {
        $this->imageFileId = $imageFileId;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of ImageFileCaption
     */
    public function getImageFileCaption()
    {
        return $this->ImageFileCaption;
    }

    /**
     * Set the value of ImageFileCaption
     */
    public function setImageFileCaption($ImageFileCaption)
    {
        $this->ImageFileCaption = $ImageFileCaption;

        return $this;
    }

    /**
     * Get the value of ImageAlternateText
     */
    public function getImageAlternateText()
    {
        return $this->ImageAlternateText;
    }

    /**
     * Set the value of ImageAlternateText
     */
    public function setImageAlternateText($ImageAlternateText)
    {
        $this->ImageAlternateText = $ImageAlternateText;

        return $this;
    }

    /**
     * Get the value of createdby
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set the value of createdby
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Get the value of createon
     */
    public function getCreateon()
    {
        return $this->createon;
    }

    /**
     * Set the value of createon
     */
    public function setCreateon($createon)
    {
        $this->createon = $createon;

        return $this;
    }

    /**
     * Get the value of modifiedby
     */
    public function getModifiedby()
    {
        return $this->modifiedby;
    }

    /**
     * Set the value of modifiedby
     */
    public function setModifiedby($modifiedby)
    {
        $this->modifiedby = $modifiedby;

        return $this;
    }

    /**
     * Get the value of modifedon
     */
    public function getModifedon()
    {
        return $this->modifedon;
    }

    /**
     * Set the value of modifedon
     */
    public function setModifedon($modifedon)
    {
        $this->modifedon = $modifedon;

        return $this;
    }
}
?>
<?php
class Post implements JsonSerializable{
    private $postId;
    private $postTitle;
    private $postDescription;
    private $postCreatedBy;
    private $modifiedBy;
    private $titleTag;
    private $keywords;
    private $mappedSubCategory;
    private $image;
    private $altTextImage;
    private $postUrl;
    private $LinkUnder;
    private $onHome;
    public function jsonSerialize(){
        return [
        'postId'=>$this->postId,
        'postTitle' =>$this->postTitle,
        'postDescription' =>$this->postDescription,
        'titleTag' =>$this->titleTag,
        'keywords' =>$this->keywords,
        'mappedSubCategory' =>$this->mappedSubCategory
        ];
    }

    /**
     * Get the value of postId
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set the value of postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get the value of postTitle
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * Set the value of postTitle
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    /**
     * Get the value of postDescription
     */
    public function getPostDescription()
    {
        return $this->postDescription;
    }

    /**
     * Set the value of postDescription
     */
    public function setPostDescription($postDescription)
    {
        $this->postDescription = $postDescription;

        return $this;
    }

    /**
     * Get the value of postCreatedBy
     */
    public function getPostCreatedBy()
    {
        return $this->postCreatedBy;
    }

    /**
     * Set the value of postCreatedBy
     */
    public function setPostCreatedBy($postCreatedBy)
    {
        $this->postCreatedBy = $postCreatedBy;

        return $this;
    }

    /**
     * Get the value of modifiedBy
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set the value of modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get the value of titleTag
     */
    public function getTitleTag()
    {
        return $this->titleTag;
    }

    /**
     * Set the value of titleTag
     */
    public function setTitleTag($titleTag)
    {
        $this->titleTag = $titleTag;

        return $this;
    }

    /**
     * Get the value of keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set the value of keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get the value of mappedSubCategory
     */
    public function getMappedSubCategory()
    {
        return $this->mappedSubCategory;
    }

    /**
     * Set the value of mappedSubCategory
     */
    public function setMappedSubCategory($mappedSubCategory)
    {
        $this->mappedSubCategory = $mappedSubCategory;

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
     * Get the value of altTextImage
     */
    public function getAltTextImage()
    {
        return $this->altTextImage;
    }

    /**
     * Set the value of altTextImage
     */
    public function setAltTextImage($altTextImage)
    {
        $this->altTextImage = $altTextImage;

        return $this;
    }

    /**
     * Get the value of postUrl
     */
    public function getPostUrl()
    {
        return $this->postUrl;
    }

    /**
     * Set the value of postUrl
     */
    public function setPostUrl($postUrl)
    {
        $this->postUrl = $postUrl;

        return $this;
    }

    /**
     * Get the value of onHome
     */
    public function getOnHome()
    {
        return $this->onHome;
    }

    /**
     * Set the value of onHome
     */
    public function setOnHome($onHome)
    {
        $this->onHome = $onHome;

        return $this;
    }


    /**
     * Get the value of LinkUnder
     */
    public function getLinkUnder()
    {
        return $this->LinkUnder;
    }

    /**
     * Set the value of LinkUnder
     */
    public function setLinkUnder($LinkUnder): self
    {
        $this->LinkUnder = $LinkUnder;

        return $this;
    }

}

?>
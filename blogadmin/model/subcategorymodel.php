<?php
class Subcategory implements JsonSerializable
{
    private $subCategoryId;
    private $subCategoryName;
    private $subCategoryDescription;
    private $mappedCategories;
    private $subCategoryModifiedOn;
    private $subCategoryCreatedBy;
    private $subCategoryCreatedOn;
    private $subCategoryModifiedBy;
    private $mappedPost;
    private $table_name = "item_subcategory";
    public function jsonSerialize()
    {
        return 
             [
                'itemsubcatid' => $this->subCategoryId,
                'itemsubcatname' => $this->subCategoryName,
                'itemsubcatdescription' => $this->subCategoryDescription,
                'mappedPost' =>$this->mappedPost,
             ];
     
    }

    /**
     * Get the value of subCategoryId
     */
    public function getSubCategoryId()
    {
        return $this->subCategoryId;
    }

    /**
     * Set the value of subCategoryId
     */
    public function setSubCategoryId($subCategoryId)
    {
        $this->subCategoryId = $subCategoryId;

        return $this;
    }

    /**
     * Get the value of subCategoryName
     */
    public function getSubCategoryName()
    {
        return $this->subCategoryName;
    }

    /**
     * Set the value of subCategoryName
     */
    public function setSubCategoryName($subCategoryName)
    {
        $this->subCategoryName = $subCategoryName;

        return $this;
    }

    /**
     * Get the value of subCategoryDescription
     */
    public function getSubCategoryDescription()
    {
        return $this->subCategoryDescription;
    }

    /**
     * Set the value of subCategoryDescription
     */
    public function setSubCategoryDescription($subCategoryDescription)
    {
        $this->subCategoryDescription = $subCategoryDescription;

        return $this;
    }

    /**
     * Get the value of subCategoryModifiedOn
     */
    public function getSubCategoryModifiedOn()
    {
        return $this->subCategoryModifiedOn;
    }

    /**
     * Set the value of subCategoryModifiedOn
     */
    public function setSubCategoryModifiedOn($subCategoryModifiedOn)
    {
        $this->subCategoryModifiedOn = $subCategoryModifiedOn;

        return $this;
    }
    /**
     * Get the value of subCategoryCreatedBy
     */
    public function getSubCategoryCreatedBy()
    {
        return $this->subCategoryCreatedBy;
    }

    /**
     * Set the value of subCategoryCreatedBy
     */
    public function setSubCategoryCreatedBy($subCategoryCreatedBy)
    {
        $this->subCategoryCreatedBy = $subCategoryCreatedBy;

        return $this;
    }

    /**
     * Get the value of subCategoryCreatedOn
     */
    public function getSubCategoryCreatedOn()
    {
        return $this->subCategoryCreatedOn;
    }

    /**
     * Set the value of subCategoryCreatedOn
     */
    public function setSubCategoryCreatedOn($subCategoryCreatedOn)
    {
        $this->subCategoryCreatedOn = $subCategoryCreatedOn;

        return $this;
    }

    /**
     * Get the value of subCategoryModifiedBy
     */
    public function getSubCategoryModifiedBy()
    {
        return $this->subCategoryModifiedBy;
    }

    /**
     * Set the value of subCategoryModifiedBy
     */
    public function setSubCategoryModifiedBy($subCategoryModifiedBy)
    {
        $this->subCategoryModifiedBy = $subCategoryModifiedBy;

        return $this;
    }

    /**
     * Get the value of mappedCategories
     */
    public function getMappedCategories()
    {
        return $this->mappedCategories;
    }

    /**
     * Set the value of mappedCategories
     */
    public function setMappedCategories($mappedCategories)
    {
        $this->mappedCategories = $mappedCategories;

        return $this;
    }

    /**
     * Get the value of mappedPost
     */
    public function getMappedPost()
    {
        return $this->mappedPost;
    }

    /**
     * Set the value of mappedPost
     */
    public function setMappedPost($mappedPost)
    {
        $this->mappedPost = $mappedPost;

        return $this;
    }
}

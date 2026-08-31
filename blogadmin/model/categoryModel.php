<?php
class Category implements JsonSerializable
{
    private $categoryId ;
    private $categoryName;
    private	$categoryDescription;
    private $hasSubcategory;
    private	$categoryModifiedOn;
    private	$categoryCreatedBy;
    private $categoryCreatedOn;
    private $categoryModifiedBy;
    private $mappedSubCategory;
    private $mappedPost;
    private $table_name="category";
    public function jsonSerialize()
    {
        return 
            [
                'CategoryId' => $this->categoryId,
                'CategoryName' => $this->categoryName,
                'CategoryDescription' => $this->categoryDescription,
                'MappedSubCategory' => $this->mappedSubCategory,
                'MappedPost' =>$this->mappedPost,
            ];
        
    }

    /**
     * Get the value of categoryid
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryid
     */
    public function setCategoryId($categoryid)
    {
        $this->categoryId = $categoryid;

        return $this;
    }

    /**
     * Get the value of categoryName
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set the value of categoryName
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get the value of categoryDescription
     */
    public function getCategoryDescription()
    {
        return $this->categoryDescription;
    }

    /**
     * Set the value of categoryDescription
     */
    public function setCategoryDescription($categoryDescription)
    {
        $this->categoryDescription = $categoryDescription;

        return $this;
    }

    /**
     * Get the value of categoryModifiedOn
     */
    public function getCategoryModifiedOn()
    {
        return $this->categoryModifiedOn;
    }

    /**
     * Set the value of categoryModifiedOn
     */
    public function setCategoryModifiedOn($categoryModifiedOn)
    {
        $this->categoryModifiedOn = $categoryModifiedOn;

        return $this;
    }

    /**
     * Get the value of categoryCreatedBy
     */
    public function getCategoryCreatedBy()
    {
        return $this->categoryCreatedBy;
    }

    /**
     * Set the value of categoryCreatedBy
     */
    public function setCategoryCreatedBy($categoryCreatedBy)
    {
        $this->categoryCreatedBy = $categoryCreatedBy;

        return $this;
    }

    /**
     * Get the value of categoryCreatedOn
     */
    public function getCategoryCreatedOn()
    {
        return $this->categoryCreatedOn;
    }

    /**
     * Set the value of categoryCreatedOn
     */
    public function setCategoryCreatedOn($categoryCreatedOn)
    {
        $this->categoryCreatedOn = $categoryCreatedOn;

        return $this;
    }

    /**
     * Get the value of categoryModifiedBy
     */
    public function getCategoryModifiedBy()
    {
        return $this->categoryModifiedBy;
    }

    /**
     * Set the value of categoryModifiedBy
     */
    public function setCategoryModifiedBy($categoryModifiedBy)
    {
        $this->categoryModifiedBy = $categoryModifiedBy;

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
     * Get the value of HasSubcategory
     */
    public function getHasSubcategory()
    {
        return $this->hasSubcategory;
    }

    /**
     * Set the value of HasSubcategory
     */
    public function setHasSubcategory($HasSubcategory)
    {
        $this->hasSubcategory = $HasSubcategory;

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

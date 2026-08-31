<?php
session_start();
include('header.php');
require_once("../dblayer/postOps.php");
?>

<style>
.card-body {
    height: 180px;
    overflow: hidden;
}
.card img {
    height: 180px;
    object-fit: cover;
}
</style>

<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between">
        <h6 class="font-weight-bold text-primary">Posts</h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#postModal">
            + Add Post
        </button>
    </div>
</div>

<div class="row">
<?php
$postList = DBpost::getPostList();

foreach ($postList as $post) {

    $desc = strip_tags($post->getPostDescription());
    $desc = substr($desc, 0, 120);
    $desc = substr($desc, 0, strrpos($desc, ' ')) . "...";

?>
    <div class="col-md-4 mb-4">
        <div class="card shadow h-100">

            <img src="../img/Post/<?php echo $post->getImage(); ?>"
                 alt="<?php echo $post->getAltTextImage(); ?>">

            <div class="card-body">
                <h5><?php echo $post->getPostTitle(); ?></h5>
                <p><?php echo $desc; ?></p>

                <?php foreach ($post->getMappedSubCategory() as $subcategory) { ?>
                    <span class="badge bg-primary">
                        <?php echo $subcategory->getSubCategoryName(); ?>
                    </span>
                <?php } ?>
            </div>

            <div class="card-footer text-end">
                <a href="postDetails.php?id=<?php echo $post->getPostId(); ?>" class="btn btn-sm btn-info">View</a>
                <a href="editPost.php?id=<?php echo $post->getPostId(); ?>" class="btn btn-sm btn-warning">Edit</a>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="<?php echo $post->getPostId(); ?>">Delete</button>
            </div>

        </div>
    </div>
<?php } ?>
</div>

<div class="modal fade" id="postModal">
    <div class="modal-dialog modal-xl">
        <form method="post" action="../controller/postController.php" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Add New Post</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Post Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>

                    <label class="mt-2">Title Tag</label>
                    <input type="text" name="titleTag" class="form-control">

                    <label class="mt-2">Meta Description</label>
                    <textarea name="metaDescription" class="form-control"></textarea>

                    <label class="mt-2">Focus Keyword</label>
                    <input type="text" name="focusKeyword" class="form-control">

                    <label class="mt-2">Post URL</label>
                    <input type="text" name="postURL" id="postURL" class="form-control">

                    <label class="mt-2">Image</label>
                    <input type="file" name="image" class="form-control">

                    <label class="mt-2">Image Alt Text</label>
                    <input type="text" name="alttext" class="form-control">

                    <label class="mt-2">Content</label>
                    <textarea name="content" class="form-control" rows="6"></textarea>

                    <div class="form-check mt-2">
                        <input type="checkbox" name="onHome" class="form-check-input">
                        <label>Show on Home</label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
document.getElementById("title").addEventListener("keyup", function() {
    let slug = this.value.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
    document.getElementById("postURL").value = '/' + slug;
});

document.querySelectorAll(".deleteBtn").forEach(btn => {
    btn.addEventListener("click", function() {
        if(confirm("Delete this post?")){
            window.location.href = "../controller/postController.php?action=delete&id=" + this.dataset.id;
        }
    });
});
</script>

<?php include('footer.php'); ?>

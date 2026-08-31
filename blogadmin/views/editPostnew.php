<form method="post" action="postController.php">

<label>Title</label>
<input type="text" name="title" required>

<label>Title Tag</label>
<input type="text" name="titleTag">

<label>Meta Description</label>
<textarea name="metaDescription"></textarea>

<label>Focus Keyword</label>
<input type="text" name="focusKeyword">

<label>URL</label>
<input type="text" name="postURL">

<label>Content</label>
<textarea name="content"></textarea>

<label>Show on Home</label>
<input type="checkbox" name="onHome">

<button type="submit">Save</button>
</form>

<script>
document.querySelector('[name="title"]').addEventListener('keyup', function(){
    let slug = this.value.toLowerCase().replace(/ /g,'-').replace(/[^a-z0-9-]/g,'');
    document.querySelector('[name="postURL"]').value = '/' + slug;
});
</script>

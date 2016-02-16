<h1>Single file form</h1>
<form action="/" method="post" enctype="multipart/form-data">
    <input type="file" name="file" >
    <input type="submit" value="upload">
</form>
<hr>
<h1>Multiple file form</h1>
<form action="/" method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <input type="submit" value="upload">
</form>
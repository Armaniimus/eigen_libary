<!DOCTYPE html>
<html>
    <meta charset="utf-8">
    <title>crud</title>

</html>

<form action='' method="post">
    <table border="1">
        <tr><td>    Url:</td><td>
            <select id="select">
                <?php include('select.php') ?>
            </select>                                                                                   </td><tr>
        <tr><td>    </td><td>           <input id="input" name="url" type='text' value="">              </td><tr>
        <tr><td>    Content:</td><td>   <?php include('crud_Proxy.php');?>                              </td><tr>
        <tr><td>    Create:</td><td>    <input type='submit' name="create" value='Create'>              </td><tr>
        <tr><td>    Read:</td><td>      <input type='submit' name='read' value='Read'>                  </td><tr>
        <tr><td>    Update:</td><td>    <input type='submit' name='update' value='Update'>              </td><tr>
        <tr><td>    Delete:</td><td>    <input type='submit' name='delete' value='Delete'>              </td><tr>
    </table>
</form>

<script>
    document.getElementById('select').addEventListener('change', function (){
            document.getElementById('input').value = document.getElementById('select').value;
        }
    );
</script>

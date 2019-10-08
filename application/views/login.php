<?PHP
    defined('BASEPATH') OR exit('rika mau ngapa kang? :/');
?>
<?PHP echo $form ?>
    <?PHP echo flashdata("error") ?>
    <div>
        <?PHP echo form_error("username") ?>
        <input type="text" name="username" placeholder="Username...">
    </div><br>
    <div>
        <?PHP echo form_error("password") ?>
        <input type="password" name="password" placeholder="Password...">
    </div><br>
    <button>OKE</button>
</form>
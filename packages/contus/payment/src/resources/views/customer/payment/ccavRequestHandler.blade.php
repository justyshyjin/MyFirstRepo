
<form method="post" name="redirect" action="<?php echo $urls['ccavenueinittransaction'];?>"> 
<?php 
echo "<input type=hidden name=encRequest value=$data>";
echo "<input type=hidden name=user_id value='1'>";
echo "<input type=hidden name=access_code value='".$urls['accessCode']."'>";
?>
</form>
<script language='javascript'>document.redirect.submit();</script>

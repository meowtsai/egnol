<? if ($error_message):?>
<div class="alert alert-error"><i class="icon-remove-circle"></i> <?=$error_message?></div>
<? endif;?>

<form class="form-horizontal" method="post">
  <fieldset>
    <input type="hidden" name="redirect_url" value="<?=$redirect_url?>">
    <legend>登入</legend>
    <input name="account" type="text" placeholder="帳號">
    <input name="password" type="password" placeholder="密碼">
    <button type="submit" class="btn">確認送出</button>
  </fieldset>
</form>
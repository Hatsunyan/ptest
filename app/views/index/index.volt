<div class="register-login">
    <div class="mode-switch">
        <div>Регистрация</div>
        <div>Вход</div>
    </div>
    <form action="user/register" method="post">
        <div class="form-input">
            <label for="email">Имя</label>
            <?=$this->tag->textField("name"); ?>
        </div>
        <div class="form-input">
            <label for="email">Email</label>
            <?=$this->tag->textField("email"); ?>
        </div>
        <div class="form-input">
            <label for="password">Пароль</label>
            <?=$this->tag->passwordField('password') ?>
        </div>
        <div class="form-input">
            <?=$this->tag->submitButton("Регистрация"); ?>
        </div>
    </form>
    <form action="user/login" method="post">
        <div class="form-input">
            <label for="email">Email</label>
            <?=$this->tag->textField("email"); ?>
        </div>
        <div class="form-input">
            <label for="password">Пароль</label>
            <?=$this->tag->passwordField('password') ?>
        </div>
        <div class="form-input">
            <?=$this->tag->submitButton("Вход"); ?>
        </div>
    </form>
</div>
<script>
    var switchers = document.querySelectorAll('.mode-switch > div');
    var forms = document.querySelectorAll('form');
    switchers[0].addEventListener('click',function()
    {
        forms[0].style.display = 'block';
        forms[1].style.display = 'none';
    });
    switchers[1].addEventListener('click',function()
    {
        forms[1].style.display = 'block';
        forms[0].style.display = 'none';
    });
</script>

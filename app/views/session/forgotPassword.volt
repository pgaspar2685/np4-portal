{{ content() }}

<h5 class="card-title text-center">Sign In</h5>

{{ form('class': 'form-search') }}

<div class="form-label-group">
	{{ form.render('email') }}
	<label for="email">Email</label>
</div>

{{ form.render('Recuperar password') }}

<hr>

{# <div>{{ link_to("session/signup", "Criar nova conta") }}</div> #}
<div>{{ link_to("session/login", "Já tem conta criada? Faça login") }}</div>

</form>

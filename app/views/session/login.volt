{{ content() }}
{{ form('class': 'form-signin user', 'action': '/session/login') }}

	<div class="form-group">
		{{ form.render('email') }}
		{# <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" value="{{ email }}" placeholder="Enter Email Address..."> #}
	</div>
	<div class="form-group">
		{{ form.render('password') }}
		{# <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password"> #}
	</div>

	<div class="custom-control custom-checkbox mb-3">
		{{ form.render('remember') }}
		<label class="custom-control-label" for="remember">Remember password</label>
	</div>
	
	{# {{ form.render('csrf', ['value': security.getToken()]) }} #}
	
	<button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Login</button>
	
	{# <hr class="my-4"> #}
	
	{# <div>{{ link_to("session/signup", "Ainda não tem conta? Criar nova") }}</div>
	<div>{{ link_to("session/forgotPassword", "Esqueceu-se da password?") }}</div> #}
	
{{ end_form() }}
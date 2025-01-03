{{ content() }}

{% if password_changed is empty %}
<form method="post" autocomplete="off" action="{{ url("users/changePassword") }}">

	<h2>Alterar Password</h2>
	
    <div class="center scaffold" style="max-width: 250px;">

        <div class="form-label-group">
            {{ form.render("password") }}
            <label for="password">Password</label>
        </div>

        <div class="form-label-group">
            {{ form.render("confirmPassword") }}
            <label for="confirmPassword">Confirme a Password</label>
        </div>

        <div class="clearfix">
            {{ submit_button("Alterar Password", "class": "btn btn-primary") }}
        </div>

    </div>

</form>
{% endif %}
{{ content() }}

<form method="post" autocomplete="off" action="{{ url("users/config") }}">

	<h2>Definir banca</h2>
	
    <div class="center scaffold" style="max-width: 250px;">

        <div class="form-label-group">
            {{ form.render("banca") }}
            <label for="banca">Banca</label>
        </div>

        <div class="clearfix">
            <button class="btn btn-primary">Submeter</button>
        </div>

    </div>

</form>
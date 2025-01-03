{{ content() }}

{{ form(controller ~ "/deleteGoal/" ~ id_event, "role" : "form", "ajax" : "true", "onsubmit" : "return;") }} 
<div class="alert alert-danger">A seguinte acção vai apagar este registo. Tem a certeza?</div>
<button type="submit" class="btn btn-danger">Eliminar</button>	
{{ endform() }}
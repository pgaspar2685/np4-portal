{% if match.id_match_status is defined AND match.id_match_status == 3 %}
<div class="pt-3 h3 mb-0 text-gray-800">Comentários:</div>
<div class="mb-3">
    {{ form('action': '/backoffice/setComentaries/' ~ match.id_match, 'id': 'form-match-overview', 'class': 'needs-validation', 'novalidate': 'novalidate', 'onsubmit': 'return App.submitForm(this, {})', "autocomplete" : "off") }}
    <div>{{ match.t1_ds_team }} - Equipa Casa</div>

    <input type="hidden" name="t1_id_admin" value="{{ coaches["t1"]["id"] }}">
    <input type="hidden" name="t1_ds_name" value="{{ coaches["t1"]["ds_name"] }}">
    <input type="hidden" name="t1_ds_owner" value="{{ coaches["t1"]["ds_owner"] }}">
    <input type="hidden" name="t1_fl_main_owner" value="1">
    <input type="hidden" name="t2_id_admin" value="{{ coaches["t2"]["id"] }}">
    <input type="hidden" name="t2_ds_name" value="{{ coaches["t2"]["ds_name"] }}">
    <input type="hidden" name="t2_ds_owner" value="{{ coaches["t2"]["ds_owner"] }}">
    <input type="hidden" name="t2_fl_main_owner" value="1">

    <div>
        <textarea class="form-control" rows="6" name="t1_ds_overview">{% if match.post.t1_ds_overview is defined %}{{ match.post.t1_ds_overview }}{% endif %}</textarea>
    </div>
    <div class="mt-3">{{ match.t2_ds_team }} - Equipa Fora</div>
    <div>
        <textarea class="form-control" rows="6" name="t2_ds_overview">{% if match.post.t2_ds_overview is defined %}{{ match.post.t2_ds_overview }}{% endif %}</textarea>
    </div>
    <button type="submit" class="mt-3 btn btn-primary">Submeter Comentários</button>
    {{ endform() }}
</div>
{% endif %}
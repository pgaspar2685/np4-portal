{% if event is defined %}
    {{ form('action': '/backoffice/editGoal/' ~ event.id_event, 'id': 'form-goal', 'class': 'needs-validation', 'novalidate': 'novalidate', 'onsubmit': 'return App.submitForm(this, {})', 'targetdiv' : '#goalModal', "autocomplete" : "off") }}
{% else %}
    {{ form('action': '/backoffice/insertGoal/' ~ match.id_match, 'id': 'form-goal', 'class': 'needs-validation', 'novalidate': 'novalidate', 'onsubmit': 'return App.submitForm(this, {})', 'targetdiv' : '#goalModal', "autocomplete" : "off") }}
{% endif %}

    <input type="hidden" name="id_match" value="{{ match.id_match }}">

    {% if event is defined %}
    {% else %}
    <div class="form-group">
        <div>Seleccione a Equipa</div>
        <input type="hidden" name="team_error" id="team_error">
        <div>
            <div>
                <input type="radio" class="btn-check" name="id_team" value="{{ match.t1_id_team }}" id="team1" autocomplete="off" {{ event.id_team is defined AND event.id_team == match.t1_id_team? 'checked' : '' }}>
                <label class="btn btn-secondary" for="team1">
                    <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t1_id_team }}.png" style="width: 20px; height: auto;">
                    <span>{{ match.t1_ds_team }}</span>
                </label>
            </div>

            <div>
                <input type="radio" class="btn-check" name="id_team" value="{{ match.t2_id_team }}" id="team2" autocomplete="off" {{ event.id_team is defined AND event.id_team == match.t2_id_team? 'checked' : '' }}>
                <label class="btn btn-secondary" for="team2">
                    <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t2_id_team }}.png" style="width: 20px; height: auto;">
                    <span>{{ match.t2_ds_team }}</span>
                </label>
            </div>
        </div>
    </div>
    {% endif %}

    <div class="form-group">
        <div>Seleccione a Parte</div>
        <input type="hidden" name="half_error" id="half_error">
        <div>
            <div>
                <input type="radio" class="btn-check" name="cd_half" value="1" id="cd_half1" autocomplete="off" {{ event.cd_half is defined AND event.cd_half == 1? 'checked' : '' }}>
                <label class="btn btn-secondary" for="cd_half1">
                    1ª Parte
                </label>
            </div>
            <div>
                <input type="radio" class="btn-check" name="cd_half" value="2" id="cd_half2" autocomplete="off" {{ event.cd_half is defined AND event.cd_half == 2? 'checked' : '' }}>
                <label class="btn btn-secondary" for="cd_half2">
                    2ª Parte
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div>Minuto</div>
        {{ form.render('cd_minute') }}
        {# <input type="number" name="cd_minute" value="" style="width: 80px" min="0" max="120"> #}
    </div>

    {# <button type="submit" id="submitGoal" class="d-none">gravar</button> #}

{{ end_form() }}   